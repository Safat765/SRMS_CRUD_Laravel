<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profiles', function(Blueprint $table)
		{
			$table->increments('profile_id');
			$table->string('first_name', 50);
			$table->string('middle_name',50);
			$table->string('last_name', 50);
			$table->string('registration_number', 15);
			$table->string('session',20)->nullable();
			$table->integer('department_id')->unsigned();
			$table->foreign('department_id')
				->references('department_id')->on('departments')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				->references('user_id')->on('users')
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
		Schema::drop('profiles');
	}

}
