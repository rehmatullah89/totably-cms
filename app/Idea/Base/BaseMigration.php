<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Base;

use App\Helpers\StringHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BaseMigration extends Migration
{
    protected $mainTable;
    protected $columnToBeRemoved = [];

    public function setMainTable($mainTable)
    {
        $this->mainTable = $mainTable;
    }

    public function getMainTable()
    {
        return $this->mainTable;
    }

    /**
     * @return string
     */
    protected function getMainTableName()
    {
        return $this->mainTable->getTable();
    }

    /**
     * @return string
     */
    protected function getTranslationTableName()
    {
        return StringHelper::depluralize($this->getMainTableName()).'_translations';
    }

    /**
     * @return string
     */
    protected function getTablePrimaryIdName()
    {
        return StringHelper::depluralize($this->getMainTableName()).'_id';
    }

    /**
     * @return string
     */
    protected function getForeignKeyName()
    {
        $primaryIdName = $this->getTablePrimaryIdName();

        return $this->getTranslationTableName().'_'.$primaryIdName.'_foreign_key';
    }

    /**
     * @return string
     */
    protected function getLanguageForeignKeyName()
    {
        return $this->getTranslationTableName().'_locale_foreign_key';
    }

    public function translateMainTable($bigInt = false)
    {
        $primaryIdName = $this->getTablePrimaryIdName();

        //set columnToBeRemoved to empty
        $this->columnToBeRemoved = [];

        //add the translation table
        Schema::create(
            $this->getTranslationTableName(),
            function (Blueprint $table) use ($bigInt, $primaryIdName) {
                $table->increments('id');
                if ($bigInt) {
                    $table->unsignedBigInteger($primaryIdName);
                } else {
                    $table->unsignedInteger($primaryIdName);
                }

                //related to the locale table
                $table->string('locale', 5)->default("en");

                //add all the columns and fill columnToBeRemoved
                $this->addColumnToBeTranslated($table);

                //foreign key for the $primaryIdName column
                $table->foreign($primaryIdName, $this->getForeignKeyName())
                    ->references('id')
                    ->on($this->getMainTableName())
                    ->onDelete('cascade');

                //add unique constraint
                $table->unique([$primaryIdName, 'locale']);
            }
        );

        //remove columns from the main table now
        Schema::table(
            $this->getMainTableName(),
            function (Blueprint $t) {
                $t->dropColumn($this->columnToBeRemoved);
            }
        );
    }


    /**
     * @param $this
     * @param $columnToBeRemoved
     *
     * @return mixed
     */
    public function addColumnToBeTranslated($table)
    {
        $columns = $this->mainTable->getColumns();
        foreach ($columns as $column) {
            if (! $column->translate || ! in_array($column->type, ['string', 'text'])) {
                continue;
            }

            //clone column
            $table->addColumn($column->type, $column->name, $column->getAttributes());
            $this->columnToBeRemoved[] = $column->name;
        }

        return $table;
    }

    public function dropTranslationTable($mainTable)
    {
        $this->mainTable = $mainTable;

        //drop the relation first
        Schema::table(
            $this->getTranslationTableName(),
            function (Blueprint $table) {
                $table->dropForeign($this->getForeignKeyName());
                $table->dropForeign($this->getLanguageForeignKeyName());
            }
        );
        //drop the table
        Schema::drop($this->getTranslationTableName());
    }

    public function dropTableAndTranslation($mainTable)
    {
        //drop the translation table
        $this->dropTranslationTable($mainTable);

        //then the main table
        Schema::drop($this->getMainTableName());
    }
}
