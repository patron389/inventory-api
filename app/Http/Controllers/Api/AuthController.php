<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * Register new user
     * Validation handled by RegisterRequest
     * Business logic handled by AuthService
     */
    public function register(RegisterRequest $request, AuthService $authService)
    {
        // controller only forwards request to service and returns JSON response
        return response()->json($authService->register($request));
    }

    /**
     * Login existing user
     * Returns API token if credentials are valid
     */
    public function login(LoginRequest $request, AuthService $authService)
    {
        // authentication logic lives in service layer
        return response()->json($authService->login($request));
    }

    /**
     * Get currently authenticated user
     * Requires auth:sanctum middleware
     */
    public function me(Request $request)
    {
        // Sanctum automatically attaches the authenticated user to the request
        return new UserResource($request->user());
    }

    /**
     * Logout current authenticated device/session
     */
    public function logout()
    {
        $user = auth()->user();

        // delete only current token
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
