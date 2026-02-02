<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authentication
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        $payload = auth('api')->payload();

        $roleName = Role::where('id', $payload->get('role_id'))->first();

        $branches = $payload->get('branches');

        $request->merge([
            'user_id' => $user->getAuthIdentifier(),
            'company_id' => $payload->get('company_id'),
            'role' => $roleName->name,
            'branches' => $payload->get('branches')
        ]);

        return $next($request);
    }
}
