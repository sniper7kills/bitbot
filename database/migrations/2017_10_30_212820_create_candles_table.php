<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCandlesTable extends Migration {

	public function up()
	{
		Schema::create('candles', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('pair_id')->unsigned();
			$table->timestamp('timestamp');
			$table->float('open', 26,20);
			$table->float('close', 26,20);
			$table->float('high', 26,20);
			$table->float('low', 26,20);
			$table->float('volume', 26,20);
		});
	}

	public function down()
	{
		Schema::drop('candles');
	}
}