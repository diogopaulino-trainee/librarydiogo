<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Traits\Loggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    use Loggable;

    public function index(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');

        $logs = Log::query();

        if ($request->has('search') && $request->search != '') {
            $logs->where('module', 'like', '%' . $request->search . '%')
                ->orWhere('change_description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort_by') && $request->has('order') && $request->sort_by != '' && $request->order != '') {
            $validSortColumns = ['created_at', 'user_id', 'module', 'object_id', 'change_description'];
            
            if (in_array($request->sort_by, $validSortColumns)) {
                $logs->orderBy($request->sort_by, $request->order);
            }
        }

        $logs = $logs->paginate(10);

        $this->logAction(
            'Log', 
            'Viewing logs list', 
            'User viewed the logs list. Search: ' . ($request->search ?? 'N/A') . ', Sort By: ' . ($request->sort_by ?? 'N/A') . ', Order: ' . ($request->order ?? 'N/A'), 
            Auth::id()
        );

        return view('admin.logs.index', compact('logs'));
    }

    public function show($id)
    {
        abort_if(!auth()->user()->hasRole('Admin'), 403, 'Access denied.');
        $log = Log::findOrFail($id);

        // Logando o acesso ao detalhes do log
        $this->logAction(
            'Log', 
            'Viewing log details', 
            'User viewed the details of log ID: ' . $id, 
            auth()->id()
        );

        return view('admin.logs.show', compact('log'));
    }
}
