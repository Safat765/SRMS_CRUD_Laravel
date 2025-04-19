<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('results', function(Blueprint $table)
		{
			$table->increments('result_id')->unsigned();
			$table->integer('student_id')->unsigned();
			$table->foreign('student_id')
				->references('user_id')->on('users')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->decimal('cgpa', 3, 2);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('results');
	}

}
