<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Manga_ad extends Model
{
    protected $table = 'manga_ads';
    
    protected $fillable =[
    	'object_id', 'table_name', 'link', 'artical', 'user_created_id', 'user_updated_id'
    ];

    use SoftDeletes;
    use Notifiable;

    public function manga(){
    	return $this->belongsTo(\App\Manga::class, 'id', 'object_id');
    }

    public function chapter(){
    	return $this->belongsTo(\App\Chapter::class, 'id', 'object_id');
    }
}
