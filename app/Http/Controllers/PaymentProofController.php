<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    /**
     * Display the payment proof upload form.
     *
     * @param  string  $orderNumber
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showUploadForm($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // If payment proof already uploaded and payment status is not pending, redirect to order detail
        if ($order->payment_proof && $order->payment_status !== 'pending') {
            return redirect()->route('landing.orders.detail', $order->order_number)
                ->with('status', 'Bukti pembayaran sudah diunggah sebelumnya.');
        }

        return view('user.payment.upload', compact('order'));
    }

    /**
     * Process the payment proof upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $orderNumber
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadProof(Request $request, $orderNumber)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Handle file upload
        if ($request->hasFile('payment_proof')) {
            // Delete old file if exists
            if ($order->payment_proof) {
                Storage::delete('public/' . $order->payment_proof);
            }

            // Store the new file
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Update order record
            $order->payment_proof = $path;
            $order->payment_status = 'verification'; // Change status to verification
            $order->save();

            return redirect()->route('landing.orders.detail', $order->order_number)
                ->with('status', 'Bukti pembayaran berhasil diunggah. Pembayaran Anda sedang diverifikasi.');
        }

        return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran.');
    }
}
