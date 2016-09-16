<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
	protected $fillable = ['mykey','value','json'];
	protected $guarded = [];
    //
}
