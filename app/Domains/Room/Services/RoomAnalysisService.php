<?php
namespace App\Domains\Room\Services;

use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image;
use Illuminate\Support\Facades\Log;

class RoomAnalysisService
{
    protected $imageAnnotatorClient;

    public function __construct()
    {
        // Initialize the Vision AI client in the service constructor
        $this->imageAnnotatorClient = new ImageAnnotatorClient();
    }

    public function analyzeImage(string $imageContent): array
    {
        // 1. Create an Image object from the photo content
        $image = new Image();
        $image->setContent($imageContent);

        // 2. Specify the features to perform (e.g., object localization, label detection)
        $features = [
            new \Google\Cloud\Vision\V1\Feature(['type' => Type::OBJECT_LOCALIZATION]),
            new \Google\Cloud\Vision\V1\Feature(['type' => Type::LABEL_DETECTION]),
        ];

        // 3. Create an AnnotateImageRequest object for a single photo
        $annotateImageRequest = new AnnotateImageRequest();
        $annotateImageRequest->setImage($image);
        $annotateImageRequest->setFeatures($features);

        // 4. Create a BatchAnnotateImagesRequest object containing our AnnotateImageRequest
        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$annotateImageRequest]);

        // Call the correct method - batchAnnotateImages()
        $response = $this->imageAnnotatorClient->batchAnnotateImages($batchRequest);

        $results = [];
        foreach ($response->getResponses() as $imageResponse) {
            if ($imageResponse->getError()) {
                Log::error('Vision AI error for specific image: ' . $imageResponse->getError()->getMessage());
                throw new \Exception('Failed to process image: ' . $imageResponse->getError()->getMessage());
            }

            // Process object localization
            $objects = [];
            foreach ($imageResponse->getLocalizedObjectAnnotations() as $object) {
                $objects[] = [
                    'name'          => $object->getName(),
                    'score'         => $object->getScore(),
                    'bounding_poly' => json_decode($object->getBoundingPoly()->serializeToJsonString(), true),
                ];
            }
            $results['objects'] = $objects;

            // Process label detection
            $labels = [];
            foreach ($imageResponse->getLabelAnnotations() as $label) {
                $labels[] = [
                    'description' => $label->getDescription(),
                    'score'       => $label->getScore(),
                ];
            }
            $results['labels'] = $labels;
        }

        // Analyze tidiness based on detected objects and labels
        $isTidy             = true;
        $messyItemsDetected = [];
        $tidyItemsDetected  = [];

        $messyKeywords = [
            'clothes on floor', 'unmade bed', 'dishes', 'clutter',
            'shoes scattered', 'trash', 'mess', 'dirty',
            'pile of clothes', 'books scattered', 'magazines scattered',
            'toys scattered', 'food crumbs', 'spill', 'dusty',
            'empty bottles', 'newspapers', 'random objects', 'junk',
            'disorder', 'chaotic', 'untidy', 'unorganized', 'rubbish',
            'waste', 'litter',
        ];
        $tidyKeywords = [
            'organized', 'clean surface', 'arranged books', 'neat',
            'tidy', 'clean', 'minimalist', 'orderly', 'spotless',
            'well-arranged', 'pristine',
        ];

        foreach ($results['objects'] as $obj) {
            $nameLower = strtolower($obj['name']);
            if (in_array($nameLower, $messyKeywords) && $obj['score'] > 0.5) {
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
            if (in_array($descLower, $tidyKeywords) && $label['score'] > 0.6) {
                $tidyItemsDetected[] = $label['description'];
            }
        }

        $analysisSummary = [
            'is_tidy'        => $isTidy,
            'messy_items'    => array_unique($messyItemsDetected),
            'tidy_items'     => array_unique($tidyItemsDetected),
            'overall_status' => $isTidy ? 'The room looks tidy!' : 'There\'s some clutter in the room.',
            'suggestions'    => [],
        ];

        if (! $isTidy) {
            // Generuoti pasiūlymus pagal aptiktus netvarkingus elementus
            if (in_array('clothes on floor', $analysisSummary['messy_items']) || in_array('pile of clothes', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Pick up clothes from the floor and put them away.';
            }
            if (in_array('unmade bed', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Make the bed.';
            }
            if (in_array('dishes', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Wash the dishes or put them away.';
            }
            if (in_array('clutter', $analysisSummary['messy_items']) || in_array('random objects', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Tidy up general clutter and put items in their designated places.';
            }
            if (in_array('shoes scattered', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Arrange shoes neatly or put them in a shoe rack.';
            }
            if (in_array('trash', $analysisSummary['messy_items']) || in_array('rubbish', $analysisSummary['messy_items']) || in_array('waste', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Empty the trash bin.';
            }
            if (in_array('books scattered', $analysisSummary['messy_items']) || in_array('magazines scattered', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Organize books and magazines on shelves.';
            }
            if (in_array('toys scattered', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Put toys away in their designated storage.';
            }
            if (in_array('dusty', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Wipe down dusty surfaces.';
            }
            if (in_array('empty bottles', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Dispose of empty bottles properly.';
            }
            if (in_array('newspapers', $analysisSummary['messy_items'])) {
                $analysisSummary['suggestions'][] = 'Neatly stack or recycle old newspapers.';
            }
            // Galima pridėti daugiau pasiūlymų pagal naujus raktinius žodžius
        }

        return [
            'analysisSummary' => $analysisSummary,
            'rawAnalysisData' => $results,
        ];
    }

    public function __destruct()
    {
        // Close the client when the service is destroyed
        if ($this->imageAnnotatorClient) {
            $this->imageAnnotatorClient->close();
        }
    }
}
