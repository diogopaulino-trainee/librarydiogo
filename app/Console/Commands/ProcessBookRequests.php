<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Request;
use App\Jobs\SendReminderEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessBookRequests extends Command
{
    protected $signature = 'requests:process';
    protected $description = 'Send reminder emails for due books and update overdue statuses';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $requestsDueTomorrow = Request::whereDate('expected_return_date', $tomorrow)
            ->where('status', 'borrowed')
            ->get();

        Log::info("Processing reminders for {$requestsDueTomorrow->count()} requests due tomorrow.");

        foreach ($requestsDueTomorrow as $request) {
            dispatch(new SendReminderEmail($request));
            sleep(2);
        }

        $overdueRequests = Request::where('status', 'borrowed')
            ->whereDate('expected_return_date', '<', $today)
            ->update(['status' => 'overdue']);

        Log::info("Updated {$overdueRequests} overdue requests.");

        $this->info("Processed book requests: reminders sent and overdue status updated.");
    }
}
