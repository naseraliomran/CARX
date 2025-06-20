<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\UserSubscription;
use App\Car;
use App\CarParts;

class CheckSubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $subscription = UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->orderByDesc('starts_at')
            ->first();

        if (!$subscription) {
            return response()->json(['message' => 'Subscription required'], 403);
        }

        $ads = Car::where('idUser', $user->id)
            ->whereBetween('created_at', [$subscription->starts_at, now()])
            ->count();
        $parts = CarParts::where('userID', $user->id)
            ->whereBetween('created_at', [$subscription->starts_at, now()])
            ->count();
        $total = $ads + $parts;

        if ($total >= $subscription->plan->ads_limit) {
            return response()->json(['message' => 'Ads limit reached'], 403);
        }

        return $next($request);
    }
}
