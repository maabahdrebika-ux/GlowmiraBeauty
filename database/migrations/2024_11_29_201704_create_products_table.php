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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('namee');
            $table->string('material')->nullable();

     
            $table->string('brandname_ar')->nullable();
            $table->string('brandname_en')->nullable();
            $table->string('country_of_origin_ar')->nullable();
            $table->string('country_of_origin_en')->nullable();
            $table->text('description_ar')->nullable(); // شرح المنتج بالعربي
            $table->text('description_en')->nullable(); // شرح المنتج بالإنجليزي
            $table->string('barcode')->nullable()->unique(); // الباركود الخاص بالمنتج

            $table->decimal('price', 10, 2);
            $table->integer('categories_id')->unsigned()->nullable();
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('image')->nullable(); // صورة واحدة للمنتج

           

            $table->text('notes')->nullable(); // ملاحظات حول المنتج

            $table->boolean('is_available')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
