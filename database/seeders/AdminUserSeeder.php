<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        try {

            // create admin
            $admin = User::create([
                'first_name' => 'System',
                'last_name'  => 'Admin',
                'username'   => 'admin',
                'email'      => 'admin@inventory.com',
                'password'   => Hash::make('admin123')
            ]);

            dump('USER CREATED', $admin->id);

            // fetch role manually
            $role = Role::where('name', 'super_admin')->first();
            dump('ROLE FOUND', $role?->guard_name);

            // assign role
            $admin->assignRole($role);

            dump('ROLE ASSIGNED');

        } catch (\Throwable $e) {

            dump('ERROR OCCURRED:');
            dump($e->getMessage());
            dump($e->getTraceAsString());

            throw $e;
        }
    }
}
