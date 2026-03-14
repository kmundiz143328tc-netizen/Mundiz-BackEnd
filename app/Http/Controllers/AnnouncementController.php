<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('author')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
        });

        return response()->json($query->limit(50)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'priority'   => 'in:low,normal,high,urgent',
            'category'   => 'in:general,academic,event,emergency',
            'is_pinned'  => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();

        $announcement = Announcement::create($validated);

        ActivityLog::log('created', 'Announcement', "Posted: \"{$validated['title']}\"");

        return response()->json($announcement->load('author'), 201);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'      => 'sometimes|string|max:255',
            'content'    => 'sometimes|string',
            'priority'   => 'in:low,normal,high,urgent',
            'category'   => 'in:general,academic,event,emergency',
            'is_pinned'  => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update($validated);

        ActivityLog::log('updated', 'Announcement', "Updated: \"{$announcement->title}\"");

        return response()->json($announcement->fresh('author'));
    }

    public function destroy(Announcement $announcement)
    {
        ActivityLog::log('deleted', 'Announcement', "Deleted: \"{$announcement->title}\"");
        $announcement->delete();
        return response()->json(['message' => 'Announcement deleted.']);
    }
}