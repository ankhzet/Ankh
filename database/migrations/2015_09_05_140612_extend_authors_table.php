<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('rating')->nullable();
            $table->string('title')->nullable();
            $table->integer('visitors')->nullable();
            $table->dropColumn('info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->text('info')->nullable();
            $table->dropColumn(['rating', 'visitors', 'title']);
        });
    }
}
