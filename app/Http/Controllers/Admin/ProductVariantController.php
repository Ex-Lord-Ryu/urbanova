<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This is handled in the ProductController's index method
        return redirect()->route('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->get();

        // Check if product_id is provided in the query string
        $selectedProductId = request('product_id');
        $selectedProduct = null;

        if ($selectedProductId) {
            $selectedProduct = Product::find($selectedProductId);
        }

        return view('admin.product-variants.create', compact('products', 'colors', 'sizes', 'selectedProduct'));
    }

    /**
     * Show the unified form for creating product variants.
     */
    public function createUnified()
    {
        $products = Product::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->get();

        return view('admin.product-variants.create-unified', compact('products', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variants' => 'required|array',
            'variants.*.size_id' => 'required|exists:sizes,id',
            'variants.*.color_id' => 'nullable|exists:colors,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $productId = $request->product_id;

            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $productId,
                    'size_id' => $variant['size_id'],
                    'color_id' => $variant['color_id'] ?? null,
                    'price' => (float)$variant['price'],
                    'stock' => (int)$variant['stock']
                ]);
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Varian produk berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan varian: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating variants for a specific size.
     */
    public function createSizeVariant($productId, $sizeId = null)
    {
        $product = Product::findOrFail($productId);
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->get();
        $selectedSize = $sizeId ? Size::findOrFail($sizeId) : null;
        $products = Product::where('is_active', true)->get();

        // Use the unified form with the product pre-selected
        return view('admin.product-variants.create-unified', compact('product', 'colors', 'sizes', 'selectedSize', 'products'));
    }

    /**
     * Store a newly created variants for a specific size.
     */
    public function storeSizeVariant(Request $request, $productId)
    {
        $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'price' => 'required|numeric|min:0',
            'colors' => 'array',
            'colors.*.color_id' => 'required|exists:colors,id',
            'colors.*.stock' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($productId);
            $sizeId = $request->size_id;
            $price = (float)$request->price;

            if (empty($request->colors)) {
                // Create a variant without color
                ProductVariant::create([
                    'product_id' => $productId,
                    'size_id' => $sizeId,
                    'color_id' => null,
                    'price' => $price,
                    'stock' => (int)$request->stock
                ]);
            } else {
                // Create variants for each color
                foreach ($request->colors as $colorData) {
                    ProductVariant::create([
                        'product_id' => $productId,
                        'size_id' => $sizeId,
                        'color_id' => $colorData['color_id'],
                        'price' => $price,
                        'stock' => (int)$colorData['stock']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product variants for size created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating product variants: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductVariant $productVariant)
    {
        $products = Product::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $sizes = Size::where('is_active', true)->get();

        // Get the product for breadcrumb/navigation context
        $product = $productVariant->product;

        return view('admin.product-variants.edit', compact('productVariant', 'products', 'colors', 'sizes', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductVariant $productVariant)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $productVariant->update([
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'color_id' => $request->color_id,
                'price' => (float)$request->price,
                'stock' => (int)$request->stock
            ]);

            DB::commit();

            // Redirect back to the product detail page if available
            if ($request->has('from_product') && $request->from_product) {
                return redirect()->route('products.show', $request->product_id)
                    ->with('success', 'Varian produk berhasil diperbarui');
            }

            return redirect()->route('products.index')
                ->with('success', 'Varian produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui varian: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $productVariant)
    {
        try {
            // Store the product ID before deleting the variant
            $productId = $productVariant->product_id;

            $productVariant->delete();

            // Check if the request wants to return to the product detail
            if (request()->has('from_product')) {
                return redirect()->route('products.show', $productId)
                    ->with('success', 'Varian produk berhasil dihapus');
            }

            return redirect()->route('products.index')
                ->with('success', 'Varian produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Terjadi kesalahan saat menghapus varian: ' . $e->getMessage());
        }
    }
}
