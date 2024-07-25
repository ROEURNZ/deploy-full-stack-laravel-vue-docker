<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\CommentProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Your code to display a list of comments
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {
         $user_id = auth()->id();

         // return $request;
         $validatedData = Validator::make($request->all(),[
             'product_id' => 'required|exists:products,id',
             'comment' => 'required|string',
             'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
         ]);
        //  return $request;
         $comment = new CommentProduct();

         if ($validatedData->fails()){
             return response()->json([
                 'success'=> false,
                 'message'=> $validatedData->errors(),
                 'status'=>404
             ]);
         }

         $img = $request->image;
         $ext = $img->getClientOriginalExtension();
         $imageName = time().'.'.$ext;
         $img->move(public_path('/images/product/'), $imageName);

         $comment->product_id = $request->product_id;
         $comment->user_id = $user_id;
         $comment->comment = $request->comment;
         $comment->image = $imageName;
         $comment->save();
         return response()->json([
             'comment' => $comment,
             'image_path' => asset('/images/product/'. $imageName),
             'success' => true,
             'message' => 'comment created successfully',
             'status' => 200
         ]);
     }
    /**
     * Display the specified resource.
     */
    public function show(string|int $id)
    {
        //
        $comment = CommentProduct::findOrFail($id);
        try{
            return response()->json(['comment' => $comment,'message' => 'You can see the comment'], status:200);
        }catch (\Exception $e){
            return response()->json(['message'=>['message', $e->getMessage()]], status:500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommentProduct $commentProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        //
        $commentProduct = CommentProduct::findOrFail($id);
        try{
            $commentProduct->delete();
            return response()->json(['message'=> 'Deleted feedback successfully'], status:200);
        }catch (\Exception $e){
            return response()->json(['message'=>['message', $e->getMessage()]], status:500);
        }
    }
}
