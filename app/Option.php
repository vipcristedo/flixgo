<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Option extends Model
{
    protected $table = 'options';

    protected $fillable = [
    	'name', 'description', 'status', 'user_created_id', 'user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

    public function optionValue(){
        return $this->hasMany(Option_value::class);
    }
}
