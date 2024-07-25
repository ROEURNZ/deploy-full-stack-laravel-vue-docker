<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ProductUserRating;

class RateProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $oneStar = ProductUserRating::where('rating',1)->first();
        $twoStars = ProductUserRating::where('rating',2)->first();
        $threeStars = ProductUserRating::where('rating',3)->first();
        $fourStars = ProductUserRating::where('rating',4)->first();
        $fiveStars = ProductUserRating::where('rating',5)->first();
        return [
            'oneStar' => $oneStar,
            'twoStars' => $twoStars,
            'threeStars' => $threeStars,
            'fourStars' => $fourStars,
            'fiveStars' => $fiveStars,
            'rating' => $this->rating,
        ];
    }
}
