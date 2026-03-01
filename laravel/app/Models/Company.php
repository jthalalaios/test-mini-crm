<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class Company extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    public $timestamps = true;

	protected $fillable = [
		'name',
		'email',
		'website',
		'email',
        'deleted_at'
	];

    public function searchable_fields()
	{
		return [
			'name',
			'email', 
			'website',
            'created_at',
		];
	}

	public function sortable(string $field_key, $query, string $order)
	{
		$field_name = (Schema::hasColumn($this->getTable(), $field_key) ? $field_key : null);
		if (!$field_name) return $query; 

		$query->orderBy($field_key, $order);
		return $query;
	}

	/* public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    } */

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

