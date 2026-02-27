<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

	/* public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    } */

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

