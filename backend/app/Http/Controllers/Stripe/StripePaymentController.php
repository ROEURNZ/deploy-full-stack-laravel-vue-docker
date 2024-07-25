<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Http\Resources\StripeResource;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use App\Models\User;
use App\Models\UserPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Customer;
use Stripe\Refund;

class StripePaymentController extends Controller
{
    public function index()
    {
        try {
            // Fetch all user payments with related user data
            $userPayments = UserPayment::with('user')->get();

            return response()->json([
                'message' => 'success',
                'data' => $userPayments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function makePayment(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    // Validate the request
    $validatedData = $request->validate([
        'email' => 'required|email',
        'amount' => 'required|numeric|min:0'
    ]);

    try {
        // Create or retrieve customer
        $customer = Customer::create([
            'email' => $validatedData['email'],
        ]);

        // Create a PaymentIntent to charge the customer
        $paymentIntent = PaymentIntent::create([
            'amount' => $validatedData['amount'] * 100, // Convert to cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
            'receipt_email' => $validatedData['email'],
        ]);

        // Store payment details in the database
        $user = User::where('email', $validatedData['email'])->first();
        if ($user) {
            UserPayment::create([
                'user_id' => $user->id,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount / 100, // Convert to dollars
                'currency' => $paymentIntent->currency,
                'payment_method' => 'card',
                'payment_date' => Carbon::now()->toDateTimeString()
            ]);
        }

        // Return client secret to frontend
        return response()->json([
            'success' => true,
            'client_secret' => $paymentIntent->client_secret,
            'scheduled' => true,
        ]);
    } catch (ApiErrorException $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




    public function setNextChargeDate(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user) {
            $user->next_charge_date = $request->next_charge_date;
            $user->save();

            return response()->json(['message' => 'Next charge date set successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
    public function handlePaymentSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Validate the request
            $request->validate([
                'payment_intent_id' => 'required|string',
                'email' => 'required|string',
                
            ]);

            // Retrieve the PaymentIntent
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $user = User::where('email', $request->email)->first();

                if ($user) {
                    // Calculate next charge date based on the plan
                    $nextChargeDate = Carbon::now();
                    if ($request->plan === 'Pro') {
                        $nextChargeDate = $nextChargeDate->addMonth(); // Set next charge date for Pro plan
                    }

                    // Update user's next_charge_date, post_count, and has_paid flag
                    $user->next_charge_date = $nextChargeDate;
                    $user->post_count = 0;
                    $user->has_paid = true;
                    $user->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Payment successful. Post count reset for user.',
                        'next_charge_date' => $nextChargeDate->toDateString(),
                        'payment_date' => Carbon::now()->toDateTimeString(), // Payment date
                        'amount' => $paymentIntent->amount / 100, // Amount in dollars
                        'payment_method' => 'card' // Payment method
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment not successful.',
                    'payment_intent_status' => $paymentIntent->status,
                ], 400);
            }
        } catch (ApiErrorException $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while confirming the payment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id){
        $payment = UserPayment::find($id);
        if ($payment) {
            return response()->json([
                'payment' => $payment,
            ]);
        }
        return response()->json([
            'error' => 'Payment not found',
        ], 404);
    }
    public function deletePayment($id)
    {
        try {
            // Find the payment by ID and delete it
            $payment = UserPayment::findOrFail($id);
            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
