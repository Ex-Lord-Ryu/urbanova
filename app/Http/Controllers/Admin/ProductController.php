<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load products with their category, colors, and sizes
        $products = Product::with(['category', 'colors', 'sizes'])->latest()->paginate(10);

        // Extract product IDs from the paginated collection
        $productIds = [];
        foreach ($products as $product) {
            $productIds[] = $product->id;
        }

        // Load variants grouped by product_id with necessary relationships
        $productVariants = ProductVariant::with(['product', 'size', 'color'])
            ->whereIn('product_id', $productIds)
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products', 'productVariants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:products',
            'description' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'description_templates' => 'nullable|array',
            'description_templates.*' => 'nullable|exists:description_templates,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except(['colors', 'sizes', 'description_templates']);
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->has('is_active') ? true : false;
            $data['is_featured'] = $request->has('is_featured') ? true : false;

            // Generate SKU if not provided
            if (empty($data['sku'])) {
                $category = Category::find($request->category_id);
                $categoryPrefix = strtoupper(substr($category->name, 0, 3));
                $productPrefix = strtoupper(substr(Str::slug($request->name), 0, 3));
                $timestamp = now()->format('ymd');
                $randomStr = strtoupper(Str::random(3));
                $data['sku'] = $categoryPrefix . '-' . $productPrefix . '-' . $timestamp . '-' . $randomStr;
            }

            // Handle single image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            // Handle multiple images
            if ($request->hasFile('additional_images')) {
                $additionalImages = [];
                foreach ($request->file('additional_images') as $image) {
                    $additionalImages[] = $image->store('products', 'public');
                }
                $data['additional_images'] = $additionalImages;
            }

            // Create product
            $product = Product::create($data);

            // Process colors
            if ($request->has('colors')) {
                $colorIds = [];
                $colorNames = $request->input('colors', []);

                foreach ($colorNames as $index => $colorName) {
                    if (is_numeric($colorName)) {
                        // This is an existing color ID
                        $colorIds[] = $colorName;
                    } else {
                        // This is a new color name, create it
                        $hexCode = null;

                        // Check if color_hex_codes exists and has a corresponding value
                        if ($request->has('color_hex_codes') && isset($request->color_hex_codes[$index])) {
                            $hexCode = $request->color_hex_codes[$index];
                        }

                        $color = Color::firstOrCreate(
                            ['name' => $colorName],
                            [
                                'slug' => Str::slug($colorName),
                                'hex_code' => $hexCode,
                                'is_active' => true
                            ]
                        );
                        $colorIds[] = $color->id;
                    }
                }

                $product->colors()->sync($colorIds);
            }

            // Process sizes
            if ($request->has('sizes')) {
                $sizeIds = [];
                $sizeNames = $request->input('sizes', []);

                foreach ($sizeNames as $sizeName) {
                    if (is_numeric($sizeName)) {
                        // This is an existing size ID
                        $sizeIds[] = $sizeName;
                    } else {
                        // This is a new size name, create it
                        $size = Size::firstOrCreate(
                            ['name' => $sizeName],
                            ['slug' => Str::slug($sizeName)]
                        );
                        $sizeIds[] = $size->id;
                    }
                }

                $product->sizes()->sync($sizeIds);
            }

            // Process description templates
            if ($request->has('description_templates')) {
                $templateIds = array_filter($request->description_templates, function($value) {
                    return !empty($value);
                });

                if (!empty($templateIds)) {
                    $product->descriptionTemplates()->sync($templateIds);
                }
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Load all the necessary relationships for displaying product variants
        $product->load([
            'category',
            'colors',
            'sizes',
            'variants.color',
            'variants.size'
        ]);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->get();
        $product->load(['colors', 'sizes', 'variants']);

        return view('admin.products.edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'colors' => 'nullable|array',
            'colors.*' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'description_templates' => 'nullable|array',
            'description_templates.*' => 'nullable|exists:description_templates,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update product
            $updateData = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'sku' => $request->sku,
                'description' => $request->description,
                'is_featured' => $request->is_featured ? true : false,
                'is_active' => $request->is_active ? true : false,
            ];

            $product->update($updateData);

            // Update warna (colors)
            $colorIds = [];
            $newColors = [];

            if ($request->has('colors')) {
                foreach ($request->colors as $colorInput) {
                    if (is_numeric($colorInput)) {
                        // Warna dari database
                        $colorIds[] = $colorInput;
                    } else {
                        // Warna custom
                        $newColor = null;
                        $hexCode = null;

                        // Cari indeks untuk warna ini dalam array color_hex_codes
                        $index = array_search($colorInput, $request->colors);
                        if ($index !== false && isset($request->color_hex_codes[$index])) {
                            $hexCode = $request->color_hex_codes[$index];
                        }

                        // Cek apakah warna sudah ada di database
                        $existingColor = Color::where('name', $colorInput)->first();
                        if ($existingColor) {
                            $colorIds[] = $existingColor->id;
                        } else {
                            // Tambahkan warna baru
                            $newColor = Color::create([
                                'name' => $colorInput,
                                'hex_code' => $hexCode,
                                'is_active' => true
                            ]);
                            $colorIds[] = $newColor->id;
                            $newColors[] = $newColor;
                        }
                    }
                }
            }

            // Sync warna dengan produk
            $product->colors()->sync($colorIds);

            // Update ukuran (sizes)
            $sizeIds = [];
            $newSizes = [];

            if ($request->has('sizes')) {
                foreach ($request->sizes as $sizeInput) {
                    if (is_numeric($sizeInput)) {
                        // Ukuran dari database
                        $sizeIds[] = $sizeInput;
                    } else {
                        // Ukuran custom
                        // Cek apakah ukuran sudah ada di database
                        $existingSize = Size::where('name', $sizeInput)->first();
                        if ($existingSize) {
                            $sizeIds[] = $existingSize->id;
                        } else {
                            // Tambahkan ukuran baru
                            $newSize = Size::create([
                                'name' => $sizeInput,
                                'is_active' => true
                            ]);
                            $sizeIds[] = $newSize->id;
                            $newSizes[] = $newSize;
                        }
                    }
                }
            }

            // Sync ukuran dengan produk
            $product->sizes()->sync($sizeIds);

            // Process description templates
            if ($request->has('description_templates')) {
                $templateIds = array_filter($request->description_templates, function($value) {
                    return !empty($value);
                });

                if (!empty($templateIds)) {
                    $product->descriptionTemplates()->sync($templateIds);
                } else {
                    $product->descriptionTemplates()->detach();
                }
            } else {
                $product->descriptionTemplates()->detach();
            }

            // Update gambar utama jika diunggah
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }

                // Simpan gambar baru
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
                $product->save();
            }

            // Update gambar tambahan jika diunggah
            if ($request->hasFile('additional_images')) {
                // Hapus gambar lama jika ada
                if ($product->additional_images) {
                    foreach ($product->additional_images as $image) {
                        Storage::delete('public/' . $image);
                    }
                }

                $additionalImages = [];
                foreach ($request->file('additional_images') as $image) {
                    $imagePath = $image->store('products', 'public');
                    $additionalImages[] = $imagePath;
                }

                $product->additional_images = $additionalImages;
                $product->save();
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete additional images if exists
        if ($product->additional_images) {
            foreach ($product->additional_images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        // Detach relationships
        $product->colors()->detach();
        $product->sizes()->detach();
        $product->variants()->delete();

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }
}
