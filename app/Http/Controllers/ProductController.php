<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the product details page for public view
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'colors', 'sizes', 'variants.size', 'variants.color'])
            ->firstOrFail();

        // Get related products based on category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        // Fetch all colors from database to ensure we have complete color data
        $allColors = Color::all()->keyBy('id');

        // Organize variant data by size and color
        $variantsBySizeAndColor = [];
        $debugColors = []; // For debugging colors

        foreach ($product->variants as $variant) {
            // Skip variants without size
            if (!$variant->size) {
                continue;
            }

            $sizeName = $variant->size->name;

            if (!isset($variantsBySizeAndColor[$sizeName])) {
                $variantsBySizeAndColor[$sizeName] = [];
            }

            // If variant has a color, add it to the size's colors array
            if ($variant->color_id) {
                // Get color data from our complete colors collection
                $color = $allColors[$variant->color_id] ?? null;

                if ($color) {
                    // For debugging
                    if (!isset($debugColors[$color->id])) {
                        $debugColors[$color->id] = [
                            'id' => $color->id,
                            'name' => $color->name,
                            'code' => $color->code ?: 'null',  // Default to 'null' if empty
                        ];
                    }

                    // Use a default color code if missing
                    $colorCode = $color->code;
                    if (empty($colorCode)) {
                        // Assign default colors based on name
                        $defaultColors = [
                            'HIJAU' => '00FF00',
                            'MERAH' => 'FF0000',
                            'BIRU' => '0000FF',
                            'KUNING' => 'FFFF00',
                            'HITAM' => '000000',
                            'PUTIH' => 'FFFFFF',
                        ];

                        $colorCode = $defaultColors[strtoupper($color->name)] ?? '888888'; // Default gray if name not matched
                    }

                    // Remove # prefix if present
                    if (strpos($colorCode, '#') === 0) {
                        $colorCode = substr($colorCode, 1);
                    }

                    $variantsBySizeAndColor[$sizeName][] = [
                        'color_id' => $color->id,
                        'color_name' => $color->name,
                        'color_code' => $colorCode,
                        'stock' => $variant->stock,
                        'variant_id' => $variant->id
                    ];
                }
            } else {
                // For variants without color, add a special "no color" entry if not already added
                $hasNoColorVariant = false;
                foreach ($variantsBySizeAndColor[$sizeName] as $existingColorVariant) {
                    if (isset($existingColorVariant['no_color']) && $existingColorVariant['no_color'] === true) {
                        $hasNoColorVariant = true;
                        break;
                    }
                }

                if (!$hasNoColorVariant) {
                    $variantsBySizeAndColor[$sizeName][] = [
                        'no_color' => true,
                        'stock' => $variant->stock,
                        'variant_id' => $variant->id
                    ];
                }
            }
        }

        // Add debug colors to view
        $debugColorsJson = json_encode($debugColors);

        return view('product.show', compact('product', 'relatedProducts', 'variantsBySizeAndColor', 'debugColors'));
    }

    /**
     * Get product variants with size and color info
     */
    public function getVariants(Request $request)
    {
        $productId = $request->input('product_id');

        $product = Product::findOrFail($productId);
        $variants = $product->variants()
            ->with(['size', 'color'])
            ->where('stock', '>', 0)
            ->get();

        return response()->json([
            'variants' => $variants
        ]);
    }
}
