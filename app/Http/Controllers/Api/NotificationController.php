<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->user_id)
            ->orderBy('notification_id', 'desc')
            ->get()
            ->map(fn($n) => [
                'id'      => $n->notification_id,
                'message' => $n->message,
                'is_read' => (bool) $n->is_read,
            ]);

        return response()->json($notifications);
    }

    public function markRead(Request $request, $id)
    {
        $notification = Notification::where('user_id', $request->user()->user_id)
            ->findOrFail($id);

        $notification->update(['is_read' => true]);

        return response()->json(['message' => 'Marked as read']);
    }
}