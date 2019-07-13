<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesList extends Migration
{
    public function up()
    {
        Schema::create('translate_languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique()->index();
            $table->string('name')->nullable();
            $table->string('native')->nullable();
            $table->string('script')->nullable();
            $table->string('regional')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translate_languages');
    }
}
