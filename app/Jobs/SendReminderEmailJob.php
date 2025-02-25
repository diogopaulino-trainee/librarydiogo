<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendReminderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        Log::info("📩 [SendReminderEmailJob] A verificar a encomenda #{$this->order->id}");

        // Garantir que a encomenda ainda está pendente antes de enviar
        if ($this->order->status !== 'pending') {
            Log::warning("[SendReminderEmailJob] A encomenda #{$this->order->id} já não está pendente. O email não será enviado.");
            return;
        }

        // Garantir que a encomenda tem um utilizador associado antes de enviar
        if (!$this->order->user) {
            Log::error("[SendReminderEmailJob] A encomenda #{$this->order->id} não tem um utilizador associado. O email não será enviado.");
            return;
        }

        // Enviar o email de lembrete
        Mail::to($this->order->user->email)->send(new OrderReminderMail($this->order));

        Log::info("[SendReminderEmailJob] Email enviado com sucesso para {$this->order->user->email} sobre a encomenda #{$this->order->id}");
    }
}
