<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes;

	public $timestamps = true;

	protected $fillable = [
		'name',
        'locale',
		'enabled',
		'default',
	];

 	public function user_settings()
	{
		return $this->belongsToMany('App\Models\UserSettings');
	}
}
