<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Type extends Model
{
    use SoftDeletes;
	protected $table = 'types';

	public function movies(){
		return $this->belongsToMany(\App\Movie::class);
	}

	public function mangas(){
		return $this->belongsToMany(Manga::class);
	}
}
