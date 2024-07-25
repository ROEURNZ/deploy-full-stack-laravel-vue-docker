<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\Products\RateProductController;
use App\Http\Controllers\Products\CommentProductController;
use App\Http\Controllers\ReplyProduct\ReplyCommentController;
use App\Http\Controllers\addToCartController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Order\PaymentController;
use App\Http\Controllers\Plans\SubscriptionController;
use App\Http\Controllers\Order\OrderProductController;
use App\Http\Controllers\Plans\PlanPayController;
use App\Http\Controllers\Store\StoreController;
use App\Http\Controllers\Stripe\StripePaymentController;
use App\Http\Controllers\Plans\PlansController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/me', [AuthController::class, 'index'])->middleware('auth:sanctum');
Route::get('/post/list', [PostController::class, 'index'])->middleware('auth:sanctum');

Route::get('/user/list', [AuthController::class, 'listUser']);

// crud on categories
Route::get('/categories/list', [CategoryController::class, 'index']);
Route::post('/create/category', [CategoryController::class, 'store']);
// forgot password
Route::post('user/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('user/reset-password', [AuthController::class, 'resetPassword']);

// Other routes that don't require authentication

Route::post('/update/category/{id}', [CategoryController::class, 'update']);
Route::delete('/delete/category/{id}', [CategoryController::class, 'destroy']);


// Product Routes
Route::prefix('products')->group(function () {
    Route::get('/list', [ProductController::class, 'index']);
    Route::get('/view/{id}', [ProductController::class, 'show']);
});

// Route::get('/products/image/{id}', [ProductController::class, 'getImage']);

Route::get('/products/category/{id}', [ProductController::class, 'productCate']);
Route::get('/products/pro_details/{product_id}', [ProductController::class, 'show'])->name('products.view');
Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    // Route::get('/list', [ProductController::class, 'index']);
    Route::post('/create', [ProductController::class, 'store']);
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/remove/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/ratings/{productId}', [ProductController::class, 'getProductRatings']);

    Route::get('/image/{id}', [ProductController::class, 'getImage']);
});
// Route::delete('/product/remove/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::post('/ratting/{productId}', [RateProductController::class, 'rate']);
    Route::delete('/remove/{productId}', [RateProductController::class, 'removeRating']);
});
Route::get('/products/ratings/{productId}', [RateProductController::class, 'getRatings']);


// Comment Products Routes
Route::prefix('comment')->group(function () {
    Route::get('/list', [CommentProductController::class, 'index']);
    Route::post('/create', [CommentProductController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/view/{id}', [CommentProductController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/update/{id}', [CommentProductController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/remove/{id}', [CommentProductController::class, 'destroy'])->middleware('auth:sanctum');
});

// reply comments to products
Route::prefix('reply')->group(function () {
    Route::get('/list', [ReplyCommentController::class, 'index']);
    Route::get('/detail', [ReplyCommentController::class, 'show']);
    Route::post('/create', [ReplyCommentController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/update/{id}', [ReplyCommentController::class, 'update']);
    Route::delete('/remove/{id}', [ReplyCommentController::class, 'destroy']);
});

// User create stores
Route::prefix('store')->group(function () {
    Route::get('/list', [StoreController::class, 'index']);
    Route::post('/create', [StoreController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/update/{id}', [StoreController::class, 'update'])->middleware('auth:sanctum');
   
    Route::get('/show/{id}', [StoreController::class, 'show']);
    Route::delete('/remove/{id}', [StoreController::class, 'destroy']);

});


// messages chat
Route::middleware('auth:sanctum')->prefix('message')->group(function () {
    Route::get('/chat/rooms', [ChartController::class, 'rooms']);
    Route::get('/chat/messages/{roomId}', [ChartController::class, 'messages']);
    Route::post('/chat/messages/{roomId}', [ChartController::class, 'newMessage']);
    Route::post('/chat/room', [ChartController::class, 'createRoom']);
});

Route::get('/myaccount', [UserProfileController::class, 'show'])->middleware('auth:sanctum');
Route::post('/user/update', [UserProfileController::class, 'update'])->middleware('auth:sanctum');
Route::get('/userproduct', [UserProfileController::class, 'userproduct']);

// customer orders the product
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orderProducts', [OrderController::class, 'store']);
Route::put('/orderProducts/{id}', [OrderController::class, 'update']);
Route::delete('/orderProducts/delete/{id}', [OrderController::class, 'delete']);

// Payment
Route::middleware('auth:sanctum')->prefix('payment')->group(function () {
    Route::post('/create', [PaymentController::class, 'createPayment']);
});

Route::get('/user/{id}', [UserProfileController::class, 'show']);
Route::post('/user/update', [UserProfileController::class, 'update'])->middleware('auth:sanctum');
Route::get('/userproduct', [UserProfileController::class, 'userproduct']);
Route::get('/userStore', [UserProfileController::class, 'userStore']);

// Crud on Add to Cart
Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
    Route::get('/list', [addToCartController::class, 'index']);
    Route::post('/add', [addToCartController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/update/{id}', [addToCartController::class, 'update']);
    Route::delete('/remove/{id}', [addToCartController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->prefix('order')->group(function () {
    Route::get('/list', [OrderProductController::class, 'index']);
    Route::get('/list/seller', [OrderProductController::class, 'userSeller']);
    Route::post('/create/order', [OrderProductController::class, 'store']);
    Route::put('/update/{id}', [OrderProductController::class, 'update']);
    Route::put('/update/status/{id}', [OrderProductController::class, 'updateStatus']);
    Route::delete('/remove/{id}', [OrderProductController::class, 'destroy']);
});

// Customer charge for product
Route::get('/orders/list', [OrderProductController::class, 'orderAndSellerUser']);
// charge the money
Route::post('/stripe/payment', [StripePaymentController::class, 'makePayment']);

Route::post('/subscriptions', [SubscriptionController::class, 'subscribe']);
Route::get('/users/{userId}/subscriptions', [SubscriptionController::class, 'userSubscriptions']);

// User post product information
Route::get('/user/post-count', [ProductController::class, 'getUserPostCount']);

Route::post('/stripe/payment', [StripePaymentController::class, 'makePayment']);
Route::post('/stripe/handlePaymentSuccess', [StripePaymentController::class, 'handlePaymentSuccess']);
Route::get('/payment',[StripePaymentController::class, 'index']);
Route::get('/show/payment/{id}', [StripePaymentController::class, 'show']);
Route::delete('/delete/payment/{id}', [StripePaymentController::class, 'deletePayment']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/post-count', [ProductController::class, 'getUserPostCount']);
    Route::post('/products', [ProductController::class, 'store']);
});
Route::get('/lists', [PlanPayController::class, 'index']);
Route::get('/plans/{id}', [PlanPayController::class, 'show']);
Route::put('update/plans/{id}', [PlanPayController::class, 'update']);
Route::delete('delete/plans/{id}', [PlanPayController::class, 'destroy']);
Route::post('/create/plans', [PlanPayController::class, 'store']);
Route::post('/setDate', [StripePaymentController::class, 'setNextChargeDate']);

Route::post('/send-message', [MessageController::class, 'sendMessage']);


