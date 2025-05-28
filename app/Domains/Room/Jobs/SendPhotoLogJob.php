<?php
namespace App\Domains\Room\Jobs;

use App\Domains\Room\Models\Room;
use App\Domains\Room\Services\RoomAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPhotoLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $Data;

    public function __construct(array $Data)
    {
        $this->Data = $Data;
    }

    public function handle(RoomAnalysisService $roomAnalysisService)
    {
        // $userId    = $this->Data['user_id'];
        // $comment   = $this->Data['comment'];
        // $timeOfDay = $this->Data['time_of_day'];
        $filePath = $this->Data['file_path'];
        $roomId   = $this->Data['room_id'];
        Log::info("Room Id:" . $roomId);

        $imageContent = file_get_contents($filePath);
        if (! $imageContent) {
            // Reikia kažkokio notifer, kad frontas gautų pranešimą
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to read photo content.',
            ], 500);
        }
        Log::info("File path:" . $filePath);
        // Reikia siųsti failą į google Vision.

        // Visas controller********************
        // $request->validate([
        //     'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        // $imageFile    = $request->file('photo');
        // $imageContent = file_get_contents($imageFile->getRealPath());

        // if (! $imageContent) {
        //     // Reikia kažkokio notifer, kad frontas gautų pranešimą
        //     return response()->json([
        //         'status'  => 'error',
        //         'message' => 'Failed to read photo content.',
        //     ], 500);
        // }
        // return dd($imageContent);
        // ***********************************
        try {
            // Perduodame į servisą
            Log::info('Žinutė prieš siuntimą');
            $analysisResult = $roomAnalysisService->analyzeImage($imageContent);
            Log::info('Žinutė po siuntimo');
            Log::info('Vision AI atsakymas: ' . json_encode($analysisResult));
            // ********room lentelės papildymas
            // Pirmiausia, randame (retrieve) tą patį įrašą pagal ID, kurį gavome iš kontrolerio
            $room = Room::find($roomId);

            if (! $room) {
                // Svarbu patikrinti, ar įrašas buvo rastas
                Log::error("AnalyzeRoomImage Job: Room with ID {$roomId} not found.");
                return; // Jei nerasta, nieko nedarome
            }

            // Dabar atnaujiname **rastą** 'room' įrašą su analizės duomenimis
            $room->update([
                'analysis_summary'  => $analysisResult['analysisSummary'],
                'raw_analysis_data' => $analysisResult['rawAnalysisData'],
                'analyzed_at'       => now(),
            ]);

            Log::info("Room analysis completed for Room ID {$roomId}. Status: {$analysisResult['analysisSummary']['overall_status']}");
            // ********room lentelės papildymas*************************************************

            return response()->json([
                'success'         => 'Photo processed successfully!',
                'analysisSummary' => $analysisResult['analysisSummary'],
                'rawAnalysisData' => $analysisResult['rawAnalysisData'],
            ]);

        } catch (\Exception $e) {
            Log::error('Vision AI error: ' . $e->getMessage());
            return response()->json([
                'status'        => 'error',
                'message'       => 'Failed to process photo. Please try again.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
        // *********************************************
    }

}
