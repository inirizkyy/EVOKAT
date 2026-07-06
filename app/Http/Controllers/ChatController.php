<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function init(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $session = ChatSession::create([
            'uuid' => (string) Str::uuid(),
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status' => 'aktif',
            'last_message_at' => now(),
        ]);

        // Auto Reply
        ChatMessage::create([
            'session_id' => $session->id,
            'sender' => 'system',
            'message' => "Halo {$request->nama}, terima kasih telah menghubungi layanan EVOKAT.\n\nPesan Anda telah kami terima dan akan segera dibalas oleh Admin pada jam kerja.\n\nJam Operasional:\nSenin–Jumat\n08.00–16.00 WIB\n\nMohon menunggu, terima kasih.",
        ]);

        return response()->json([
            'success' => true,
            'session' => $session
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'message' => 'required|string|max:1000',
        ]);

        $session = ChatSession::where('uuid', $request->uuid)->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesi chat tidak ditemukan.'], 404);
        }

        if ($session->status === 'selesai') {
            return response()->json(['success' => false, 'message' => 'Percakapan ini telah selesai.'], 403);
        }

        $chat = ChatMessage::create([
            'session_id' => $session->id,
            'sender' => 'pemohon',
            'message' => htmlspecialchars($request->message),
        ]);

        $session->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'chat' => $chat
        ]);
    }

    public function fetchMessages(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        $session = ChatSession::where('uuid', $request->uuid)->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesi chat tidak ditemukan.'], 404);
        }

        $messages = $session->messages()->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'status' => $session->status,
            'messages' => $messages
        ]);
    }

    public function markAsDone(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        $session = ChatSession::where('uuid', $request->uuid)->first();

        if ($session) {
            $session->update(['status' => 'selesai']);
        }

        return response()->json(['success' => true]);
    }

    public function adminStatus()
    {
        $isOnline = Auth::check();
        return response()->json([
            'online' => $isOnline,
            'label'  => $isOnline ? 'Online' : 'Offline',
        ]);
    }
}
