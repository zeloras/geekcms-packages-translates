<?php

namespace Modules\Translates\Models\DB;

use App\Models\MainModel;

class TranslateLanguagesElements extends MainModel
{
    protected $table = 'translate_languages_elements';

    protected $fillable = [
        'lang_id', 'key', 'translate',	'enabled',
    ];
}
