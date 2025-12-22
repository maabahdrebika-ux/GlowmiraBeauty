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
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number');
            $table->string('total_price');

            $table->timestamp('created_at')->useCurrent();
            $table->integer('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->integer('customers_id')->unsigned()->nullable();
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('cascade');
            
            $table->integer('invoice_types_id')->unsigned()->nullable();
            $table->foreign('invoice_types_id')->references('id')->on('invoice_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
