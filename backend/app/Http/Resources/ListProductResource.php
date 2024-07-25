<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'image_url' => $this->image ? asset('/api/products/image/' . $this->id) : null,
            'category_id' => $this->category_id,
            'category_name' => $this->category ? $this->category->category_name : null,
            'creator' => $this->user ? $this->user->name : null,
        ];
    }
}
