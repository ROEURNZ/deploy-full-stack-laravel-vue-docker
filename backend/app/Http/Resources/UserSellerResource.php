<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Product;

class UserSellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $products = Product::select('id','name','price','image','user_id')
                                  ->where('id', $this->product_id)
                                  ->orderByDesc('created_at')
                                  ->get();
        $owner_id = $products->first()->user_id;
        if ($owner_id == auth()->id()){
            return [
                'id' => $this->id,
                'quantity' => $this->qty,
                'status' => $this->status,
                'buyer'=> User::select('id', 'name', 'email', 'profile')->where('id', $this->order_id)->first(),
                'products' => $products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price'=>$product->price,
                            'image' => $product->image,
                        ];
                    }),
                'created_at' => $this->created_at->format('d-m-y'),
                'updated_at' => $this->updated_at->format('d-m-y'),
                // 'created_at' => $this->created_at->diffForHumans(),
                // 'updated_at' => $this->updated_at->diffForHumans(),
            ];
        }else{
            return [
                'error' => 'You are not the owner of this product'
            ];
        }
    }

}
