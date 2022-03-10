<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manga_ads extends Model
{
    use SoftDeletes;
    protected $table = 'manga_ads';

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

}
