<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a handful of companies without firing observers (e.g. skip verification/email)
        Company::withoutEvents(function () {
            Company::factory(100)->create();
        });
    }
}
