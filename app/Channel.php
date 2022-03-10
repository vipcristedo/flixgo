<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Channel extends Model
{
    protected $table = 'channels';

    protected $fillable = [
        'title', 'link', 'description', 'status', 'user_created_id', 'user_updated_id',
    ];

    use Notifiable;
    use SoftDeletes;

    public function sources()
	{
		return $this->hasMany(Source::class);
	}
}
