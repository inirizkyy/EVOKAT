<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('admin.chat.index');
    }

    public function getSessions(Request $request)
    {
        $status = $request->query('status', 'aktif');
        $search = $request->query('search', '');

        $query = ChatSession::withCount(['messages as unread_count' => function ($query) {
            $query->where('sender', 'pemohon')->where('is_read', false);
        }])
        ->with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])
        ->where('status', $status);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sessions = $query->orderBy('last_message_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'sessions' => $sessions
        ]);
    }

    public function getMessages($id)
    {
        $session = ChatSession::findOrFail($id);

        // Mark pemohon messages as read
        ChatMessage::where('session_id', $session->id)
            ->where('sender', 'pemohon')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $session->messages()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'status' => $session->status,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $session = ChatSession::findOrFail($id);

        if ($session->status === 'selesai') {
            return response()->json(['success' => false, 'message' => 'Percakapan ini telah selesai.'], 403);
        }

        $chat = ChatMessage::create([
            'session_id' => $session->id,
            'sender' => 'admin',
            'message' => htmlspecialchars($request->message),
            'is_read' => true, // admin messages are considered read by admin
        ]);

        $session->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'chat' => $chat
        ]);
    }

    public function markAsDone($id)
    {
        $session = ChatSession::findOrFail($id);
        $session->update(['status' => 'selesai']);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $session = ChatSession::findOrFail($id);
        // Cascade delete if needed, but since it's soft delete, we'll let soft delete handle session.
        // Wait, soft deleting session won't automatically soft delete messages unless we have that setup.
        // Actually, soft deleting the session hides it.
        $session->messages()->delete();
        $session->delete();

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = ChatMessage::where('sender', 'pemohon')
            ->where('is_read', false)
            ->whereHas('session', function($q) {
                $q->where('status', 'aktif');
            })
            ->count();

        return response()->json(['success' => true, 'count' => $count]);
    }
}
