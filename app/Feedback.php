<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	protected $fillable = ['key','value','json'];
	protected $guarded = [];
    //
}
