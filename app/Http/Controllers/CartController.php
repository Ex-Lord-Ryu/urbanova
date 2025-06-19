<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Size;

class CartController extends Controller
{
    // Tampilkan halaman cart
    public function index()
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });
        return view('cart.index', compact('cart', 'total'));
    }

    // Tambah produk ke cart (AJAX)
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'qty' => 'required|integer|min:1',
            'color_id' => 'nullable|exists:colors,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $size = $request->size;
        $qty = $request->qty;
        $colorId = $request->color_id;

        // Cek stok per size
        $sizeObj = Size::where('name', $size)->first();
        if (!$sizeObj) {
            return response()->json([
                'success' => false,
                'message' => 'Size tidak valid'
            ], 422);
        }

        // Cek apakah size dan warna ini tersedia stoknya
        if ($colorId) {
            $variants = $product->getVariantsBySizeAndColor($sizeObj->id, $colorId);
        } else {
            $variants = $product->getVariantsBySize($sizeObj->id);
        }

        $availableStock = $variants->sum('stock');

        if ($availableStock < $qty) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi untuk ukuran ' . $size
            ], 422);
        }

        // Tambahkan ke cart jika stok tersedia
        $cart = session('cart', []);

        // Include color in cart key if provided
        $key = $colorId
            ? $product->id . '-' . $size . '-' . $colorId
            : $product->id . '-' . $size;

        if (isset($cart[$key])) {
            // Cek jika penambahan melebihi stok yang tersedia
            if ($cart[$key]['qty'] + $qty > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi untuk ukuran ' . $size
                ], 422);
            }
            $cart[$key]['qty'] += $qty;
        } else {
            // Get variant price if available
            if ($colorId) {
                $variantPrice = $product->getVariantPriceWithColor($sizeObj->id, $colorId);
            } else {
                $variantPrice = $product->getVariantPrice($sizeObj->id);
            }

            // Get color information if provided
            $colorInfo = null;
            if ($colorId) {
                $colorInfo = \App\Models\Color::find($colorId);
            }

            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'size' => $size,
                'color_id' => $colorId,
                'color_name' => $colorInfo ? $colorInfo->name : null,
                'color_hex' => $colorInfo ? $colorInfo->hex_code : null,
                'price' => $variantPrice,
                'image' => $product->image ?? null,
                'qty' => $qty,
            ];
        }
        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'item' => $cart[$key],
        ]);
    }

    // Update qty item di cart
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'qty' => 'required|integer|min:1',
        ]);
        $cart = session('cart', []);
        if (isset($cart[$request->key])) {
            $cart[$request->key]['qty'] = $request->qty;
            session(['cart' => $cart]);
        }
        return response()->json(['success' => true]);
    }

    // Hapus item dari cart
    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required',
        ]);
        $cart = session('cart', []);
        unset($cart[$request->key]);
        session(['cart' => $cart]);
        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'empty' => count($cart) === 0
        ]);
    }

    // Kosongkan cart
    public function clear()
    {
        session()->forget('cart');

        // Check if this is an AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => 0,
                'empty' => true
            ]);
        }

        return redirect()->route('cart.index');
    }

    // Modal konfirmasi setelah add to cart
    public function addedModal(Request $request)
    {
        $product = Product::find($request->product_id);
        $size = $request->size ?: null;
        $colorId = $request->color_id;
        $qty = $request->qty ?? 1;

        // Get color information if provided
        $color = null;
        if ($colorId) {
            $color = \App\Models\Color::find($colorId);
        }

        // Ambil 6 produk terbaru sebagai recently ordered
        $recent = Product::latest()->take(6)->get();
        return view('components.cart-added-modal', compact('product', 'size', 'color', 'qty', 'recent'))->render();
    }
}
