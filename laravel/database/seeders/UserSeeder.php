<?php

namespace Database\Seeders;

use App\Helpers\RolesHelper;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
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
        RolesHelper::insert_user_role_by_id($user->id, $admin_role?->id);

        $default_language = Language::where('enabled', true)->where('default', true)->first();
        UserSetting::firstOrCreate([
                    'user_id' => $user->id,
                    'language_id' => $default_language->id,
                ]);
    }
}