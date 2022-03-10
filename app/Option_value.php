<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Option_value extends Model
{
    protected $table = 'option_values';

    use SoftDeletes;

    public function option(){
        return $this->belongsTo(Option::class);
    }
}
