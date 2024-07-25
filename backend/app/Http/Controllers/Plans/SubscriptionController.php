<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plans;
use App\Models\Subscription;


class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = User::findOrFail($request->user_id);
        // $plan = Plans::findOrFail($request->plan_id);
        if ($user->subscription) {
            return response()->json(['error' => 'User already has an active subscription'], 400);
        }

        // Create a new subscription for the user
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        // $subscription->plan_id = $plan->id;
        $subscription->expires_at = now()->addMonth(); 
        $subscription->save();

        return response()->json(['subscription' => $subscription], 201);
    }

    public function userSubscriptions($userId)
    {
        $user = User::findOrFail($userId);
        $subscriptions = $user->subscriptions()->with('plan')->get();

        return response()->json(['subscriptions' => $subscriptions]);
    }

}


