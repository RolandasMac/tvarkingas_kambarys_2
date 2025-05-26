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

Route::middleware(['auth', 'role:parent'])->prefix('parent')->group(function () {

    Route::get('/parents_panel', [UserController::class, 'showParentPanel'])->name('parents_panel');
    Route::post('/add-child', [UserController::class, 'addChild'])->name('add-child');
    Route::get('/show-add-child', [UserController::class, 'schowAddChild'])->name('show-add-child');

});
