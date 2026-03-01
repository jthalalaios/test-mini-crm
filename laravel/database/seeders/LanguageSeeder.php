<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LanguageSeeder extends Seeder
{
    public function run()
    {
		Language::truncate();
		
		Language::insert([
			[ 'name' => 'English', 'locale' => 'en', 'enabled' => 1, 'default' => 1, 'created_at' => Carbon::now()->setTimezone('UTC'), 'updated_at' => Carbon::now()->setTimezone('UTC')],
			[ 'name' => 'Greek', 'locale' => 'gr', 'enabled' => 1, 'default' => 0, 'created_at' => Carbon::now()->setTimezone('UTC'), 'updated_at' => Carbon::now()->setTimezone('UTC')],
		]);
    }
}