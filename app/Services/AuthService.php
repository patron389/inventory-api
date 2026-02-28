<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Handle user registration
     * Creates user and immediately returns an API token (auto-login after register)
     */
    public function register($request)
    {
        // create user record with hashed password (never store plain text passwords)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // generate Sanctum personal access token for API authentication
        $token = $user->createToken('api_token')->plainTextToken;

        // return both user info and token so frontend can store it
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Authenticate user and issue token
     */
    public function login($request)
    {
        // find user by username instead of email
        $user = User::where('username', $request->username)->first();

        // verify user exists AND password matches hashed password
        if (!$user || !Hash::check($request->password, $user->password)) {
            abort(response()->json([
                'message' => 'Invalid username or password'
            ], 401));
        }
        // 🚨 CHECK IF USER IS ACTIVE
        if (!$user->is_active) {
            abort(response()->json([
                'message' => 'Your account is deactivated. Please contact admin.'
            ], 403));
        }
        // create new token every login (multiple devices supported)
        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Logout current device only
     * (does not logout other devices — important behavior)
     */
    public function logout($request)
    {
        // delete the token used in the current request
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
