<?php

namespace Modules\Translates\Models\DB;

use App\Models\MainModel;

class TranslateLanguages extends MainModel
{
    public $table = 'translate_languages';

    protected $fillable = [
        'key', 'name', 'native', 'script', 'regional', 'icon', 'enabled',
    ];

    /**
     * Get translated items for current language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(TranslateLanguagesElements::class, 'lang_id', 'id');
    }
}
