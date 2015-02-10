<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Slack extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->string('user_id')->after('id');
			$table->string('team_id')->after('id');
			$table->string('team')->after('name');
			$table->string('token')->after('team_id');
			$table->string('firstname')->after('token');
			$table->string('lastname')->after('firstname');
			$table->string('image')->after('team');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('user_id');
			$table->dropColumn('team_id');
			$table->dropColumn('team');
			$table->dropColumn('firstname');
			$table->dropColumn('lastname');
			$table->dropColumn('image');
			$table->dropColumn('token');
		});
	}

}
