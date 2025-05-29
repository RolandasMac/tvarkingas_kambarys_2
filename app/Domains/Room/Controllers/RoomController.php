<?php
namespace App\Domains\Room\Controllers;

use App\Domains\Room\Requests\StoreRoomRequest;
use App\Domains\Room\Services\RoomService;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function __construct(protected RoomService $roomService)
    {}

    public function index()
    {
        $rooms = $this->roomService->getAll();
        return Inertia::render('Room/Index', compact('rooms'));
    }

    public function create()
    {
        return Inertia::render('Room/Create');
    }

    public function store(StoreRoomRequest $request)
    {
        // return dd($request->validated());
        $this->roomService->create($request->validated());
        return redirect()->route('rooms.index');
        // return Inertia::render('Test/Test');
    }

    public function show(int $id)
    {
        $room      = $this->roomService->find($id);
        $photo_url = $room->getFirstMediaUrl('photos');
        $res       = [
            'room'      => $room,
            'photo_url' => $photo_url,
        ];
        return Inertia::render('Room/Show', compact('res'));
    }

    public function destroy(int $id)
    {
        $this->roomService->delete($id);
        return redirect()->route('rooms.index')->with('success', 'Kambarys ištrintas.');
    }
}
