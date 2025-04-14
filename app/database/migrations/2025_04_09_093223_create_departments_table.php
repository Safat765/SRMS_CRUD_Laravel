<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('departments')) {
			Schema::create('departments', function(Blueprint $table)
			{
				$table->increments('department_id');
				$table->string('name', 30);
				$table->integer('created_by')->unsigned();
				$table->timestamps();
				$table->foreign('created_by')
					->references('user_id')->on('users')
					->onDelete('cascade')
					->onUpdate('cascade');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('departments');
	}

}
