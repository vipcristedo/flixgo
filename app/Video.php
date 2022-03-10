<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Video extends Model
{
	protected $table='videos';

	protected $fillable = [
    	'title', 'tags', 'description', 'movies_id', 'playlist_id', 'status', 'slug', 'user_created_id', 'user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

	public function movie()
	{
		return $this->belongsTo(\App\Type::class);
	}
	public function sources()
	{
		return $this->hasMany(\App\Source::class);
	}
	public function playlist()
	{
		return $this->belongsTo(\App\Playlist::class);
	}
}
