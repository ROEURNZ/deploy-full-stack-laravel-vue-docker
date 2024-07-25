<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index()
    {
        return Order::list();
    }

    public function store(Request $request)
    {
        $order = Order::store($request);
        return response()->json($order, 201);
    }
    public function update(Request $request, $id = null){
        $order = Order::store($request , $id);
        return response()->json($order, 200);
    }
    public function delete($id){
        $order =Order::destroy($id);
        return response()->json($order, 200);
    }
}
