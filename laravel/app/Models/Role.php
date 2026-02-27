<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
	public $timestamps = true;

	protected $fillable = [
		'name',
		'slug'
	];

	public function searchable_fields()
	{
		return [
			'id',
			'name',
			'slug',
		];
	}

	public function sortable(string $field_key, $query, string $order)
	{
		$field_name = Schema::hasColumn($this->getTable(), $field_key) ? $field_key : null;
		if (!$field_name) return $query;
	
		$query->orderBy("roles.$field_key", $order);
		return $query;
	}

	public function users()
	{
		return $this->belongsToMany('App\Models\User', 'users_roles');
	}
}
