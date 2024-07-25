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
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "sender_id" => $this->user1_id,
            "receiver_id" => $this->user2_id,
            "receiver" => User::select('id', 'name', 'email', 'profile')->where("id", $this->user2_id)->first(),
            "newmessage" => ChartMessages::where("chat_room_id", $this->id)->latest()
        ];
    }
}
