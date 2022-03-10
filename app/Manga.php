<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Manga extends Model
{
    protected $table = 'mangas';

    use SoftDeletes;

    public function Types()
	{
		return $this->belongsToMany(Type::class);
	}

	public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function manga_ads(){
        return $this->hasMany(\App\Manga_ads::class, 'object_id', 'id');
    }
}
