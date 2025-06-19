<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrackingNumberAdded extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Order
     */
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('landing.orders.detail', $this->order->order_number));
        $trackingUrl = "https://cekresi.com/?noresi=" . $this->order->tracking_number;

        return (new MailMessage)
                    ->subject('Pesanan Anda Telah Dikirim - No. Resi Tersedia')
                    ->greeting('Halo ' . $this->order->full_name . ',')
                    ->line('Pesanan Anda #' . $this->order->order_number . ' telah dikirim!')
                    ->line('Kurir: **' . $this->order->courier_name . '**')
                    ->line('Nomor resi pengiriman Anda adalah: **' . $this->order->tracking_number . '**')
                    ->action('Lacak Pengiriman', $trackingUrl)
                    ->line('Anda juga dapat melihat detail pesanan dengan mengklik tombol di bawah ini:')
                    ->action('Lihat Detail Pesanan', $url)
                    ->line('Terima kasih telah berbelanja bersama kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'tracking_number' => $this->order->tracking_number,
            'courier_name' => $this->order->courier_name,
        ];
    }
}
