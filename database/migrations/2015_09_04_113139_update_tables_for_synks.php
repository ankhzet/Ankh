<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablesForSynks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->text('info')->nullable();
         });
        Schema::table('groups', function (Blueprint $table) {
            $table->text('annotation')->nullable()->change();
            $table->text('link')->nullable()->change();
         });
        Schema::table('pages', function (Blueprint $table) {
            $table->text('annotation')->nullable()->change();
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
            $table->dropColumn('info');
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->string('annotation')->change();
        });
        Schema::table('pages', function (Blueprint $table) {
            $table->string('annotation')->change();
        });
    }
}
