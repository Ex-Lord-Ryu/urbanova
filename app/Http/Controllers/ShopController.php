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
    public function index()
    {
        // Get all active products (including featured)
        $products = Product::where('is_active', true)
                          ->latest()
                          ->paginate(8);

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

        // Get setting for showing featured badge
        $showFeaturedBadge = Setting::get('show_featured_badge', true);

        return view('shop.shop', compact('products', 'categories', 'priceRanges', 'showFeaturedBadge'));
    }
}
