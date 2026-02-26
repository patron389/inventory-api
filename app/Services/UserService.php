<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function deleteUser(User $actingUser, User $targetUser): void
    {
        if ($targetUser->hasRole('super_admin')) {
            throw new AuthorizationException('Super Admin cannot be deleted.');
        }

        if ($actingUser->id === $targetUser->id) {
            throw new AuthorizationException('You cannot delete your own account.');
        }

        $targetUser->delete();
    }

    public function createUser(User $actingUser, array $data): User
    {
        // extra safety: never allow creating super admin
        if ($data['role'] === 'super_admin') {
            throw new AuthorizationException('Super Admin cannot be created via API.');
        }

        // create user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
        ]);

        // assign role
        $user->assignRole($data['role']);

        return $user;
    }

    public function getUsers(User $actingUser): LengthAwarePaginator
    {
        $query = User::query()->latest();

        // IT Admin cannot see super admin accounts
        if ($actingUser->hasRole('it_admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            });
        }

        return $query->paginate(10);
    }

    public function updateUser(User $actingUser, User $targetUser, array $data): User
    {
        // IT admin cannot modify super admin
        if ($targetUser->hasRole('super_admin') && !$actingUser->hasRole('super_admin')) {
            throw new AuthorizationException('You cannot modify the Super Admin.');
        }

        // update basic fields
        $targetUser->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'username'   => $data['username'],
            'email'      => $data['email'],
        ]);

        // update password only if provided
        if (!empty($data['password'])) {
            $targetUser->update([
                'password' => Hash::make($data['password'])
            ]);
        }

        return $targetUser->fresh();
    }
}