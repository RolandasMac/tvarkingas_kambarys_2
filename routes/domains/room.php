<?php
use App\Domains\Room\Controllers\RoomController;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/room/upload', [RoomController::class, 'store']);
// });

// Route::get('/room', [RoomController::class, 'index']);

Route::middleware(['auth', 'role:child|parent|admin'])->group(function () {
    Route::resource('rooms', RoomController::class)->only([
        'index', 'create', 'store', 'show', 'destroy',
    ])->names('rooms');

});
Route::post('/roomsanalyze', [RoomController::class, 'analyze'])->name('rooms.analyze');
