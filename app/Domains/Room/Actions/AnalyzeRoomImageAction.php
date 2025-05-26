<?php
namespace App\Domains\Room\Actions;

use App\Domains\Room\Models\Room;

class AnalyzeRoomImageAction
{
    public function execute(Room $room): array
    {
        // Čia gali būti AI analizės integracija su OpenAI / imitacija
        return [
            "Ant grindų žaislai",
            "Nepaklota lova",
            "Drabužiai ant kėdės",
        ];
    }
}
