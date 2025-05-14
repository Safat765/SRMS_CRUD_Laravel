<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration {
	
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('exams', function(Blueprint $table)
		{
			$table->increments('exam_id');
			$table->integer('course_id')->unsigned();
			$table->foreign('course_id')
				->references('course_id')->on('courses')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->integer('department_id')->unsigned();
			$table->foreign('department_id')
				->references('department_id')->on('departments')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->integer('semester_id')->unsigned();
			$table->foreign('semester_id')
				->references('semester_id')->on('semesters')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->string('exam_title', 150);
			$table->enum('exam_type', [1, 2, 3, 4]);
			$table->tinyInteger('credit');
			$table->integer('marks');
			$table->integer('instructor_id')->unsigned();
			$table->foreign('instructor_id')
				->references('user_id')->on('users')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->integer('created_by')->unsigned();
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
		Schema::drop('exams');
	}
	
}
