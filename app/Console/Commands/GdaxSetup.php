<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Exchange;
use \App\Pair;
use \App\Exchange\Gdax;

class GdaxSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdax:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup GDAX for Bot';

    private $exchange;
    private $api;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->exchange = \App\Exchange::firstOrCreate(['name'=>'GDAX']);
        $this->api = new \App\Exchange\Gdax();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = $this->api->call('products');
        if($response['responseCode'] == 200)
        {
            $response = json_decode($response['response']);
            foreach($response as $product)
            {
                $pair = $this->exchange->pairs()->firstOrCreate(['name'=>strtoupper($product->id)]);
            }
        }else{
            echo "\n\nError Setting Up GDAX!\nError Code: ".$response['responseCode']."\nMessage: ".json_decode($response['response'])->message."\n\n";
        }
    }
}
