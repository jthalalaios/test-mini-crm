<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::truncate();
        $json = File::get(database_path('seeders/json/roles.json'));
        $roles = json_decode($json, true);

        foreach($roles as $role) {
            Role::create([
                'name' => $role['name'],
                'slug' => $role['slug'],
                'created_at' => Carbon::now()->setTimezone('UTC'),
                'updated_at' => Carbon::now()->setTimezone('UTC'),
            ]);
        }

    }
}
