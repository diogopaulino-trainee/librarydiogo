<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $name;
    public $orderId;
    public $orderTotal;
    public $checkoutUrl;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->name = $order->user->name;
        $this->orderId = $order->id;
        $this->orderTotal = $order->total_price;
        $this->checkoutUrl = route('orders.payment', $order->id);
    }

    public function build()
    {
        return $this->subject('Reminder: Finish your order')
                    ->view('emails.order_reminder')
                    ->with([
                        'name' => $this->name,
                        'orderId' => $this->orderId,
                        'orderTotal' => $this->orderTotal,
                        'checkoutUrl' => $this->checkoutUrl,
                    ]);
    }
}
