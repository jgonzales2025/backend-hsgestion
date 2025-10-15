<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authentication
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $payload = auth('api')->payload();

        $request->merge([
            'user_id' => $user->getAuthIdentifier(),
            'company_id' => $payload->get('company_id')
        ]);

        return $next($request);
    }
}
