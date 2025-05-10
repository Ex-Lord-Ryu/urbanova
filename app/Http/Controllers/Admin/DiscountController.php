<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    /**
     * Display a listing of products with discounts.
     */
    public function index()
    {
        $products = Product::whereNotNull('discount_percentage')
            ->orWhere('discount_percentage', '>', 0)
            ->with('category')
            ->orderBy('discount_end_date', 'asc')
            ->paginate(10);

        return view('admin.discounts.index', compact('products'));
    }

    /**
     * Show the form for creating a new discount.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.discounts.create', compact('categories', 'products'));
    }

    /**
     * Store a newly created discount in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->products as $productId) {
                $product = Product::findOrFail($productId);

                $product->update([
                    'discount_percentage' => $request->discount_percentage,
                    'discount_start_date' => $request->discount_start_date,
                    'discount_end_date' => $request->discount_end_date,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();

        return view('admin.discounts.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified discount in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
        ]);

        $product = Product::findOrFail($id);

        try {
            $product->update([
                'discount_percentage' => $request->discount_percentage,
                'discount_start_date' => $request->discount_start_date,
                'discount_end_date' => $request->discount_end_date,
            ]);

            return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the discount from the specified product.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            $product->update([
                'discount_percentage' => 0,
                'discount_start_date' => null,
                'discount_end_date' => null,
            ]);

            return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form for bulk discount creation.
     */
    public function createBulk()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.discounts.create-bulk', compact('categories'));
    }

    /**
     * Apply discounts to products by category.
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
        ]);

        DB::beginTransaction();

        try {
            $products = Product::where('category_id', $request->category_id)
                ->where('is_active', true)
                ->get();

            foreach ($products as $product) {
                $product->update([
                    'discount_percentage' => $request->discount_percentage,
                    'discount_start_date' => $request->discount_start_date,
                    'discount_end_date' => $request->discount_end_date,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.discounts.index')
                ->with('success', 'Diskon massal berhasil diterapkan ke ' . $products->count() . ' produk');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
