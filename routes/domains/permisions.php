<?php

use App\Domains\Authorization\Controllers\ParentController;
use App\Domains\Authorization\Controllers\UserController;
use App\Domains\Room\Controllers\ChildController;
use App\Domains\Room\Controllers\ParentRoomController;

// Route::get('/setup-roles', [UserController::class, 'setup']);
// Route::get('/assign-role/{user}/{role}', [UserController::class, 'assign']);

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Route::inertia('/assign-role-ui', 'Admin/AssignRole');
    Route::get('/assign-role-ui', [UserController::class, 'assignRoleUi'])->name('assign-role-ui');
    // Route::get('/users-roles-data', [UserController::class, 'usersAndRoles']);
    Route::post('/assign-role', [UserController::class, 'assign'])->name('assign-role');
    // Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/toggle-block', [UserController::class, 'toggleBlock'])->name('toggleBlock');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent')->group(function () {

    Route::get('/parents_panel', [ParentController::class, 'showParentPanel'])->name('parents_panel');
    Route::post('/add-child', [ParentController::class, 'addChild'])->name('add-child');
    Route::get('/show-add-child', [ParentController::class, 'schowAddChild'])->name('show-add-child');
    Route::get('/show-childrens-logs', [ParentRoomController::class, 'showChildrensLogs'])->name('show-childrens-logs');

});

Route::middleware(['auth', 'role:child'])->prefix('child')->group(function () {

    Route::get('/child_panel', [ChildController::class, 'showChildPanel'])->name('show_child_panel');
    Route::post('/send-photo', [ChildController::class, 'sendPhoto'])->name('send_photo');
    Route::get('/show-sendPhoto-page', [ChildController::class, 'showSendPhotoPage'])->name('show-sendPhoto-page');
    // Route::post('/room-analysis', [RoomAnalysisController::class, 'analyze'])->name('send_photo');
    Route::post('/room-analysis', [ChildController::class, 'sendPhoto'])->name('send_photo');

});
