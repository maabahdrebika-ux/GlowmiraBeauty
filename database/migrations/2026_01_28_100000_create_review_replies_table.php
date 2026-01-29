<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('review_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('review_id')->unsigned()->nullable();
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('admin_id')->unsigned()->nullable()->comment('If reply is from admin');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('comment')->comment('Reply comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_replies');
    }
};
