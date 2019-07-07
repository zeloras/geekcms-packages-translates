<?php

namespace Modules\Translates\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Translates\Models\TranslateLanguages;
use Modules\Translates\Models\TranslateLanguagesElements;

class TranslatesDatabaseSeeder extends Seeder
{
    public function run()
    {
        TranslateLanguages::truncate();
        TranslateLanguagesElements::truncate();

        $langRu = TranslateLanguages::create([
            'key' => 'ru',
            'name' => 'Russian',
            'native' => 'Русский',
            'script' => 'Cyrl',
            'regional' => 'ru_RU',
            'icon' => 'flag-icon-ru',
            'enabled' => true,
        ]);

        $langEn = TranslateLanguages::create([
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
