<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $chatMessage = ChatMessage::create(['message' => $message]);
        broadcast(new MyEvent($chatMessage->message));
        return response()->json(['status' => 'Message sent!', 'message' => $chatMessage]);
    }
}

