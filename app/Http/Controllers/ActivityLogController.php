<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // GET /api/activity-logs
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->module) {
            $query->where('module', $request->module);
        }
        if ($request->action) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate($request->per_page ?? 20);

        return response()->json($logs);
    }

    // DELETE /api/activity-logs/clear — clear all logs
    public function clear()
    {
        ActivityLog::truncate();
        return response()->json(['message' => 'Activity logs cleared.']);
    }
}