<?php

use Illuminate\Database\Seeder;
use \Exchange;

class ExchangeTableSeeder extends Seeder {

	public function run()
	{
		//DB::table('exchanges')->delete();

		// GDAX
		Exchange::create(array(
				'name' => gdax
			));
	}
}