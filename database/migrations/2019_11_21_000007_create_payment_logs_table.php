<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentLogsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_payment_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("ticket_id");
            $table->text("item_id"); // single or comma separated multiple item ids
            $table->decimal("amount_paid");
            $table->integer("paid_for"); // pay for number of users
            $table->string("pay_type"); // split/free amount etc
            $table->unsignedBigInteger("table_id");
            $table->unsignedBigInteger("user_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("table_id")->references("id")->on("tp_restaurant_tables")->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign("user_id")->references("id")->on("users")->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_payment_logs');
    }
}
