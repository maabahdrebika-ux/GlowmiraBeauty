<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');

            $table->string('phonenumber');
            $table->string('adress');
            $table->string('adressen');
            $table->longText('ourworksa')->nullable();
            $table->longText('ourworkse')->nullable();
            $table->string('lan')->nullable(); // Assuming this is latitude
            $table->string('long')->nullable(); // Assuming this is longitude
            
            // Social Media Links
            
            $table->string('whatsapp')->nullable(); // Facebook URL

            $table->string('facebook_url')->nullable(); // Facebook URL
            $table->string('instagram_url')->nullable(); // Instagram URL
            $table->string('twitter_url')->nullable(); // Twitter URL
            $table->string('linkedin_url')->nullable(); // LinkedIn URL
            $table->string('youtube_url')->nullable(); // YouTube URL
            $table->string('pinterest_url')->nullable(); // Pinterest URL
            
         
            $table->timestamp('created_at')->useCurrent();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contactuses');
    }
}
