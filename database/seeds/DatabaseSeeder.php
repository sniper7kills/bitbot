<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Model::unguard();

		$this->call('PairTableSeeder');
		$this->command->info('Pair table seeded!');

		$this->call('ExchangeTableSeeder');
		$this->command->info('Exchange table seeded!');
	}
}