<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class StoreController extends Controller
{
    public function index(){
        $stores = Store::all();
        return StoreResource::collection($stores);
    }
    public function getUserStore(Request $request)
    {
        $user = $request->user();
        $stores = Store::where('created_by', $user->id)->get();
        if (!$stores) {
            return response()->json(['message' => 'No store found for this user.'], 404);
        }
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        try {
            // Check if the user already has a store
            $user = Auth::user();
            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'nullable|string',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
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
                $image->move(public_path('/api/stores/image/'), $imageName);
            }

            // Get the validated data
            $validatedData = $validator->validated();

            // Create new store instance
            $store = new Store([
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'description' => $validatedData['description'],
                'image' => $imageName,
                'user_id' => $user->id,
            ]);
            $store->save();
            $store->image_url = $imageName ? asset('/api/stores/image/' . $imageName) : null;

            return response()->json([
                'status' => true,
                'data' => $store,
                'message' => 'Store created successfully'
            ], 201);
        } catch (\Exception $error) {
            // Return error response if an exception occurs
            return response()->json([
                'status' => false,
                'message' => 'Store creation failed',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $store = Store::findOrFail($id); // Ensure store with ID exists
            return response()->json([
                'status' => true,
                'data' => [
                    'store' => new StoreResource($store),
                    'created_by' => [
                        'id' => $store->user->id,
                        'name' => $store->user->name,
                    ]
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found or an error occurred.',
            ], 404); // Return appropriate HTTP status code
        }
    }

    public function update(Request $request, $id)
{
    try {
        // Find the store by ID
        Log::info('Update request data:', $request->all());
        $store = Store::findOrFail($id);

        // Validate incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update store properties
        $store->name = $validatedData['name'];
        $store->address = $validatedData['address'] ?? $store->address;
        $store->description = $validatedData['description'];

        // Find the store by ID
        $store = Store::findOrFail($id);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($store->image) {
                $oldImagePath = public_path('/api/stores/image/' . $store->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload the new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('/api/stores/image/'), $imageName);
            $store->image = $imageName;
        }

        // Update store details
        $store->name = $request->input('name');
        $store->address = $request->input('address');
        $store->description = $request->input('description');
        $store->save();

        return response()->json([
            'status' => true,
            'data' => new StoreResource($store),
            'message' => 'Store updated successfully'
        ], 200);
    } catch (\Exception $error) {
        // Return error response if an exception occurs
        return response()->json([
            'status' => false,
            'message' => 'Store update failed',
            'error' => $error->getMessage()
        ], 400);
    }
}




    public function destroy($id)
    {
        try {
            // Find the store by ID
            $store = Store::findOrFail($id);

            // Logging the store details before deletion
            Log::info('Deleting store: ', $store->toArray());

            // Delete associated image from storage if it exists
            if ($store->image) {
                $imagePath = public_path('/api/stores/image/' . $store->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete the store
            $store->delete();

            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Store and associated image deleted successfully'
            ]);
        } catch (\Exception $error) {
            // Log the error message
            Log::error('Store deletion failed: ' . $error->getMessage());

            // Return error response if an exception occurs
            return response()->json([
                'status' => false,
                'message' => 'Store deletion failed',
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function getImage($id)
    {
        try {
            $store = Store::findOrFail($id);

            if ($store->image) {
                $path = public_path('/api/stores/image/' . $store->image);
                return Response::file($path);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Image not found for store with ID ' . $id,
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving image: ' . $e->getMessage(),
            ], 500);
        }
    }
   
}
