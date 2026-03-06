<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $users = \App\Models\User::where('role', 'consumer')->get();
            $messages = \App\Models\Message::with(['sender', 'receiver'])
                ->orderBy('created_at', 'asc')
                ->get();
            return view('messages.index', compact('users', 'messages'));
        } else {
            $admin = \App\Models\User::where('role', 'admin')->first();
            $messages = \App\Models\Message::where(function($q) use ($user, $admin) {
                $q->where('sender_id', $user->id)->where('receiver_id', $admin->id);
            })->orWhere(function($q) use ($user, $admin) {
                $q->where('sender_id', $admin->id)->where('receiver_id', $user->id);
            })->orderBy('created_at', 'asc')->get();
            return view('messages.consumer', compact('admin', 'messages'));
        }
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent.');
    }
}
