<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('marks', function(Blueprint $table)
		{
			$table->increments('mark_id');
			$table->integer('student_id')->unsigned();
			$table->foreign('student_id')
				->references('user_id')->on('users')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->integer('exam_id')->unsigned();
			$table->foreign('exam_id')
				->references('exam_id')->on('exams')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->integer('course_id')->unsigned();
			$table->foreign('course_id')
				->references('course_id')->on('courses')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->double('marks');
			$table->integer('semester_id')->unsigned();
			$table->foreign('semester_id')
				->references('semester_id')->on('semesters')
				->onDelete('cascade')
				->onUpdate('cascade');
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
		Schema::drop('marks');
	}

}
