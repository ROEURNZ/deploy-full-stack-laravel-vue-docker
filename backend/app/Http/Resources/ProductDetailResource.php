<?php

namespace App\Http\Resources;

use App\Models\ProductUserRating;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\CommentProduct;
use App\Models\replyComment;
use App\Models\Category;


class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Load owner details
        $owner = User::select('id', 'name', 'email', 'profile', 'user_qrimage')
                    ->where('id', $this->user_id)
                    ->first();

        // Load comments inline
        $comments = CommentProduct::select('id', 'user_id', 'comment', 'image', 'created_at')
                                  ->where('product_id', $this->id)
                                  ->orderByDesc('created_at')
                                  ->get();

        // Load category inline
        $category = Category::select('id', 'category_name')
                            ->where('id', $this->category_id)
                            ->first();

        // load reply comments inline
        $reply = replyComment::select('id', 'comment_id', 'user_id','text')
        ->where('comment_id', $this->id)
        ->get();
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
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'image' => $this->image,
                'category_id' => $this->category_id,
                'pro_owner' => $owner ? [
                    'id' => $owner->id,
                    'name' => $owner->name,
                    'email' => $owner->email,
                    'profile' => $owner->profile,
                    'qrimage' => $owner->user_qrimage,
                ] : null,
                'category' => $category ? [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                ] : null,
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
                'comments' => $comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user_id' => $comment->user_id,
                        'comment' => $comment->comment,
                        'image' => $comment->image,
                        'created_at' => $comment->created_at,
                        'user' => User::select('id', 'name', 'email')
                                      ->where('id', $comment->user_id)
                                      ->first(),
                     'replies' => replyComment::where('comment_id', $comment->id)->get(),
                    ];
                }),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'success' => true,
            'message' => 'get product details successfully'
        ];
    }
}

// {
//     "data": {
//         "id": 2,
//         "product_name": "Pink boys",
//         "price": "23.80",
//         "description": "Discond 24% for Khmer new yeare",
//         "category_id": 1,
//         "pro_owner": {
//             "id": 2,
//             "name": "User",
//             "email": "user@gmail.com",
//             "profile": null
//         },
//         "category": {
//             "id": 1,
//             "category_name": "Clothes"
//         },
//         "comments": [
//             {
//                 "id": 1,
//                 "user_id": 2,
//                 "comment": "this product is so good",
//                 "image": "1719581109.jpg",
//                 "created_at": "2024-06-28T13:25:09.000000Z",
//                 "user": {
//                     "id": 2,
//                     "name": "User",
//                     "email": "user@gmail.com",
//                     "profile": null
//                 }
//             }
//         ],
//         "created_at": "2024-06-28T08:48:14.000000Z",
//         "updated_at": "2024-06-28T08:48:14.000000Z"
//     },
//     "success": true,
//     "message": "get product details details successfully"
// }