<?php

namespace App\Models;

use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSetting extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
		'id',
        'user_id',
        'language_id',
    ];


	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function language() 
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

}