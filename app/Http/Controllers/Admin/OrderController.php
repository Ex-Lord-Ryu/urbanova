<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\TrackingNumberAdded;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Filter by order status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number, name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'order_status' => $request->order_status
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status has been updated successfully.');
    }

    /**
     * Update the specified payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,verification',
        ]);

        $order->update([
            'payment_status' => $request->payment_status
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Payment status has been updated successfully.');
    }

    /**
     * Update the specified order's tracking number.
     */
    public function updateTrackingNumber(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'courier_name' => 'required|string|max:50',
        ]);

        // Check if this is a new tracking number or an update
        $isNew = empty($order->tracking_number);

        // Store previous tracking number to check if it changed
        $previousTrackingNumber = $order->tracking_number;

        $order->update([
            'tracking_number' => $request->tracking_number,
            'courier_name' => $request->courier_name
        ]);

        // If order is being shipped, update the status
        if ($isNew && $order->order_status == 'processing') {
            $order->update([
                'order_status' => 'shipped'
            ]);
        }

        // Send notification to the user if tracking number is new or changed
        if ($isNew || $previousTrackingNumber != $request->tracking_number) {
            if ($order->user) {
                $order->user->notify(new TrackingNumberAdded($order));
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Tracking number and courier information has been updated successfully.');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        // Delete associated order items first
        $order->items()->delete();

        // Delete the order
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order has been deleted successfully.');
    }
}
