<?php
namespace App\Domains\Authorization\Services;

use App\Domains\Authorization\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function linkChildToParent(string $childEmail, string $childPassword)
    {
        $child = User::where('email', $childEmail)->first();

        if (! $child) {
            return 'Vaikas nerastas';
        }

        if (! Hash::check($childPassword, $child->password)) {
            return 'Neteisingas slaptažodis';
        }

        $parent = Auth::user();

        $child->parent_id = $parent->id;
        $child->save();

        // Priskiriame roles per spatie/permission
        $child->syncRoles(['child']);
        $parent->syncRoles(['parent']);

        return true;
    }
}
