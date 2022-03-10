<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Source extends Model
{
    protected $table = 'sources';

    protected $fillable = [
    	'source_key', 'video_id', 'movie_id', 'channel_id', 'language', 'prioritize', 'status', 'user_created_id', 'user_updated_id',
    ];

    use SoftDeletes;

    public function moive(){
    	return $this->belongsTo(\App\Movie::class);
    }

    public function channel(){
    	return $this->belongsTo(\App\Channel::class);
    }

    public function video(){
    	return $this->belongsTo(\App\Video::class);
    }
}
