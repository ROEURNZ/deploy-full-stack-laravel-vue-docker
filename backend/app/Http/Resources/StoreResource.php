<?php

namespace App\Http\Resources;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'description' => $this->description,
            'image' => $this->image,
            'user' => $this->user,
            "owner"=> Store::where("user_id", $this->id)->get(),
            'image_url' => $this->image ? asset('/api/stores/image/' . $this->image) : null,
        ];
    }
}
