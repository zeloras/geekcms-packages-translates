<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesListTranslate extends Migration
{
    public function up()
    {
        Schema::create('translate_languages_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lang_id')->nullable()->index();
            $table->string('key')->nullable();
            $table->string('translate')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translate_languages_elements');
    }
}
