<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmailJob;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReminderEmailsCommand extends Command
{
    protected $signature = 'orders:send-reminders';
    protected $description = 'Send reminder emails for orders pending for more than 1 hour';

    public function handle()
    {
        Log::info("[SendReminderEmailsCommand] Searching for orders pending for more than 1 hour...");

        $orders = Order::where('status', 'pending')
                       ->where('created_at', '<', now()->subHour())
                       ->get();

        if ($orders->isEmpty()) {
            Log::info("[SendReminderEmailsCommand] No pending orders for more than 1 hour.");
            return;
        }

        foreach ($orders as $order) {
            dispatch(new SendReminderEmailJob($order));
            Log::info("[SendReminderEmailsCommand] Dispatched job for order #{$order->id} ({$order->user->email})");
        }

        $this->info("Reminder emails queued successfully!");
    }
}
