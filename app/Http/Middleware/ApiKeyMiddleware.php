<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('Authorization'); // Bearer <token>

        if ($apiKey !== config('app.apikey')) {
            return response()->json(['message' => 'Unauthorized',
        'status' => 401], 401);
        }

        return $next($request);
    }
}
