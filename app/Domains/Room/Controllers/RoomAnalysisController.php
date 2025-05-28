<?php
namespace App\Domains\Room\Controllers;

use App\Domains\Room\Services\RoomAnalysisService;
use App\Http\Controllers\Controller; // Import the service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomAnalysisController extends Controller
{
    protected $roomAnalysisService;

    // Constructor to inject the service
    public function __construct(RoomAnalysisService $roomAnalysisService)
    {
        $this->roomAnalysisService = $roomAnalysisService;
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageFile    = $request->file('photo');
        $imageContent = file_get_contents($imageFile->getRealPath());

        if (! $imageContent) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to read photo content.',
            ], 500);
        }

        try {
            // Delegate the analysis to the service
            $analysisResult = $this->roomAnalysisService->analyzeImage($imageContent);

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
    }
}
