<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('candles', function(Blueprint $table) {
			$table->foreign('pair_id')->references('id')->on('pairs')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('pairs', function(Blueprint $table) {
			$table->foreign('exchange_id')->references('id')->on('exchanges')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('ticks', function(Blueprint $table) {
			$table->foreign('pair_id')->references('id')->on('pairs')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('candles', function(Blueprint $table) {
			$table->dropForeign('candles_pair_id_foreign');
		});
		Schema::table('pairs', function(Blueprint $table) {
			$table->dropForeign('pairs_exchange_id_foreign');
		});
		Schema::table('ticks', function(Blueprint $table) {
			$table->dropForeign('ticks_pair_id_foreign');
		});
	}
}