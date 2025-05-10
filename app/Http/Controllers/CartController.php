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
        ]);

        $product = Product::findOrFail($request->product_id);
        $size = $request->size;
        $qty = $request->qty;

        // Cek stok per size
        $sizeObj = Size::where('name', $size)->first();
        if (!$sizeObj) {
            return response()->json([
                'success' => false,
                'message' => 'Size tidak valid'
            ], 422);
        }

        // Cek apakah size ini tersedia stoknya
        $sizeStock = $product->sizeStocks()->where('size_id', $sizeObj->id)->first();
        if (!$sizeStock || $sizeStock->stock < $qty) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi untuk ukuran ' . $size
            ], 422);
        }

        // Tambahkan ke cart jika stok tersedia
        $cart = session('cart', []);
        $key = $product->id . '-' . $size;

        if (isset($cart[$key])) {
            // Cek jika penambahan melebihi stok yang tersedia
            if ($cart[$key]['qty'] + $qty > $sizeStock->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi untuk ukuran ' . $size
                ], 422);
            }
            $cart[$key]['qty'] += $qty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'size' => $size,
                'price' => $product->price,
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
        $size = $request->size;
        $qty = $request->qty ?? 1;
        // Ambil 6 produk terbaru sebagai recently ordered
        $recent = Product::latest()->take(6)->get();
        return view('components.cart-added-modal', compact('product', 'size', 'qty', 'recent'))->render();
    }
}
