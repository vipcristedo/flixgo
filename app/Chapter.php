<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Chapter extends Model
{
    protected $table = 'chapters';

    use SoftDeletes;
    use Notifiable;

    public function Manga()
    {
        return $this->belongsTo(Manga::class);
    }

    public function pictures()
    {
        return $this->hasMany(Chapter_picture::class);
    }

    public function ads()
    {
        return $this->hasMany(Manga_ads::class);
    }

    public function manga_ads(){
        return $this->hasMany(\App\Manga_ad::class, 'object_id', 'id');
    }
}
