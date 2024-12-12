<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantGalleryTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            "tp_restaurant_gallery",
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('order')->nullable();
                $table->string('image', 255);
                $table->unsignedBigInteger('restaurant_id')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign("restaurant_id")->references("id")->on("tp_restaurants")->onUpdate('CASCADE')->onDelete('CASCADE');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_restaurant_gallery');
    }
}
