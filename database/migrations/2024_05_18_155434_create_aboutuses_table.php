<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aboutuses', function (Blueprint $table) {
            $table->increments('id');

            // Section: Introduction One
            $table->string('intro_one_title_ar')->nullable();
            $table->string('intro_one_title_en')->nullable();
            $table->longText('intro_one_desc_ar')->nullable();
            $table->longText('intro_one_desc_en')->nullable();
            $table->string('intro_one_bg1')->nullable();
            $table->string('intro_one_bg2')->nullable();

            // Section: Introduction Two
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aboutuses');
    }
}
