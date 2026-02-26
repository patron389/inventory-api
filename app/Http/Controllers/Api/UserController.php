<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser(auth()->user(), $user);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->createUser(
            auth()->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function index()
    {
        $users = $this->userService->getUsers(auth()->user());

        return UserResource::collection($users);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (!auth()->user()->can('user.update')) {
            throw new AuthorizationException('You do not have permission to update users.');
        }
        $updatedUser = $this->userService->updateUser(
            auth()->user(),
            $user,
            $request->validated()
        );

        return response()->json([
            'message' => 'User updated successfully',
            'data' => new \App\Http\Resources\UserResource($updatedUser)
        ]);
    }
}