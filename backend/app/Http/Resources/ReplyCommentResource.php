<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            // 'user_id' => $this->user_id,
            'comment_id' => $this->comment_id,
            'text' => $this->text,  
            'user_id'=> [
                'user' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]
        ];
        
    }
}
