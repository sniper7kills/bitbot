<?php

use Illuminate\Database\Seeder;
use \Pair;

class PairTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('pairs')->delete();

		// gdax.btc-usd
		Pair::create(array(
				'exchange_id' => 1,
				'name' => btc-usd
			));

		// gdax.eth-usd
		Pair::create(array(
				'exchange_id' => 1,
				'name' => eth-usd
			));

		// gdax.ltc-usd
		Pair::create(array(
				'exchange_id' => 1,
				'name' => ltc-usd
			));
	}
}