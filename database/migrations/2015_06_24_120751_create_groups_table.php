<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function(Blueprint $table) {
            $table->increments('id');

            $table->integer('author_id')->unsigned()->index();
            $table->foreign('author_id')->references('id')->on('authors');

            $table->string('title', 250);
            $table->string('link', 250);
            $table->boolean('inline');
            $table->string('annotation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('groups');
    }
}
