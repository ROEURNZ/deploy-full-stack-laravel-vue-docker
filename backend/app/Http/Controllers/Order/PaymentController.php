<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createPayment(Request $request){
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string|in:ABA,Canadia,Wing', // Validate the selected bank
            'account_number' => 'required|string',
            'amount' => 'required|numeric',
        ]);
        $order = Order::findOrFail($request->order_id);
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->payment_method = $request->payment_method;
        $payment->account_number = $request->account_number;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->status = 'pending'; 
        $payment->save();
        switch ($request->payment_method) {
            case 'ABA':
                // Redirect to ABA bank transfer details page
                return response()->json(['message' => 'Redirecting to ABA bank transfer details page'], 200);
            case 'Canadia':
                // Redirect to Canadia bank transfer details page
                return response()->json(['message' => 'Redirecting to Canadia bank transfer details page'], 200);
            case 'Wing':
                // Redirect to Wing bank transfer details page
                return response()->json(['message' => 'Redirecting to Wing bank transfer details page'], 200);
            default:
                return response()->json(['message' => 'Invalid payment method'], 400);
        }
        return response()->json(['message' => 'Payment proccessed successfully'], 201);
        
        // Update corresponding order status
        $order = Order::findOrFail($payment->order_id);
        $order->status = 'processing'; // or 'completed' depending on your workflow
        $order->save();
        
        return response()->json(['message' => 'Payment verified successfully', 'payment' => $payment, 'order' => $order], 200);
    }

}
