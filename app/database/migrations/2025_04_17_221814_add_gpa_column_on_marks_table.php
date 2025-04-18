<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddGpaColumnOnMarksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('marks', function(Blueprint $table)
		{
			$table->decimal('gpa', 4, 2)->nullable()->after('marks');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('marks', function(Blueprint $table)
		{
            $table->dropColumn('gpa');
		});
	}

}
