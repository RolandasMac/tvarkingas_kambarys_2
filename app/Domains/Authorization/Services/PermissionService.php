<?php
namespace App\Domains\Authorization\Services;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    public function createRole(string $roleName): Role
    {
        return Role::firstOrCreate(['name' => $roleName]);
    }

    public function createPermission(string $permissionName): Permission
    {
        return Permission::firstOrCreate(['name' => $permissionName]);
    }

    public function assignPermissionToRole(string $permissionName, string $roleName): void
    {
        $role       = Role::where('name', $roleName)->firstOrFail();
        $permission = Permission::where('name', $permissionName)->firstOrFail();
        $role->givePermissionTo($permission);
    }

    public function assignRoleToUser(User $user, string $roleName): void
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        $user->assignRole($role);
    }

    public function userHasRole(User $user, string $roleName): bool
    {
        return $user->hasRole($roleName);
    }

    public function userHasPermission(User $user, string $permissionName): bool
    {
        return $user->hasPermissionTo($permissionName);
    }
}
