<?php
namespace App\Domains\Room\Services;

use App\Domains\Room\Actions\AnalyzeRoomImageAction;
use App\Domains\Room\Jobs\SendPhotoLogJob;
use App\Domains\Room\Models\Room;
use Illuminate\Support\Facades\Request;

class RoomService
{
    public function uploadAndAnalyze($userId, $image, $timeOfDay, $comment = null): Room
    {
        $room = Room::create([
            'user_id'     => $userId,
            'time_of_day' => $timeOfDay,
            'comment'     => $comment,
        ]);

        $room->addMedia($image)->toMediaCollection('photos');

        // Analizė (foninis job arba inline veiksmas)
        $analysis = app(AnalyzeRoomImageAction::class)->execute($room);

        $room->update([
            'analysis' => $analysis,
        ]);

        return $room;
    }
    public function getAll()
    {
        return Room::latest()->get();
    }

    public function find(int $id): Room
    {
        return Room::findOrFail($id);
    }

    public function create(array $data): Room
    {

        // Sukuriam Room įrašą
        $room = Room::create([
            'user_id'     => auth()->id(),
            'time_of_day' => $data['time_of_day'],
            'comment'     => $data['comment'],
            'analysis'    => null, // pildysiu vėliau
        ]);

        // Pridedame failą prie media library
        $media    = $room->addMediaFromRequest('photo')->toMediaCollection('photos');
        $filePath = $media->getPath();
        $mediaId  = $media->id;
        $userId   = auth()->id();

        // Išsiunčiame duomenis ir failo kelią į Job'ą
        SendPhotoLogJob::dispatch([
            'room_id'     => $room->id, // Perduodame kambario ID, jei reikės atnaujinti analysis lauką
            'media_id'    => $mediaId,  // Perduodame media ID
            'file_path'   => $filePath, // Perduodame failo kelią
            'user_id'     => $userId,
            'time_of_day' => $data['time_of_day'],
            'comment'     => $data['comment'],
        ]);

        return $room;
    }

    public function delete(int $id): bool
    {
        $room = Room::findOrFail($id);
        return $room->delete();
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        // Sukuriam Room įrašą
        // $room = Room::create([
        //     // 'user_id'     => auth()->id(),
        //     'time_of_day' => $request->input('time_of_day'),
        //     'comment'     => $request->input('comment'),
        //     'user_id'     => 1,
        //     'analysis'    => null, // pildysime vėliau
        // ]);

        // // Pridedame failą prie media library
        // $room->addMediaFromRequest('photo')->toMediaCollection('photos');

        return response()->json(['message' => 'Nuotrauka įkelta sėkmingai']);
    }
    public function getRoomsForUser($user)
    {
        return $user->roomLogs()->get();
    }
    public function getRoomsForChild($user)
    {
        return $user->children()->with('roomLogs')->get()->map(function ($child) {
            return [
                'user' => $child,
                'logs' => $child->roomLogs,
            ];
        });
    }
}
