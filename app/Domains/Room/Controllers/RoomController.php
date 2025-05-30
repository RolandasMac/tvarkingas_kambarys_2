<?php
namespace App\Domains\Room\Controllers;

use App\Domains\Room\Requests\StoreRoomRequest;
use App\Domains\Room\Services\RoomService;
use App\Domains\Room\Services\RoomsLogsExport;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

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
    public function exportRoomsLogsCsv()
    {
        // Patikriname leidimą eksportuoti vartotojus (pvz., tik administratoriams)
        // if (!auth()->user()->hasRole('admin')) {
        //     abort(403, 'Neturite teisių eksportuoti vartotojus.');
        // }

        return Excel::download(new RoomsLogsExport, 'roomLogs.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
