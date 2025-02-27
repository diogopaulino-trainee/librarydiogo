<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    /**
     * Regista a ação no log
     *
     * @param string $module
     * @param string $action
     * @param string $description
     * @param int|null $objectId
     * @return void
     */
    public function logAction($module, $changeDescription, $details, $objectId = null)
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $objectId = $objectId ?: $userId;  
        } else {
            $userId = 0;
            $objectId = $objectId ?: 0;
        }

        Log::create([
            'user_id' => $userId,
            'module' => $module,
            'object_id' => $objectId,
            'change_description' => $changeDescription,
            'details' => $details,
            'ip_address' => Request::ip(),
            'browser' => Request::header('User-Agent'),
        ]);
    }
}
