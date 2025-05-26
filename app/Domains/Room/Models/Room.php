<?php
namespace App\Domains\Room\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Room extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['user_id', 'time_of_day', 'comment', 'analysis'];
}
