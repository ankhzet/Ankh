<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRedundantFromUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->text('change')->nullable()->change();
            $table->dropColumn(['value', 'delta']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('updates', function (Blueprint $table) {
            $table->integer('delta');
            $table->integer('value');
            $table->string('change')->change();
        });
    }
}
