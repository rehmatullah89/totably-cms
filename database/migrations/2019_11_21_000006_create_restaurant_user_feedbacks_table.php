<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantUserFeedbacksTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_restaurant_user_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string("ticket_id")->nullable(); // it will be zero, if user is giving feedback to restaurant
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("restaurant_id")->nullable(); // it will be zero, if user is giving feedback to order not restaurant
            $table->integer("rating");
            $table->mediumText("comment");
            $table->mediumText("restaurant_response");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")->references("id")->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('tp_restaurant_user_feedbacks');
    }
}
