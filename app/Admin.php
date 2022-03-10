<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
    	'name', 'email', 'phone', 'address', 'is_active', 'password', 'user_created_id','user_updated_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    use Notifiable;
    use SoftDeletes;

    public function roles(){
    	return $this->belongsToMany(\App\Role::class);
    }
}
