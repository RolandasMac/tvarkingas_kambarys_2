<?php
namespace App\Domains\Room\Controllers;

use App\Http\Controllers\Controller;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;

// PATAISYTA: Atkreipkite dėmesį į "Client" dalį namespace'e!
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image; // Reikalinga vaizdo objektui
use Illuminate\Http\Request;      // Reikalinga vienos nuotraukos užklausai
use Illuminate\Support\Facades\Log;

// Reikalinga paketinio apdorojimo užklausai

class RoomAnalysisController1 extends Controller
{
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
                'message' => 'Nepavyko nuskaityti nuotraukos turinio.',
            ], 500);
        }

        try {
            // Inicializuojame Vision AI klientą
            $imageAnnotator = new ImageAnnotatorClient();

            // 1. Sukuriame Image objektą iš nuotraukos turinio
            $image = new Image();
            $image->setContent($imageContent);

            // 2. Nurodome, kokias funkcijas norime atlikti (pvz., objekto lokalizavimą, etikečių detekciją)
            $features = [
                new \Google\Cloud\Vision\V1\Feature(['type' => Type::OBJECT_LOCALIZATION]),
                new \Google\Cloud\Vision\V1\Feature(['type' => Type::LABEL_DETECTION]),
            ];

            // 3. Sukuriame AnnotateImageRequest objektą vienai nuotraukai
            $annotateImageRequest = new AnnotateImageRequest();
            $annotateImageRequest->setImage($image);
            $annotateImageRequest->setFeatures($features);

            // 4. Sukuriame BatchAnnotateImagesRequest objektą, kuriame bus mūsų AnnotateImageRequest
            // Net jei apdorojame tik vieną nuotrauką, ji turi būti masyve, nes tai "batch" užklausa.
            $batchRequest = new BatchAnnotateImagesRequest();
            $batchRequest->setRequests([$annotateImageRequest]);

            // PATAISYTA: Kviečiame teisingą metodą - batchAnnotateImages()
            $response = $imageAnnotator->batchAnnotateImages($batchRequest);

            // Apdorojame atsakymą iš Vision AI
            $results = [];
            foreach ($response->getResponses() as $imageResponse) {
                if ($imageResponse->getError()) {
                    // Apdoroti klaidą, jei Vision API grąžino klaidą konkrečiai nuotraukai
                    Log::error('Vision AI klaida konkrečiai nuotraukai: ' . $imageResponse->getError()->getMessage());
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'Nepavyko apdoroti nuotraukos. Detalės: ' . $imageResponse->getError()->getMessage(),
                        'error_details' => 'Vision API grąžino klaidą konkrečiai nuotraukai.',
                    ], 500);
                }

                // Apdoroti objektų lokalizavimą
                $objects = [];
                foreach ($imageResponse->getLocalizedObjectAnnotations() as $object) {
                    $objects[] = [
                        'name'          => $object->getName(),
                        'score'         => $object->getScore(),
                        'bounding_poly' => $object->getBoundingPoly()->serializeToJsonString(), // Arba atskirai paimti vertices
                    ];
                }
                $results['objects'] = $objects;

                // Apdoroti etikečių detekciją
                $labels = [];
                foreach ($imageResponse->getLabelAnnotations() as $label) {
                    $labels[] = [
                        'description' => $label->getDescription(),
                        'score'       => $label->getScore(),
                    ];
                }
                $results['labels'] = $labels;
            }

            // Grąžiname sėkmingą atsakymą
            // return response()->json([
            //     'status'  => 'success',
            //     'message' => 'Nuotrauka apdorota sėkmingai.',
            //     'data'    => $results,
            // ]);
            $isTidy             = true;
            $messyItemsDetected = [];
            $tidyItemsDetected  = [];

            $messyKeywords = ['clothes on floor', 'unmade bed', 'dishes', 'clutter', 'shoes scattered']; // Pavyzdžiai
            $tidyKeywords  = ['organized', 'clean surface', 'arranged books'];                           // Pavyzdžiai

            foreach ($results['objects'] as $obj) {
                $nameLower = strtolower($obj['name']);
                if (in_array($nameLower, $messyKeywords) && $obj['score'] > 0.5) { // Su aukštu tikslumu
                    $isTidy               = false;
                    $messyItemsDetected[] = $obj['name'];
                }
                if (in_array($nameLower, $tidyKeywords) && $obj['score'] > 0.5) {
                    $tidyItemsDetected[] = $obj['name'];
                }
            }

            foreach ($results['labels'] as $label) {
                $descLower = strtolower($label['description']);
                if (in_array($descLower, $messyKeywords) && $label['score'] > 0.6) {
                    $isTidy               = false;
                    $messyItemsDetected[] = $label['description'];
                }
            }

            $analysisSummary = [
                'is_tidy'        => $isTidy,
                'messy_items'    => array_unique($messyItemsDetected), // Unikalūs aptikti netvarkingi elementai
                'tidy_items'     => array_unique($tidyItemsDetected),
                'overall_status' => $isTidy ? 'Kambarys atrodo tvarkingas!' : 'Kambaryje yra netvarkos.',
                'suggestions'    => [], // Galima generuoti pasiūlymus pagal aptiktus elementus
            ];

            if (! $isTidy) {
                // Generuoti pasiūlymus pagal $messyItemsDetected
                if (in_array('clothes on floor', $messyItemsDetected)) {
                    $analysisSummary['suggestions'][] = 'Surinkite drabužius nuo grindų.';
                }
                // ... ir t.t.
            }

// Grąžiname atnaujintus duomenis
            // return back()->with(
            return response()->json([
                'success'         => 'Nuotrauka apdorota sėkmingai!',
                'analysisSummary' => $analysisSummary, // Nauja: apibendrinta analizė
                'rawAnalysisData' => $results,         // Galite pasilikti ir neapdorotus duomenis, jei reikia
            ]);

        } catch (\Exception $e) {
            // Bendras klaidos tvarkymas
            Log::error('Vision AI klaida: ' . $e->getMessage());
            return response()->json([
                'status'        => 'error',
                'message'       => 'Nepavyko apdoroti nuotraukos. Bandykite dar kartą.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }
}
