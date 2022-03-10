<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Chapter_picture extends Model
{
    protected $table = 'chapter_pictures';

    use SoftDeletes;
    use Notifiable;

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
