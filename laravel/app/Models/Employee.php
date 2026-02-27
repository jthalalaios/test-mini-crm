<?php

namespace App\Models;

use App\Models\Company as ModelsCompany;
use Faker\Provider\Company;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = true;

	protected $fillable = [
		'first_name',
		'last_name',
		'company_id',
		'email',
        'phone',
        'deleted_at'
	];
    
    public function company()
    {
        return $this->belongsTo(ModelsCompany::class, 'company_id');
    }
}
