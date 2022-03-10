<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Movie extends Model
{
    protected $table = 'movies';

    protected $fillable = [
    	'name','description','nominations','card_cover','age','slug','genre','runtime','release_year','quality','country','rate','user_created_id','user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

    public function tags(){
    	return $this->belongsToMany(\App\Tag::class);
    }

    public function types(){
    	return $this->belongsToMany(\App\Type::class);
    }

    public function videos()
	{
		return $this->hasMany(\App\Video::class);
	}

	public function sources()
	{
		return $this->hasMany(\App\Source::class);
	}

	public function playlists()
	{
		return $this->hasMany(\App\Playlist::class);
	}
}
