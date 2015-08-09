<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityUpdatePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_update', function(Blueprint $table) {
            $table->integer('entity_id')->unsigned()->index();

            $table->integer('update_id')->unsigned()->index();
            $table->foreign('update_id')->references('id')->on('updates')->onDelete('cascade');

            $table->integer('r_type')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('entity_update');
    }
}
