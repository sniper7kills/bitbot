<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicksTable extends Migration {

	public function up()
	{
		Schema::create('ticks', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('trade_id')->unsigned();
			$table->integer('pair_id')->unsigned();
			$table->float('price', 26,20);
			$table->float('size', 26,20);
			$table->timestamp('timestamp');
		});
	}

	public function down()
	{
		Schema::drop('ticks');
	}
}