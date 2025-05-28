<?php
namespace App\Domains\Room\Models;

use App\Domains\Authorization\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Room extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'rooms'; // nes lentelė vadinasi rooms

    protected $fillable = [
        'user_id',
        'time_of_day',
        'comment',
        'analysis',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
