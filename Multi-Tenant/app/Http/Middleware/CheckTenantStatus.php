<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckTenantStatus
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenant(); // ✅ Correct way to get current tenant

        if ($tenant && $tenant->status === 'disabled') {
            Log::warning("Access blocked: Tenant {$tenant->id} is disabled.");
            
            // 🔥 Instead of returning JSON, return a view
            return response()->view('tenant.disabled', [], 403);
        }

        return $next($request);
    }
}
