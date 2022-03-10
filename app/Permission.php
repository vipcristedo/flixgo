<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
    	'name', 'display_name', 'description', 'status', 'user_created_id', 'user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;
    
    public function roles(){
    	return $this->belongsToMany(\App\Role::class);
    }
}
