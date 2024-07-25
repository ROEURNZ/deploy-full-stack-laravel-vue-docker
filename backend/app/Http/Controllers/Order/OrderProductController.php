<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\UserOrderResource;
use App\Http\Resources\UserSellerResource;
use App\Http\Resources\orderAndsellerResource;

class OrderProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderProducts = OrderProduct::where('order_id', auth()->id())->get();
        // Get all order products
    
        return response()->json([
            'success' => true,
            'orderProducts' => UserOrderResource::collection($orderProducts),
        ]);
    }
    public function orderAndSellerUser()
    {
        $orderProducts = OrderProduct::all();
        // Get all order products
    
        return response()->json([
            'success' => true,
            'orderProducts' => orderAndsellerResource::collection($orderProducts),
        ]);
    }
    public function userSeller()
    {
        $orderProducts = OrderProduct::whereHas('product', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
    
        return response()->json([
            'success' => true,
            'orderProducts' => UserSellerResource::collection($orderProducts),
        ]);
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the authenticated user
        $user_id = auth()->id();

        // Get the product details
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $totalPrice = $product->price * $quantity;

        // Create a new OrderProduct record
        $orderProduct = new OrderProduct([
            'order_id' => $user_id,
            'product_id' => $request->product_id,
            'qty' => $quantity,
            'totalPrice' => $totalPrice,
        ]);
        $orderProduct->save();

        return response()->json([
            'success' => true,
            'message' => 'Product added to order successfully',
            'orderProduct' => $orderProduct,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderProduct $orderProduct)
    {
        return response()->json([
            'success' => true,
            'orderProduct' => $orderProduct,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderProduct $orderProduct)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        // Get the product details
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity?? $orderProduct->qty;
        $totalPrice = $product->price * $quantity;

        // Update the OrderProduct record
        $orderProduct->product_id = $request->product_id;
        $orderProduct->qty = $quantity;
        $orderProduct->totalPrice = $totalPrice;
        $orderProduct->save();

        return response()->json([
            'success' => true,
            'message' => 'Order product updated successfully',
            'orderProduct' => $orderProduct,
        ]);
    }
    public function updateStatus(Request $request, string|int $id)
    {
        // Get the product details
        $OrderProducts = OrderProduct::findOrFail($id);

        // Update the OrderProduct record
        $OrderProducts->status = $request->status;
        $OrderProducts->save();

        return response()->json([
            'success' => true,
            'message' => 'Order product updated successfully',
            'orderProduct' => $OrderProducts,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|int $id)
    {
        $orderProduct = OrderProduct::findOrFail($id);
        $orderProduct->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order product deleted successfully',
            'orderProduct' => $orderProduct
        ]);
    }
}

