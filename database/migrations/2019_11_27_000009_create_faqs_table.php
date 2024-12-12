<?php

use App\Idea\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;

class CreateFaqsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('question')->nullable()->translate();
            $table->text('answer')->nullable()->translate();
            $table->timestamps();
            $table->softDeletes();
            $this->setMainTable($table);
        });
        $this->translateMainTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_faqs');
    }
}
