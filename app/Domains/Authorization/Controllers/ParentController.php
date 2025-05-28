<?php
namespace App\Domains\Authorization\Controllers;

use App\Domains\Authorization\Models\User;
use App\Domains\Authorization\Services\PermissionService;
use App\Domains\Room\Services\RoomService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParentController extends Controller
{
    public function __construct(protected PermissionService $permissionService, protected RoomService $roomService)
    {}

    public function addChild(Request $request)
    {
        // dd($request->all());
        $result = $this->permissionService->linkChildToParent(
            $request->child_email,
            $request->child_password
        );

        if ($result !== true) {
            // Grąžinam klaidą į formą
            return back()->withErrors(['child_password' => $result])->withInput();
        }

        return redirect()->route('parents_panel')->with('success', 'Vaikas sėkmingai priskirtas.');
    }
    public function showParentPanel()
    {
        // return dd(auth()->user());
        return Inertia::render('Parent/ParentPanel', ['children' => auth()->user()->children]);

    }
    public function schowAddChild()
    {
        return Inertia::render('Parent/AddChild');
    }
    // public function showChildrensLogs()
    // {
    //     $user = auth()->user();
    //     $logs = $this->roomService->getRoomsForChild($user);
    //     // return dd($logs);
    //     return Inertia::render('Parent/ParentChildrenLogs', ['logs' => $logs]);

    // }
}
