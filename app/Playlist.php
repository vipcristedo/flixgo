<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Playlist extends Model
{
    protected $table = 'playlists';

    protected $fillable = [
        'title','description','status','user_created_id','user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

    public function movies(){
        return $this->belongsToMany(\App\Backend\Movie::class);
    }

    public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function videos()
	{
		return $this->hasMany(Video::class);
	}
}
