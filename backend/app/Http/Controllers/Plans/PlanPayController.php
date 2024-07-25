<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Models\PlanPay;
use Illuminate\Http\Request;

class PlanPayController extends Controller
{
    public function index()
    {
        $plans = PlanPay::all();
        return response()->json($plans);
    }

    public function store(Request $request)
    {
        $plan = PlanPay::create($request->all());
        return response()->json($plan, 201);
    }

    public function show($id)
    {
        $plan = PlanPay::findOrFail($id);
        return response()->json($plan);
    }

    public function update(Request $request, $id)
    {
        $plan = PlanPay::findOrFail($id);
        $plan->update($request->all());
        return response()->json($plan, 200);
    }

    public function destroy($id)
    {
        PlanPay::destroy($id);
        return response()->json(
            ['message' => 'Plan deleted successfully',
            'status' => 'true',
            'code' => 200,
            
            ]

        );
    }
}
