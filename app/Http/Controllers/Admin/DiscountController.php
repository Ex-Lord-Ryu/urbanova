<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountController extends Controller
{
    /**
     * Display a listing of products with discounts.
     */
    public function index()
    {
        // Log active discount count
        $discountCount = Discount::where('percentage', '>', 0)->count();
        \Illuminate\Support\Facades\Log::info('Discount count in database', [
            'total_discounts' => $discountCount
        ]);

        // Check if any products have discounts
        $productsWithDiscounts = Product::whereHas('discounts', function ($query) {
                $query->where('percentage', '>', 0);
            })->count();

        \Illuminate\Support\Facades\Log::info('Products with discounts', [
            'count' => $productsWithDiscounts
        ]);

        // Modified query to show ALL products with discounts, regardless of date
        $products = Product::whereHas('discounts', function ($query) {
                $query->where('percentage', '>', 0);
            })
            ->with(['category', 'discounts' => function ($query) {
                $query->orderBy('percentage', 'desc');
            }])
            ->paginate(10);

        // Log the retrieved products
        \Illuminate\Support\Facades\Log::info('Retrieved products for display', [
            'count' => $products->count()
        ]);

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
            // Debug the input data
            \Illuminate\Support\Facades\Log::info('Discount creation attempt', [
                'products' => $request->products,
                'discount_percentage' => $request->discount_percentage,
                'discount_start_date' => $request->discount_start_date,
                'discount_end_date' => $request->discount_end_date
            ]);

            // Check if start date is today, move it to tomorrow
            $startDate = Discount::advanceStartDateIfToday($request->discount_start_date);

            // Set end date to one day after start date if empty or same as start date
            $endDate = $request->discount_end_date;

            if (empty($endDate) || (!empty($startDate) && $endDate == $startDate)) {
                $endDate = Discount::setEndDateToNextDay($startDate);
            } else if (!empty($startDate) && !empty($endDate)) {
                // If end date is earlier than the (possibly advanced) start date, adjust it
                $startDateObj = Carbon::parse($startDate);
                $endDateObj = Carbon::parse($endDate);

                if ($endDateObj->lessThanOrEqualTo($startDateObj)) {
                    $endDate = $startDateObj->copy()->addDay()->format('Y-m-d');
                }
            }

            \Illuminate\Support\Facades\Log::info('Processed discount dates', [
                'processed_start_date' => $startDate,
                'processed_end_date' => $endDate
            ]);

            $createdDiscounts = [];

            foreach ($request->products as $productId) {
                // Check if discount already exists
                $discount = Discount::where('product_id', $productId)->first();

                if ($discount) {
                    // Update existing discount
                    $discount->update([
                        'percentage' => $request->discount_percentage,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                    $createdDiscounts[] = $discount->fresh();
                } else {
                    // Create new discount
                    $newDiscount = Discount::create([
                        'product_id' => $productId,
                        'percentage' => $request->discount_percentage,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                    $createdDiscounts[] = $newDiscount;
                }
            }

            // Log the result to debug
            \Illuminate\Support\Facades\Log::info('Created discounts', [
                'count' => count($createdDiscounts),
                'discounts' => $createdDiscounts
            ]);

            DB::commit();
            return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error creating discount', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified discount.
     */
    public function edit($id)
    {
        $product = Product::with('discounts')->findOrFail($id);
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
            // Check if start date is today, move it to tomorrow
            $startDate = Discount::advanceStartDateIfToday($request->discount_start_date);

            // Set end date to one day after start date if empty or same as start date
            $endDate = $request->discount_end_date;

            if (empty($endDate) || (!empty($startDate) && $endDate == $startDate)) {
                $endDate = Discount::setEndDateToNextDay($startDate);
            } else if (!empty($startDate) && !empty($endDate)) {
                // If end date is earlier than the (possibly advanced) start date, adjust it
                $startDateObj = Carbon::parse($startDate);
                $endDateObj = Carbon::parse($endDate);

                if ($endDateObj->lessThanOrEqualTo($startDateObj)) {
                    $endDate = $startDateObj->copy()->addDay()->format('Y-m-d');
                }
            }

            $discount = Discount::where('product_id', $id)->first();

            if ($discount) {
                // Update existing discount
                $discount->update([
                    'percentage' => $request->discount_percentage,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
            } else {
                // Create new discount
                Discount::create([
                    'product_id' => $id,
                    'percentage' => $request->discount_percentage,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
            }

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
            // Delete all discounts for this product
            Discount::where('product_id', $id)->delete();

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
            // Check if start date is today, move it to tomorrow
            $startDate = Discount::advanceStartDateIfToday($request->discount_start_date);

            // Set end date to one day after start date if empty or same as start date
            $endDate = $request->discount_end_date;

            if (empty($endDate) || (!empty($startDate) && $endDate == $startDate)) {
                $endDate = Discount::setEndDateToNextDay($startDate);
            } else if (!empty($startDate) && !empty($endDate)) {
                // If end date is earlier than the (possibly advanced) start date, adjust it
                $startDateObj = Carbon::parse($startDate);
                $endDateObj = Carbon::parse($endDate);

                if ($endDateObj->lessThanOrEqualTo($startDateObj)) {
                    $endDate = $startDateObj->copy()->addDay()->format('Y-m-d');
                }
            }

            $products = Product::where('category_id', $request->category_id)
                ->where('is_active', true)
                ->get();

            foreach ($products as $product) {
                $discount = Discount::where('product_id', $product->id)->first();

                if ($discount) {
                    // Update existing discount
                    $discount->update([
                        'percentage' => $request->discount_percentage,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                } else {
                    // Create new discount
                    Discount::create([
                        'product_id' => $product->id,
                        'percentage' => $request->discount_percentage,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);
                }
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
