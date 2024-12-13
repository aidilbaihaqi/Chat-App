<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::where('from_user_id', auth()->id)
                    ->orWhere('to_user_id', auth()->id)->get();
        return view('chat.index', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'from_user_id' => auth()->id,
            'to_user_id' => $request->to_user_id,
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message))->toOthers();
        return response()->json(['message'=> $message]);
    }
}
