<?php

namespace App\Services;
use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function allRoles()
    {
    return Role::where('name', '!=', 'super_admin')
        ->select('id', 'name')
        ->orderBy('name')
        ->get();
    }
}
