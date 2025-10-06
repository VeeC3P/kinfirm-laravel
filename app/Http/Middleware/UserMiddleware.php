<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get or create a dummy static user, otherwise I would write here proper authentication code
        // $user = User::firstOrCreate(['id' => 1], [
        //     'name' => 'Static User',
        //     'email' => 'user@example.com',
        //     'password' => Hash::make('Password1!')
        // ]);

        // Check if the token exists in the request
        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                'error' => 'No token provided. Please provide an API token.',
            ], 401);
        }

        // Extract the token from the Authorization header
        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        // Attempt to authenticate the user using the token
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'error' => 'Invalid token. Please provide a valid token.',
            ], 401);
        }

        // Optionally, bind the user to the request for future access
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
