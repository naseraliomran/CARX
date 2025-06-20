<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionPlan;
use App\UserSubscription;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function initiate(Request $request)
    {
        $plan = SubscriptionPlan::find($request->plan_id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        // Here payment request with Iraqi gateways will be implemented
        return response()->json(['message' => 'Payment initialization placeholder']);
    }

    public function webhook(Request $request)
    {
        if ($request->status === 'paid') {
            $plan = SubscriptionPlan::find($request->plan_id);
            if ($plan) {
                $starts = now();
                $expires = $starts->copy()->addDays($plan->duration_days);
                UserSubscription::create([
                    'user_id' => $request->user_id,
                    'plan_id' => $plan->id,
                    'starts_at' => $starts,
                    'expires_at' => $expires,
                    'status' => 'active',
                ]);
            }
        }
        return response()->json(['message' => 'ok']);
    }
}
