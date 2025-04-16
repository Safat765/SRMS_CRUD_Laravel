<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSemesterColumnOnProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('profiles')) {
			Schema::table('profiles', function(Blueprint $table)
			{
				$table->integer('semester_id')->unsigned()->nullable()->after('session');
				$table->foreign('semester_id')
					->references('semester_id')->on('semesters')
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
		Schema::table('profiles', function(Blueprint $table)
		{
            $table->dropColumn('semester_id');
		});
	}

}
