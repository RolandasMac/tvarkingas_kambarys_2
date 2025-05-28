<?php
namespace App\Domains\Room\Controllers;

use App\Domains\Authorization\Models\User;
use App\Domains\Authorization\Services\PermissionService;
use App\Domains\Room\Requests\StoreRoomRequest;
use App\Domains\Room\Services\RoomService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class ChildController extends Controller
{

    public function __construct(protected PermissionService $permissionService, protected RoomService $roomService)
    {

    }

    public function showChildPanel()
    {
        $user = auth()->user();
        $logs = $this->roomService->getRoomsForUser($user);
        // return dd($logs);
        return Inertia::render('Child/ChildPanel', ['logs' => $logs]);

    }
    public function showSendPhotoPage()
    {
        return Inertia::render('Child/SendPhoto');
    }
    public function sendPhoto(StoreRoomRequest $request)
    {
        // $getData = $request->all();
        $validatedData            = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;
        $sendData                 = Arr::only($validatedData, ['time_of_day', 'comment', 'user_id']);
        // return dd($request->validated());
        $this->roomService->create($sendData);

        return redirect()->route('show_child_panel');
        // return Inertia::render('Child/ChildPanel', ['logs' => $logs]);

    }

}
