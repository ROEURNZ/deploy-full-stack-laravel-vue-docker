<?php

namespace App\Http\Controllers;
use App\Models\addtocart;
use Exception;
use App\Http\Resources\addToCartResource;

use Illuminate\Http\Request;

class addToCartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = addtocart::all();
        $cart = addToCartResource::collection($cart);
        return response()->json([ 'data' => $cart, 'message' => 'Got add cart successfully'], status:200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = $request->product_id;
        $user_id = $request->user_id;

        $add_to_cart = new addtocart();

        $add_to_cart->product_id = $cart;
        $add_to_cart->user_id = $user_id;

        try{
            $add_to_cart->save();
            return response()->json(['message' => 'Added to cart successfully'], status:200);
        }catch(Exception $error){
            return response()->json(['data'=>$error ,'message' => 'Failed to add to cart'], status:400);
        };
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = $request->id;
        $product_id = $request->product_id;
        $user_id = $request->user_id;

        $add_to_cart = addtocart::where('id', $id)->first();

        $add_to_cart->product_id = $product_id;
        $add_to_cart->user_id = $user_id;

        try{
            $add_to_cart->save();
            return response()->json(['message' => 'Update to cart successfully'], status:200);
        }catch(Exception $error){
            return response()->json(['data'=>$error ,'message' => 'Failed to add to cart'], status:400);
        };

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $add_to_cart =addtocart::find($id);
        $add_to_cart->delete();
        return response()->json(['message' => 'Delete from cart successfully'], status:200);
    }
}
