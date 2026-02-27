<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $admin_role = Role::where('slug', 'admin')->first();
        
        $user->roles()->attach($admin_role?->id, [
			'created_at' => Carbon::now()->setTimezone('UTC'),
			'updated_at' => Carbon::now()->setTimezone('UTC'),
		]);

        $default_language = Language::where('enabled', true)->where('default', true)->first();
        UserSetting::firstOrCreate([
                    'user_id' => $user->id,
                    'language_id' => $default_language->id,
                ]);
    }
}