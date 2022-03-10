<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
    	'name', 'description', 'status', 'user_created_id', 'user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

    public function admins(){
    	return $this->belongsToMany(\App\Admin::class);
    }

    public function permissions(){
    	return $this->belongsToMany(\App\Permission::class);
    }
}
