<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Check if validation fails and inform the user
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and if the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // Create a token for the user using Sanctum
            $token = $user->createToken('CryptoApi')->plainTextToken;

            // Return the raw token in the response
            return response()->json(['token' => $token]);
        } else {
            // Return an error if the login failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        
    }

    // This was not really in the spec but it would be nice to have it for any future development
    public function logout(Request $request)
    {
        // $request->user()->tokens->each(function ($token) {
        //     $token->delete();
        // });

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
