<?php
namespace App\Domains\Room\Controllers;

use App\Domains\Authorization\Models\User;
use App\Domains\Authorization\Services\PermissionService;
use App\Domains\Room\Services\RoomService;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ParentRoomController extends Controller
{
    public function __construct(protected PermissionService $permissionService, protected RoomService $roomService)
    {}

    public function showChildrensLogs()
    {
        $user = auth()->user();
        $logs = $this->roomService->getRoomsForChild($user);
        // return dd($logs);
        return Inertia::render('Parent/ParentChildrenLogs', ['logs' => $logs]);

    }
}
