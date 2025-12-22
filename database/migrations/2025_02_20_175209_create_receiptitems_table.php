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
        Schema::create('receiptitems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('price');
            $table->string('quantty');
            $table->date('expired_date')->nullable();

               $table->timestamp('created_at')->useCurrent();
            $table->integer('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->integer('receipts_id')->unsigned()->nullable();
            $table->foreign('receipts_id')->references('id')->on('receipts')->onDelete('cascade');
         
            $table->integer('coolors_id')->unsigned()->nullable();
            $table->foreign('coolors_id')->references('id')->on('coolors')->onDelete('cascade');
            $table->integer('sizes_id')->unsigned()->nullable();
            $table->foreign('sizes_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->integer('products_id')->unsigned()->nullable();
            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiptitems');
    }
};
