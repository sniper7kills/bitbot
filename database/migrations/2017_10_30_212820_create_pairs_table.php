<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePairsTable extends Migration {

	public function up()
	{
		Schema::create('pairs', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('exchange_id')->unsigned()->index();
			$table->string('name');
                        $table->boolean('enabled')->default(FALSE);
		});
	}

	public function down()
	{
		Schema::drop('pairs');
	}
}
