<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserFailedTrialTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users_failed_trial',
            function (Blueprint $table) {
                $table->bigIncrements("id");
                $table->string("type");
                $table->integer("trial")->default(0);
                $table->timestamp('last_trial_date')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign("user_id")->references("id")->on("users");
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
        Schema::dropIfExists('users_failed_trial');
    }
}
