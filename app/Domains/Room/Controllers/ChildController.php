<?php
namespace App\Domains\Room\Controllers;

use App\Domains\Authorization\Models\User;
use App\Domains\Authorization\Services\PermissionService;
use App\Domains\Room\Services\RoomService;
use App\Http\Controllers\Controller;
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
        // return dd($rooms);
        return Inertia::render('Child/ChildPanel', ['logs' => $logs]);

    }
    public function showSendPhotoPage()
    {
        return Inertia::render('Child/SendPhoto');
    }
    // public function sendPhoto(StoreRoomRequest $request, VisionService $visionService)
    // {
    //     // $getData = $request->all();

    //     // // Reikia sukurti Queue įvykį
    //     // SendPhotoLogJob::dispatch([$getData['user_id']]);

    //     // return dd([$getData['user_id']]);

    //     // $this->roomService->create($request->validated());

    //     // return dd("Gaidys");
    //     // return Inertia::render('Child/ChildLogs', ['logs' => $logs]);

    //     $request->validate([
    //         'photo' => 'required|image|max:5120', // iki 5MB
    //     ]);

    //     // Išsaugom laikinai nuotrauką
    //     $path = $request->file('photo')->store('temp');

    //     $fullPath = storage_path('\app\public\20\123.jpg');

    //     // return dd($fullPath);
    //     // Siunčiam į Vision
    //     if (! file_exists($fullPath)) {
    //         Log::error("Failas nerastas: $fullPath");
    //         abort(404, 'Failas nerastas');
    //     }
    //     // $visionService = new VisionService();
    //     // $result        = $visionService->analyzeRoomCleanliness($fullPath);
    //     // return dd($result);
    //     // // Pašalinam failą
    //     // unlink($fullPath);

    //     // // return response()->json($result);
    //     // return dd($result);
    //     // Tarkim nuotrauka įkelta ir saugoma /storage/app/photos/photo.jpg

    //     $comment = $this->cleanlinessService->analyzeRoom($fullPath);
    //     return dd($comment);
    //     return response()->json([
    //         'comment' => $comment,
    //     ]);
    // }

}
