<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSemestersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('semesters', function(Blueprint $table)
		{
			$table->increments('semester_id');
			$table->string('name', 30);
			$table->integer('created_by')->unsigned();
			$table->timestamps();
			$table->foreign('created_by')
				->references('user_id')->on('users')
				->onDelete('cascade')
				->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('semesters');
	}

}
