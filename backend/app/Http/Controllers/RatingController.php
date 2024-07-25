<?php

namespace App\Http\Controllers;

use App\Models;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating = Rating::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['status' => true, 'data' => $rating, 'message' => 'Rating submitted successfully'], 201);
    }

    public function index($productId)
    {
        $ratings = Rating::where('product_id', $productId)->with('user')->get();
        return response()->json(['status' => true, 'data' => $ratings, 'message' => 'Ratings retrieved successfully'], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $rating = Rating::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json(['status' => true, 'data' => $rating, 'message' => 'Rating updated successfully'], 200);
    }

    public function destroy($id)
    {
        $rating = Rating::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $rating->delete();

        return response()->json(['status' => true, 'message' => 'Rating deleted successfully'], 200);
    }
}
