<?php

use App\Domains\Authorization\Controllers\UserController;

Route::get('/setup-roles', [UserController::class, 'setup']);
Route::get('/assign-role/{user}/{role}', [UserController::class, 'assign']);

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Route::inertia('/assign-role-ui', 'Admin/AssignRole');
    Route::get('/assign-role-ui', [UserController::class, 'assignRoleUi'])->name('assign-role-ui');
    // Route::get('/users-roles-data', [UserController::class, 'usersAndRoles']);
    Route::post('/assign-role', action: [UserController::class, 'assign'])->name('assign-role');
});
