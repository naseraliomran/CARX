<?php

namespace App\Http\Controllers;

use App\SubscriptionPlan;
use App\UserSubscription;
use App\Car;
use App\CarParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function getPlans()
    {
        return response()->json(SubscriptionPlan::all());
    }

    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $plan = SubscriptionPlan::find($request->plan_id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        $startsAt = now();
        $expiresAt = $startsAt->copy()->addDays($plan->duration_days);
        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);
        return response()->json($subscription);
    }

    public function mySubscription()
    {
        $user = Auth::user();
        $sub = UserSubscription::where('user_id', $user->id)
            ->orderByDesc('starts_at')->first();
        if (!$sub) {
            return response()->json(null, 404);
        }
        return response()->json($sub);
    }

    public function myAdsCount()
    {
        $user = Auth::user();
        $sub = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderByDesc('starts_at')
            ->first();
        if (!$sub) {
            return response()->json(['ads_count' => 0, 'ads_limit' => 0, 'remaining' => 0]);
        }
        $ads = Car::where('idUser', $user->id)
            ->whereBetween('created_at', [$sub->starts_at, now()])
            ->count();
        $parts = CarParts::where('userID', $user->id)
            ->whereBetween('created_at', [$sub->starts_at, now()])
            ->count();
        $count = $ads + $parts;
        $limit = $sub->plan->ads_limit;
        return response()->json([
            'ads_count' => $count,
            'ads_limit' => $limit,
            'remaining' => $limit - $count,
        ]);
    }
}
