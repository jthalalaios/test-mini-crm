<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ensure some companies exist
        $companies = Company::all();
        if ($companies->isEmpty()) {
            $companies = Company::factory(100)->create();
        }

        // create 1000 employees distributed across existing companies
        Employee::factory(1000)->make()->each(fn($employee) =>
            $employee->company_id = $companies->random()->id
        )->each->save();
    }
}
