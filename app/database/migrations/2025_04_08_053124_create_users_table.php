<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('user_id');
			$table->string('username', 50)->unique();
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->enum('user_type', [1, 2, 3]);
            $table->enum('status', [0, 1]);
            $table->string('registration_number', 10)->unique();
            $table->string('phone_number', 20);
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
		Schema::drop('users');
	}
}
