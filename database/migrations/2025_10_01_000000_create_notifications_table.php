<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->string('message');
            $table->string('messageen');
            $table->boolean('is_read')->default(false);
            $table->string('url')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->integer('orders_id')->unsigned()->nullable();
            $table->foreign('orders_id')->references('id')->on('orders')->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
