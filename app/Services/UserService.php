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
        // $defaultPassword = 'secret123';
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'phone_no'   => $data['phone_no'],
            'password'   => Hash::make($data['password']),
        ]);

        // assign role
        $user->assignRole($data['role']);

        return $user;
    }

    public function getUsers(User $actingUser, array $filters = []): LengthAwarePaginator
    {
        $query = User::query()->latest();

        // IT Admin cannot see super admin accounts
        if ($actingUser->hasRole('it_admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'super_admin');
            });
        }

        // 🔎 Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 📌 Status filter
        if (!empty($filters['status'])) {

            if ($filters['status'] === 'active') {
                $query->where('is_active', true);
            }

            if ($filters['status'] === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return $query->paginate(10);
    }

    public function updateUser(User $actingUser, User $targetUser, array $data): User
    {
        // IT admin cannot modify super admin
        if ($targetUser->hasRole('super_admin') && !empty($data['role'])) {
            throw new AuthorizationException('You cannot modify the Super Admin.');
        }

        // update basic fields
        $targetUser->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'phone_no'   => $data['phone_no'],
            'is_active'   => $data['is_active'],
        ]);

        // update password only if provided
        if (!empty($data['password'])) {
            $targetUser->update([
                'password' => Hash::make($data['password'])
            ]);
        }
        // ✅ Update role if provided
        if (!empty($data['role'])) {
            $targetUser->syncRoles([$data['role']]); // replaces old role
        }

        return $targetUser->fresh();
    }
}