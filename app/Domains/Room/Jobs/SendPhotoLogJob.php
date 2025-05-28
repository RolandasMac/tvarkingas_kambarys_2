<?php
namespace App\Domains\Room\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendPhotoLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $photoData;

    public function __construct(array $photoData)
    {
        $this->photoData = $photoData;
    }

    public function handle()
    {
        // Logika, pvz. išsaugoti arba siųsti
        Log::info("User id:" . $this->photoData[0]);
    }
}
