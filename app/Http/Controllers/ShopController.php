<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
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

        // Filter by sizes if set - only include products with stock for these sizes
        if ($request->has('size') && !empty($request->size)) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('size_id', $request->size)
                  ->where('stock', '>', 0);
            });
        }

        // Filter by colors if set - only include products with stock for these colors
        if ($request->has('color') && !empty($request->color)) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('color_id', $request->color)
                  ->where('stock', '>', 0);
            });
        }

        // Filter by custom price range (min and max price) if set
        if (($request->has('min_price') && $request->min_price !== null) ||
            ($request->has('max_price') && $request->max_price !== null)) {

            $minPrice = $request->min_price ? (int)$request->min_price : 0;
            $maxPrice = $request->max_price ? (int)$request->max_price : PHP_INT_MAX;

            // Use the product IDs with variants in the selected price range
            $productIdsInRange = ProductVariant::whereBetween('price', [$minPrice, $maxPrice])
                ->where('stock', '>', 0)  // Only consider variants that are in stock
                ->distinct()
                ->pluck('product_id');

            $query->whereIn('id', $productIdsInRange);
        }
        // Or filter by predefined price range if set
        elseif ($request->has('price') && $request->price !== null) {
            // Calculate min and max prices for the filter using product variants that are in stock
            $priceRange = ProductVariant::join('products', 'product_variants.product_id', '=', 'products.id')
                                ->where('products.is_active', true)
                                ->where('product_variants.stock', '>', 0)
                                ->selectRaw('MIN(product_variants.price) as min_price, MAX(product_variants.price) as max_price')
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
                        // Use the product IDs with variants in the selected price range
                        $productIdsInRange = ProductVariant::whereBetween('price', [$selectedRange['min'], $selectedRange['max']])
                            ->where('stock', '>', 0)  // Only consider variants that are in stock
                            ->distinct()
                            ->pluck('product_id');
                        $query->whereIn('id', $productIdsInRange);
                    }
                }
            }
        }

        // Filter by discount if set
        if ($request->has('discount') && $request->discount) {
            $query->whereHas('discounts', function ($q) {
                $now = now();
                $q->where('percentage', '>', 0)
                  ->where(function ($subq) use ($now) {
                      $subq->whereNull('start_date')
                           ->orWhere('start_date', '<=', $now);
                  })
                  ->where(function ($subq) use ($now) {
                      $subq->whereNull('end_date')
                           ->orWhere('end_date', '>=', $now);
                  });
            });
        }

        // Get products with pagination
        $products = $query->latest()->paginate(8)->withQueryString();

        // Only get active products with available stock for the price range calculation
        $productsWithStock = Product::where('is_active', true)
            ->whereHas('variants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->pluck('id');

        // Calculate min and max prices for the filter using product variants that are in stock
        $priceRange = ProductVariant::whereIn('product_id', $productsWithStock)
                        ->where('stock', '>', 0)
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

        // Get categories that have products with stock
        $categories = Category::where('is_active', true)
            ->whereHas('products', function($q) {
                $q->where('is_active', true)
                  ->whereHas('variants', function($v) {
                      $v->where('stock', '>', 0);
                  });
            })
            ->get();

        // Get sizes that have products with stock
        $sizes = Size::where('is_active', true)
            ->whereHas('productVariants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->get();

        // Get colors that have products with stock
        $colors = Color::where('is_active', true)
            ->whereHas('productVariants', function($q) {
                $q->where('stock', '>', 0);
            })
            ->get();

        // Selected filters for maintaining filter state
        $selectedCategories = $request->category ?? [];
        $selectedSizes = $request->size ?? [];
        $selectedColors = $request->color ?? [];
        $selectedPrice = $request->price;

        // Get setting for showing featured badge - force direct DB query without cache
        $showFeaturedBadge = Setting::where('key', 'show_featured_badge')->first();
        $showFeaturedBadge = $showFeaturedBadge ? (bool)$showFeaturedBadge->value : false;

        return view('shop.shop', compact(
            'products',
            'categories',
            'sizes',
            'colors',
            'priceRanges',
            'showFeaturedBadge',
            'selectedCategories',
            'selectedSizes',
            'selectedColors',
            'selectedPrice',
            'productsWithStock'
        ));
    }
}
