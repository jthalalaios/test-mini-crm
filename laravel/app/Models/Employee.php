<?php

namespace App\Models;


use App\Models\Company as ModelsCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Employee extends Model
{
    use HasFactory;
    
    public $timestamps = true;

	protected $fillable = [
		'first_name',
		'last_name',
		'company_id',
		'email',
        'phone',
        'deleted_at'
	];

    	public function searchable_fields()
	{
		return [
			'company_name', 
			'first_name',
			'last_name', 
            'email',
            'phone',
            'created_at',
		];
	}

	public function sortable(string $field_key, $query, string $order)
	{
		$mapping = [
			'company_name' => 'company_name',
		];
	
		$field_name = $mapping[$field_key] ?? (Schema::hasColumn($this->getTable(), $field_key) ? $field_key : null);
		if (!$field_name) return $query; 
	
		$query->select(['employees.*']); 
		switch ($field_key) {
			case 'company_name':
				$query->join('companies', 'employees.company_id', '=', 'companies.id')
					->orderBy("companies.name", $order);
				break;
			default:
				$query->orderBy("employees.$field_key", $order);
				break;
		}
	
		return $query;
	}

    
    public function company()
    {
        return $this->belongsTo(ModelsCompany::class, 'company_id');
    }
}
