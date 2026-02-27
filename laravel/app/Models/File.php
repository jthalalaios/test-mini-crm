<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
	use HasFactory, SoftDeletes;
	public $timestamps = true;

	protected $fillable = [
		'file_name',
		'file_type',
		'file_size',
		'file_path',
		'default',
		'path',
		'resolution',
        'user_id',
		'foreign_id',
		'deleted_at'
	];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}


