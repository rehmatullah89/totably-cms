<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tp_restaurants',
            function (Blueprint $table) {
                $table->bigIncrements("id");
                $table->mediumText("location_id");
                $table->string("name");
                $table->longText("description")->nullable();
                $table->mediumText("address")->nullable();
                $table->string("phone")->nullable();
                $table->string("email");
                $table->string("working_hours")->nullable();
                $table->string("rating")->nullable();
                $table->string("image")->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('manager_id')->unsigned()->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign("user_id")->references("id")->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign('manager_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('tp_restaurants');
    }
}
