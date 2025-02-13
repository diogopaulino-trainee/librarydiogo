<?php

namespace App\Jobs;

use App\Mail\ReminderEmail;
use App\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        Mail::to($this->request->user_email_at_request)
            ->send(new ReminderEmail($this->request));
    }
}
