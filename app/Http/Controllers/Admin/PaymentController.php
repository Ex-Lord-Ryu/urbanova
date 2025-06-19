<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Order::whereNotNull('payment_proof')
            ->with('user')
            ->latest();

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

        $payments = $query->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Order $order)
    {
        if (empty($order->payment_proof)) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'This order does not have a payment proof attached.');
        }

        $order->load(['items.product', 'items.variant', 'user']);
        return view('admin.payments.show', compact('order'));
    }

    /**
     * Verify a payment as valid.
     */
    public function verify(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing'
        ]);

        return redirect()->route('admin.payments.show', $order)
            ->with('success', 'Payment has been verified and order status updated to processing.');
    }

    /**
     * Reject a payment as invalid.
     */
    public function reject(Order $order)
    {
        $order->update([
            'payment_status' => 'failed'
        ]);

        return redirect()->route('admin.payments.show', $order)
            ->with('success', 'Payment has been rejected.');
    }

    /**
     * Download the payment proof.
     *
     * @param Order $order
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function download(Order $order)
    {
        if (empty($order->payment_proof)) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'This order does not have a payment proof attached.');
        }

        $filePath = $order->payment_proof;
        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->route('admin.payments.show', $order)
                ->with('error', 'Payment proof file not found.');
        }

        $fullPath = Storage::disk('public')->path($filePath);
        $filename = 'payment_proof_' . $order->order_number . '.' . pathinfo($order->payment_proof, PATHINFO_EXTENSION);

        return response()->download($fullPath, $filename);
    }
}
