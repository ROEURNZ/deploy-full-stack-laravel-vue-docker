<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductUserRating;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $rating = ProductUserRating::select('id','product_id', 'rating')->where('product_id', $this->id)->count();
    
        $ratings = ProductUserRating::select('rating', \DB::raw('count(*) as count'))
        ->where('product_id', $this->id)
        ->groupBy('rating')
        ->get();
    
        // Convert the results to a collection with rating as key and count as value
        $ratingsCount = $ratings->pluck('count', 'rating');
    
        // Calculate the total number of ratings
        $totalRatings = $ratingsCount->sum();
    
        // Calculate percentages for each rating level
        $oneStarPercent = $totalRatings > 0 ? ($ratingsCount->get(1, 0) / $totalRatings) * 100 : 0;
        $twoStarsPercent = $totalRatings > 0 ? ($ratingsCount->get(2, 0) / $totalRatings) * 100 : 0;
        $threeStarsPercent = $totalRatings > 0 ? ($ratingsCount->get(3, 0) / $totalRatings) * 100 : 0;
        $fourStarsPercent = $totalRatings > 0 ? ($ratingsCount->get(4, 0) / $totalRatings) * 100 : 0;
        $fiveStarsPercent = $totalRatings > 0 ? ($ratingsCount->get(5, 0) / $totalRatings) * 100 : 0;
    
        // Determine the top star rating by finding the rating with the highest count
        $topStars = $ratingsCount->isEmpty() ? null : $ratingsCount->sortDesc()->keys()->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'category_id' => $this->category_id,
            'category_name' => $this->category ? $this->category->name : null,
            'creator' => $this->user ? $this->user->name : null,
            'quantity' =>$this->quantity,
            'ratting' => [
                'raters' => $rating,
                'levels'=>[
                    '1-Star' => $oneStarPercent,
                    '2-Stars' => $twoStarsPercent,
                    '3-Stars' => $threeStarsPercent,
                    '4-Stars' => $fourStarsPercent,
                    '5-Stars' => $fiveStarsPercent,
                ],
                'topRating' => $topStars,
            ],
            'created_at' => $this->created_at->format('l jS, F, Y \a\t h:i:s'),
            'updated_at' => $this->updated_at->format('l jS, F, Y \a\t h:i:s'),
        ];
    }
}
