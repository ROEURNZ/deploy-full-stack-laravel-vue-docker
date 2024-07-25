<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductUserRating;
use App\Http\Resources\RateProductResource;
use Exception;

class RateProductController extends Controller
{
    /**
     * Rate a specific product.
     */
    public function rate(Request $request, $productId)
    {
        try {
            // Validate request
            $request->validate([
                'rating' => 'required|integer|min:1|max:5', // Example: Rating scale from 1 to 5
            ]);

            // Check if the product exists
            $product = Product::findOrFail($productId);

            // Check if the user has already rated the product
            $existingRating = ProductUserRating::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();

            if ($existingRating) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already rated this product',
                ], 400);
            }

            // Create a new rating
            $rating = new ProductUserRating([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'rating' => $request->rating,
            ]);
            $rating->save();

            // Fetch product details including name
            $ratedProduct = Product::findOrFail($productId);

            return response()->json([
                'status' => true,
                'message' => 'Product rated successfully',
                'data' => [
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'product_name' => $ratedProduct->name,
                    'rating' => $request->rating,
                    'updated_at' => $rating->updated_at,
                    'created_at' => $rating->created_at,
                    'id' => $rating->id,
                ],
            ], 201);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to rate the product',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove rating from a specific product.
     */
    public function removeRating($productId)
    {
        try {
            // Check if the product exists
            $product = Product::findOrFail($productId);

            // Find and delete the user's rating for the product
            $rating = ProductUserRating::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();

            if ($rating) {
                $rating->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Product rating removed successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'You have not rated this product',
                ], 400);
            }
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to remove product rating',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    /**
     * Get ratings for a specific product.
     */
    public function getRatings($productId)
    {
        try {
            // Check if the product exists
            $product = Product::findOrFail($productId);

            // Retrieve all ratings for the product
            $ratings = ProductUserRating::where('product_id', $productId)->get();

            return response()->json([
                'status' => true,
                'data' => RateProductResource::collection($ratings),
                'message' => 'Product ratings retrieved successfully',
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve product ratings',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
