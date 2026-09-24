<?php

namespace App\Http\Middleware;

use App\Models\Tenant\AdminAndDriver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireAdminAndDriverLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('admin_and_driver_id');
        $userExists = $userId
            ? AdminAndDriver::query()->whereKey($userId)->where('is_active', true)->exists()
            : false;

        if (! $userExists) {
            $request->session()->forget('admin_and_driver_id');

            return redirect()->route('login');
        }

        return $next($request);
    }
}
