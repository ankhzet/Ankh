	<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreatePagesTable extends Migration {
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('pages', function(Blueprint $table) {
				$table->increments('id');

				$table->integer('author_id')->unsigned()->index();
				$table->foreign('author_id')->references('id')->on('authors');
				$table->integer('group_id')->unsigned()->index();
				$table->foreign('group_id')->references('id')->on('groups');

				$table->string('title');
				$table->string('annotation');
				$table->integer('size');
				$table->string('link');

				$table->timestamps();
				$table->softDeletes();
			});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('pages');
		}
	}
