<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ChartMessages;
use App\Models\User;

class ChatRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // Check if the authenticated user is either the sender or the receiver
        if (auth()->id() == $this->user1_id || auth()->id() == $this->user2_id) {
            // Base array with common attributes
            $data = [
                "user_id" => auth()->id(),
                "id" => $this->id,
                "sender_id" => $this->user1_id,
                "receiver_id" => $this->user2_id,
                "sender" => User::select('id', 'name', 'email', 'profile')->where("id", $this->user1_id)->first(),
                "receiver" => User::select('id', 'name', 'email', 'profile')->where("id", $this->user2_id)->first(),
            ];
    
            // Add 'newmessage' key if the condition is met
            $data["newmessage"] = ChartMessages::where('chat_room_id', $this->id)->orderBy('created_at', 'desc')->first();
    
            return $data;
        }
    
        // Return null if the user is not the sender or the receiver
        return null;
    }
    
}
