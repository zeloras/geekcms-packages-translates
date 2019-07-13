<?php

namespace GeekCms\Translates\Database\Seeders;

use GeekCms\Translates\Models\TranslateLanguages;
use GeekCms\Translates\Models\TranslateLanguagesElements;
use Illuminate\Database\Seeder;

class TranslatesDatabaseSeeder extends Seeder
{
    public function run()
    {
        TranslateLanguages::truncate();
        TranslateLanguagesElements::truncate();
        TranslateLanguages::create([
            'key' => 'ru',
            'name' => 'Russian',
            'native' => 'Русский',
            'script' => 'Cyrl',
            'regional' => 'ru_RU',
            'icon' => 'flag-icon-ru',
            'enabled' => true,
        ]);

        TranslateLanguages::create([
            'key' => 'en',
            'name' => 'English',
            'native' => 'English',
            'script' => 'Latn',
            'regional' => 'en_GB',
            'icon' => 'flag-icon-um',
            'enabled' => true,
        ]);
    }
}
