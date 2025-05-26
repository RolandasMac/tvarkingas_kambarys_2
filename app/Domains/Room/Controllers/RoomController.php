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
        return redirect()->route('rooms.index')->with('success', 'Kambarys sukurtas.');
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
    // public function analyze(Request $request)
    // {
    //     return dd($request->all());
    //     $request->validate([
    //         'photo' => 'required|image|max:5120', // iki 5MB
    //     ]);

    //     $image  = $request->file('photo');
    //     $base64 = base64_encode(file_get_contents($image->getRealPath()));
    //     $mime   = $image->getMimeType(); // pvz. image/jpeg

    //     $response = Http::withToken(env('OPENAI_API_KEY'))
    //         ->post('https://api.openai.com/v1/chat/completions', [
    //             'model'      => 'gpt-4-vision-preview',
    //             'messages'   => [[
    //                 'role'    => 'user',
    //                 'content' => [
    //                     ['type' => 'text', 'text' => 'Apibūdink kas yra šioje nuotraukoje.'],
    //                     ['type' => 'image_url', 'image_url' => ['url' => "data:$mime;base64,$base64"]],
    //                 ],
    //             ]],
    //             'max_tokens' => 500,
    //         ]);

    //     return response()->json($response->json()['choices'][0]['message']['content']);
    // }

    // public function analyze(Request $request)
    // {
    //     $request->validate([
    //         'photo' => 'required|image|max:5120',
    //     ]);

    //     // Įrašom failą
    //     $path = $request->file('photo')->store('photos');

    //     // Nuskaitom failą
    //     // $imageData = file_get_contents(storage_path("app/{$path}"));
    //     $imageData = file_get_contents(Storage::path($path));
    //     // return dd($imageData);

    //     // Nustatom Google Credentials
    //     putenv('GOOGLE_APPLICATION_CREDENTIALS=' . storage_path('app/google/vision.json'));

    //     // Kuriam klientą
    //     $vision = new \Google\Cloud\Vision\V1\Client\ImageAnnotatorClient();

    //     // Siunčiam vaizdą analizei
    //     $response = $vision->labelDetection($imageData);
    //     $labels   = $response->getLabelAnnotations();

    //     $results = [];

    //     if ($labels) {
    //         foreach ($labels as $label) {
    //             $results[] = [
    //                 'description' => $label->getDescription(),
    //                 'score'       => round($label->getScore(), 2),
    //             ];
    //         }
    //     }

    //     $vision->close();

    //     // return response()->json([
    //     //     'file'   => $path,
    //     //     'labels' => $results,
    //     // ]);

    //     return dd($results, $path);
    // }

    // public function analyze(Request $request, GeminiService $gemini)
    // {
    //     $request->validate([
    //         'photo' => 'required|image|max:5120', // max 5MB
    //     ]);

    //     $path = $request->file('photo')->store('photos', 'public');

    //     $localPath = storage_path('app/public/' . $path);
    //     $result    = $gemini->analyzeImage($localPath, 'Apibūdink kambario tvarką');

    //     return response()->json([
    //         'analysis' => $result,
    //     ]);
    // }
    // public function analyze(Request $request)
    // {
    //     $image = $request->file('photo');

    //     if (! $image) {
    //         return response()->json(['error' => 'No image uploaded'], 400);
    //     }

    //     // Konvertuojam į base64
    //     $imageData = base64_encode(file_get_contents($image->getRealPath()));
    //     $mimeType  = $image->getMimeType(); // pvz. "image/jpeg"

    //     $client = new Client();
    //     $apiKey = env('GEMINI_API_KEY');
    //     $model  = 'gemini-2.5-flash-preview-0513';

    //     // Sudarom Gemini užklausą
    //     $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
    //         'headers' => [
    //             'Content-Type' => 'application/json',
    //         ],
    //         'json'    => [
    //             'contents' => [
    //                 [
    //                     'parts' => [
    //                         [
    //                             'inlineData' => [
    //                                 'mimeType' => $mimeType,
    //                                 'data'     => $imageData,
    //                             ],
    //                         ],
    //                         [
    //                             'text' => 'Apibūdink šio kambario būklę ir ar jis tvarkingas.',
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //         ],
    //     ]);

    //     $result = json_decode($response->getBody(), true);
    //     return response()->json($result);
    // }

}
