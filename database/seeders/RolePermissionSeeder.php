<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // clear cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // wipe old guard data to avoid guard mismatch
        Permission::query()->delete();
        Role::query()->delete();
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            'warehouse.view',
            'warehouse.create',
            'warehouse.update',
            'warehouse.delete',

            'product.view',
            'product.create',
            'product.update',
            'product.delete',

            'brand.view',
            'brand.create',
            'brand.update',
            'brand.delete',
            
            'category.view',
            'category.create',
            'category.update',
            'category.delete',

            'subcategory.view',
            'subcategory.create',
            'subcategory.update',
            'subcategory.delete',

            'stock.view',
            'stock.add',
            'stock.transfer',
            'stock.adjust',

            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.assign_role',
        ];

        // IMPORTANT: use WEB guard (not sanctum)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        // SUPER ADMIN → full access (root account)
        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web'
        ]);
        $superAdmin->syncPermissions(Permission::all());

        // IT ADMIN → manages users but not system ownership
        $itAdmin = Role::firstOrCreate([
            'name' => 'it_admin',
            'guard_name' => 'web'
        ]);
        $itAdmin->syncPermissions([
            'warehouse.view',
            'warehouse.create',
            'warehouse.update',
            'warehouse.delete',

            'product.view',
            'product.create',
            'product.update',
            'product.delete',
            
            'stock.view',
            'stock.add',
            'stock.transfer',
            'stock.adjust',

            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.assign_role',
        ]);

        // Staff → operational tasks
        $staff = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web'
        ]);
        $staff->syncPermissions([
            'warehouse.view',
            'stock.view',
            'stock.add',
            'stock.transfer',
        ]);

        // Viewer → read only
        $viewer = Role::firstOrCreate([
            'name' => 'viewer',
            'guard_name' => 'web'
        ]);
        $viewer->syncPermissions([
            'warehouse.view',
            'stock.view',
        ]);
    }
}
