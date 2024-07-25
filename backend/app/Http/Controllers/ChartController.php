<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChartRoom;
use App\Models\ChartMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ChatRoomResource;

class ChartController extends Controller
{
    public function rooms(Request $request)
    {
        $user = Auth::user();
        $rooms = ChartRoom::all();
        return ChatRoomResource::collection($rooms);
    }


    public function messages(Request $request, $roomId)
    {
        return ChartMessages::where('chat_room_id', $roomId)
                          ->with('user')
                        //   ->orderBy('created_at', 'DESC')
                          ->get();
    }

    public function newMessage(Request $request, $roomId)
    {
        $newMessage = new ChartMessages;
        $newMessage->user_id = Auth::id();
        $newMessage->chat_room_id = $roomId;
        $newMessage->message = $request->message;
        $newMessage->save();

        return $newMessage;
    }

    public function createRoom(Request $request)
    {
        Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
        ]);

        $user1 = Auth::id();
        $user2 = $request->user_id;

        $room = ChartRoom::where(function ($query) use ($user1, $user2) {
            $query->where('user1_id', $user1)
                  ->where('user2_id', $user2);
        })->orWhere(function ($query) use ($user1, $user2) {
            $query->where('user1_id', $user2)
                  ->where('user2_id', $user1);
        })->first();

        if (!$room) {
            $room = new ChartRoom;
            $room->user1_id = $user1;
            $room->user2_id = $user2;
            $room->save();
        }

        return ChatRoomResource::make($room);
    }
}

