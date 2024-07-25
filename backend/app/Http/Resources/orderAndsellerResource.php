<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;
use App\Models\User;

class orderAndsellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = Product::select('id','name','price','image','user_id')
                                  ->where('id', $this->product_id)
                                  ->orderByDesc('created_at')
                                  ->get();
        return [
            'id' => $this->id,
            'quantity' => $this->qty,
            'status' => $this->status,
            'products' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price'=>$product->price,
                        'image' => $product->image,
                        'owner' => User::select('id', 'name', 'email')
                            ->where('id', $product->user_id)
                            ->first(),

                        'buyer'=> User::select('id', 'name', 'email')
                            ->where('id', $this->order_id)
                            ->first(),
                    ];
                }),
            'created_at' => $this->created_at->format('d-m-y'),
            'updated_at' => $this->updated_at->format('d-m-y'),
            // 'created_at' => $this->created_at->diffForHumans(),
            // 'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
