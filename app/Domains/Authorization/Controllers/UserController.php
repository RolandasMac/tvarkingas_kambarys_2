<?php
namespace App\Domains\Authorization\Controllers;

use App\Domains\Authorization\Models\User;
use App\Domains\Authorization\Services\PermissionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected PermissionService $permissionService)
    {}

    // public function setup()
    // {
    //     $this->permissionService->createRole('child');
    //     $this->permissionService->createRole('parent');

    //     $this->permissionService->createPermission('upload room photo');
    //     $this->permissionService->createPermission('view reports');

    //     $this->permissionService->assignPermissionToRole('upload room photo', 'child');
    //     $this->permissionService->assignPermissionToRole('view reports', 'parent');

    //     return response()->json(['message' => 'Roles and permissions created.']);
    // }

    public function assign(Request $request)
    {
        // return dd($request);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role'    => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $this->permissionService->assignRoleToUser($user, $validated['role']);

        return response()->json(['message' => "Role '{$validated['role']}' assigned to user {$user->name}."]);
    }

    // public function usersAndRoles()
    // {
    //     return response()->json([
    //         'users' => User::select('id', 'name', 'email', 'is_blocked')->get(),
    //         'roles' => Role::pluck('name'),
    //     ]);
    // }
    public function assignRoleUi()
    {
        $users = User::select('id', 'name', 'email', 'is_blocked')->get();
        $roles = Role::pluck('name');

        return Inertia::render('Admin/AssignRole', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
    // Funkcija, skirta blokuoti/atblokuoti vartotoją
    public function toggleBlock(Request $request)
    {
        $userId = $request->input('userId');

        // $userId = $request->userId; // Arba šis būdas, jei siunčiamas JSON
        $user = User::findOrFail($userId);
        // Patikriname leidimą blokuoti vartotojus
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Neturite teisių blokuoti/atblokuoti vartotojus.');
        }

        // Negalima blokuoti savęs!
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Negalite blokuoti savo paskyros.');
        }

        $user->is_blocked = ! $user->is_blocked; // Pakeičiame statusą

        $user->save();

        $status = $user->is_blocked ? 'blokuota' : 'atblokuota';

        // return back()->with('success', "Vartotojo {$user->name} paskyra buvo {$status}.");
        $users = User::orderBy('name')->get();

        // return Inertia::render('Admin/AssignRole', [
        //     'updatedUsers' => $users,
        // ]);
        return back()->with('success', "Vartotojo {$user->name} paskyra buvo {$status}.");
    }
}
