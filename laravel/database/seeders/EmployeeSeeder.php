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
        // ensure some companies exist, without firing events
        $companies = \DB::transaction(function () {
            return Company::withoutEvents(function () {
                $existing = Company::all();
                if ($existing->isEmpty()) {
                    return Company::factory(100)->create();
                }
                return $existing;
            });
        });

        // create 1000 employees distributed across existing companies
        Employee::factory(1000)->make()->each(function ($employee) use ($companies) {
            $employee->company_id = $companies->random()->id;
        })->each->save();
    }
}
