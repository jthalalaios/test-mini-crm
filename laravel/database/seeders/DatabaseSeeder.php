<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            RoleSeeder::class,
            UserSeeder::class, // creating the admin
            CompanySeeder::class, //dummy data
            EmployeeSeeder::class, //dummy data
        ]);
    }
}
