<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display the shop page with all products.
     */
    public function index(Request $request)
    {
        // Base query for active products
        $query = Product::where('is_active', true);

        // Filter by categories if set
        if ($request->has('category') && !empty($request->category)) {
            $query->whereIn('category_id', $request->category);
        }

        // Filter by price range if set
        if ($request->has('price') && $request->price !== null) {
            // Calculate min and max prices for the filter
            $priceRange = Product::where('is_active', true)
                               ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                               ->first();

            if ($priceRange->min_price !== null && $priceRange->max_price !== null) {
                $minPrice = floor($priceRange->min_price);
                $maxPrice = ceil($priceRange->max_price);

                // Create 3 price ranges
                $rangeSize = ceil(($maxPrice - $minPrice) / 3);

                if ($rangeSize > 0) {
                    $ranges = [
                        ['min' => $minPrice, 'max' => $minPrice + $rangeSize],
                        ['min' => $minPrice + $rangeSize + 1, 'max' => $minPrice + (2 * $rangeSize)],
                        ['min' => $minPrice + (2 * $rangeSize) + 1, 'max' => $maxPrice]
                    ];

                    if (isset($ranges[$request->price])) {
                        $selectedRange = $ranges[$request->price];
                        $query->whereBetween('price', [$selectedRange['min'], $selectedRange['max']]);
                    }
                }
            }
        }

        // Get products with pagination
        $products = $query->latest()->paginate(8)->withQueryString();

        // Calculate min and max prices for the filter
        $priceRange = Product::where('is_active', true)
                           ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                           ->first();

        // Create price ranges for filter
        $priceRanges = [];
        if ($priceRange->min_price !== null && $priceRange->max_price !== null) {
            $minPrice = floor($priceRange->min_price);
            $maxPrice = ceil($priceRange->max_price);

            // Create 3 price ranges
            $rangeSize = ceil(($maxPrice - $minPrice) / 3);

            if ($rangeSize > 0) {
                $priceRanges = [
                    ['min' => $minPrice, 'max' => $minPrice + $rangeSize, 'label' => 'Rp ' . number_format($minPrice, 0, ',', '.') . ' - Rp ' . number_format($minPrice + $rangeSize, 0, ',', '.')],
                    ['min' => $minPrice + $rangeSize + 1, 'max' => $minPrice + (2 * $rangeSize), 'label' => 'Rp ' . number_format($minPrice + $rangeSize + 1, 0, ',', '.') . ' - Rp ' . number_format($minPrice + (2 * $rangeSize), 0, ',', '.')],
                    ['min' => $minPrice + (2 * $rangeSize) + 1, 'max' => $maxPrice, 'label' => 'Rp ' . number_format($minPrice + (2 * $rangeSize) + 1, 0, ',', '.') . ' - Rp ' . number_format($maxPrice, 0, ',', '.')]
                ];
            } else {
                $priceRanges = [
                    ['min' => $minPrice, 'max' => $maxPrice, 'label' => 'Rp ' . number_format($minPrice, 0, ',', '.')]
                ];
            }
        }

        $categories = Category::where('is_active', true)->get();

        // Selected categories for maintaining filter state
        $selectedCategories = $request->category ?? [];

        // Selected price range
        $selectedPrice = $request->price;

        // Get setting for showing featured badge
        $showFeaturedBadge = Setting::get('show_featured_badge', true);

        return view('shop.shop', compact(
            'products',
            'categories',
            'priceRanges',
            'showFeaturedBadge',
            'selectedCategories',
            'selectedPrice'
        ));
    }
}
