<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Get cart items from session
        $cart = Session::get('cart', []);

        // If cart is empty, redirect back to cart page
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        // Calculate cart total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // Get user data for pre-filling checkout form
        $user = Auth::user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    /**
     * Process the checkout form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        // Validate checkout form data
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|string|in:bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get cart items
        $cart = Session::get('cart', []);

        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        // Generate unique order number
        $orderNumber = 'URB-' . strtoupper(Str::random(8));

        // Create order
        $order = new Order();
        $order->user_id = Auth::id();
        $order->order_number = $orderNumber;
        $order->full_name = $validated['full_name'];
        $order->email = $validated['email'];
        $order->phone = $validated['phone'];
        $order->address = $validated['address'];
        $order->city = $validated['city'];
        $order->postal_code = $validated['postal_code'];
        $order->total_amount = $total;
        $order->payment_method = $validated['payment_method'];
        $order->payment_status = 'pending'; // Default payment status
        $order->order_status = 'pending'; // Default order status
        $order->notes = $validated['notes'];
        $order->save();

        // Save order items
        foreach ($cart as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product_id'] ?? null;
            $orderItem->product_variant_id = $item['variant_id'] ?? null;
            $orderItem->product_name = $item['name'];
            $orderItem->size = $item['size'];
            $orderItem->color_name = $item['color_name'] ?? null;
            $orderItem->color_hex = $item['color_hex'] ?? null;
            $orderItem->price = $item['price'];
            $orderItem->quantity = $item['qty'];
            $orderItem->subtotal = $item['price'] * $item['qty'];
            $orderItem->image = $item['image'];
            $orderItem->save();
        }

        // Clear the cart
        Session::forget('cart');

        return redirect()->route('checkout.success')->with('order_number', $orderNumber);
    }

    /**
     * Display checkout success page.
     *
     * @return \Illuminate\View\View
     */
    public function success()
    {
        $orderNumber = session('order_number');

        // If there's no order number in the session, redirect to home
        if (!$orderNumber) {
            return redirect()->route('landing');
        }

        return view('checkout.success', compact('orderNumber'));
    }
}
