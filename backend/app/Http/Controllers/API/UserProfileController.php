<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserProductResource;

class UserProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        try {
            return response()->json(['user' => $user, 'message' => 'You can see your profile'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all products of a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userProduct(Request $request): JsonResponse
    {
        $user_id = $request->id;
        $user = User::findOrFail($user_id);
        try {
            return response()->json(['data' => UserProductResource::make($user), 'message' => 'Get all user\'s products', 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all stores of a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userStore(Request $request): JsonResponse
    {
        $user_id = $request->id;
        $user = User::findOrFail($user_id);
        try {
            return response()->json(['data' => StoreResource::make($user), 'message' => 'Get all stores of user', 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user_id,
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_qrimage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update user details
        if ($request->name) {
            $user->name = $request->name;
        }

        if ($request->email) {
            $user->email = $request->email;
        }

        // Handle profile image
        if ($request->hasFile('profile')) {
            // Generate a unique filename
            $profileImage = time() . '.' . $request->file('profile')->extension();
            // Move the file to the public directory
            $request->file('profile')->move(public_path('api/profiles'), $profileImage);
            // Save the filename to the database
            $user->profile = $profileImage;
        }

        // Handle user QR image
        if ($request->hasFile('user_qrimage')) {
            // Generate a unique filename
            $userQrImage = time() . '.' . $request->file('user_qrimage')->extension();
            // Move the file to the public directory
            $request->file('user_qrimage')->move(public_path('api/userqrimage'), $userQrImage);
            // Save the filename to the database
            $user->user_qrimage = $userQrImage;
        }

        $user->save();

        return response()->json([
            'data' => $user,
            'message' => 'User updated successfully',
        ], 200);
    }
}
