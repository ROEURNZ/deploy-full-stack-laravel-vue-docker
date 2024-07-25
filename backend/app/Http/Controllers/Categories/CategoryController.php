<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categories = CategoryResource::collection($categories);
        return response()->json([
            'status' => 'success',
            'message' => 'You can get the categories list',
            'data' => $categories
        ], 200);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Category retrieved successfully',
            'data' => new CategoryResource($category),
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('api/categories/image'), $imageName);
        }

        $category = Category::create([
            'category_name' => $request->category_name,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }



    public function update(Request $request, $id)
{
    $request->validate([
        'category_name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $category = Category::findOrFail($id);

    $imageName = $category->image;
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($imageName && file_exists(public_path('api/categories/image/' . $imageName))) {
            unlink(public_path('api/categories/image/' . $imageName));
        }

        // Store the new image
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('api/categories/image'), $imageName);
    }

    $category->update([
        'category_name' => $request->category_name,
        'image' => $imageName,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Category updated successfully',
        'data' => new CategoryResource($category),
    ], 200);
}


public function destroy($id)
{
    $category = Category::findOrFail($id);
    if ($category->image) {
        $imagePath = public_path('api/categories/image/' . $category->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            Log::warning("File not found: " . $imagePath);
        }
    }
    $category->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Category deleted successfully',
    ], 200);
}


}
