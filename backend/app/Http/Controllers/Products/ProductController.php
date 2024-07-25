<?php

namespace App\Http\Controllers\Products;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductUserRating;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductCateResource;


class ProductController extends Controller
{

    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($products),
        ]);
    }

    public function productCate(string|int $id)
    {
        if (Category::find($id)){
            $products = Product::where('category_id', $id)->get();
            try{
                return response()->json([
                    'status' => true,
                    'data' => ProductCateResource::collection($products),
                ],200);   
            }catch(Exception $e){
                return response()->json(['message'=>['message', $e->getMessage()]], status:500);
            }
        }else{
            return response()->json(['message'=> 'Category id not found'], status:400);
        }

    }

      // Return error response if an exception occur
    public function store(Request $request)
{
    try {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800', // Adjust max file size as needed
            'category_id' => 'required|exists:categories,id',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if provided
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('/api/products/image/'), $imageName);
        }

        // Get the authenticated user
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
        // Get the authenticated user
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }

        $postLimit = 10;
        if ($user->post_count >= $postLimit) {
            if ($user->has_paid) {
               
                $user->post_count = 0;
                $user->has_paid = false; 
                $user->save();
            } 
            if ($user->has_paid) {

                $user->next_charge_date = now()->addMonth(); 
                $user->save();
            }
                return response()->json([
                    'status' => false,
                    'message' => 'You have reached the maximum number of posts allowed. Please make a payment to continue posting.',
                    
                ], 403); 
        }

        // Debug: Log user information
        Log::info('Authenticated user:', $user->toArray());

        

        // Create new product instance and associate with the user
        $product = new Product([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName, 
            'category_id' => $request->category_id,
            'user_id' => $user->id, 
        ]);

        // Save the product to the database
        $product->save();

        // Increment the user's post_count
        $user->increment('post_count');
        $user->refresh(); 
        Log::info('User post_count after increment:', ['post_count' => $user->post_count]);
        $product->image_url = $imageName ? asset('/api/products/image/' . $imageName) : null;

        // Return success response
        return response()->json([
            'status' => true,
            'data' => $product, 
            'message' => 'Product created successfully'
        ], 201);
    } catch (\Exception $error) {
        // Return error response if an exception occurs
        return response()->json([
            'status' => false,
            'message' => 'Product creation failed',
            'error' => $error->getMessage()
        ], 400);
    }
}   


   public function show(Request $request, $id)
   {
       $product = Product::findOrFail($id); // Ensure product with ID exists
       try {
           return response()->json([
               'status' => true,
               'data' => new ProductDetailResource($product),
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'status' => false,
               'message' => 'Product not found or an error occurred.',
           ], 404); // Return appropriate HTTP status code
       }
   }

   public function update(Request $request, $id)
   {
       try {
           // Validate incoming request
           $validator = Validator::make($request->all(), [
               'name' => 'nullable|string|max:255',
               'description' => 'nullable|string',
               'price' => 'nullable|numeric',
               'quantity' => 'nullable|numeric',
               'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048', // Adjust max file size as needed
               'category_id' => 'nullable|exists:categories,id',
           ]);

           // Handle validation errors
           if ($validator->fails()) {
               return response()->json([
                   'status' => false,
                   'message' => 'Validation error',
                   'errors' => $validator->errors()
               ], 422);
           }

           // Find the product by ID
           $product = Product::findOrFail($id);

           // Handle image update if provided
           if ($request->hasFile('image')) {
               // Delete previous image if exists
               if ($product->image) {
                   $imagePath = public_path('/api/products/image/' . $product->image);
                   if (file_exists($imagePath)) {
                       unlink($imagePath);
                   }
               }

               // Upload new image
               $file = $request->file('image');
               $imageName = time() . '_' . $file->getClientOriginalName();
               $file->move(public_path('/api/products/image/'), $imageName);

               // Update image field in product
               $product->image = $imageName;
           }

           // Update other fields
           if ($request->has('name')) {
               $product->name = $request->name;
           }
           if ($request->has('description')) {
               $product->description = $request->description;
           }
           if ($request->has('price')) {
               $product->price = $request->price;
           }
           if ($request->has('quantity')) {
               $product->quantity = $request->quantity;
           }
           if ($request->has('category_id')) {
               $product->category_id = $request->category_id;
           }

           // Save the updated product
           $product->save();

           // Prepare the response with correct image URL if an image was uploaded
           $product->image_url = $product->image ? asset('/api/products/image/' . $product->image) : null;

           // Return success response
           return response()->json([
               'status' => true,
               'data' => new ProductResource($product),
               'message' => 'Product updated successfully'
           ]);
       } catch (\Exception $error) {
           // Return error response if an exception occurs
           return response()->json([
               'status' => false,
               'message' => 'Product update failed',
               'error' => $error->getMessage()
           ], 400);
       }
   }
   
    public function destroy($id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);

            // Logging the product details before deletion
            Log::info('Deleting product: ', $product->toArray());

            // Delete associated image from storage if it exists
            if ($product->image) {
                $imagePath = public_path('/api/products/image/' . $product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete the product
            $product->delete();

            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Product and associated image deleted successfully'
            ]);
        } catch (\Exception $error) {
            // Log the error message
            Log::error('Product deletion failed: ' . $error->getMessage());

            // Return error response if an exception occurs
            return response()->json([
                'status' => false,
                'message' => 'Product deletion failed',
                'error' => $error->getMessage()
            ], 400);
        }
    }
    public function getImage($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                $path = public_path('/api/products/image/' . $product->image);
                return Response::file($path);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Image not found for product with ID ' . $id,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving image: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Retrieve ratings for a specific product.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductRatings($productId)
    {
        try {
            // Find the product
            $product = Product::findOrFail($productId);

            // Fetch ratings associated with this product
            $ratings = ProductUserRating::where('product_id', $productId)
                ->with('user')
                ->get();

            // Format response data
            $formattedRatings = $ratings->map(function ($rating) use ($product) {
                $role = ($rating->user_id === $product->user_id) ? 'owner' : 'customer';

                return [
                    'rating_id' => $rating->id,
                    'user_id' => $rating->user->id,
                    'user_name' => $rating->user->name,
                    'user_email' => $rating->user->email,
                    'rating' => $rating->rating,
                    'role' => $role,
                    'created_at' => $rating->created_at,
                ];
            });
            // Return JSON response with success message and ratings data
            return response()->json([
                'status' => true,
                'message' => 'Product ratings retrieved successfully',
                'data' => [
                    'product_id' => $productId,
                    'product_name' => $product->name,
                    'ratings' => $formattedRatings,
                ],
            ], 200);
        } catch (Exception $error) {
            // Return JSON response with error message and details
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve product ratings',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
    

}