<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //hassan there should be a POS table id that map the pos table to this, also we need to save the QR image path somewhere
        Schema::create('tp_restaurant_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("code"); // this will the table id of omnivore POS table
            $table->unsignedBigInteger("restaurant_id")->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("restaurant_id")->references("id")->on("tp_restaurants")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_restaurant_tables');
    }
}
