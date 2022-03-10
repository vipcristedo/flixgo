<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;


class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
    	'name','slug','user_created_id','user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

    public function movies(){
    	return $this->belongsToMany(\App\Movie::class);
    }
}
