<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exchange;
use App\Exchange\Gdax;
use App\Pair;
use App\Tick;

class GdaxStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdax:stream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect to the GDAX WebSocket and Stream Data into the database';

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
        \Ratchet\Client\connect('wss://ws-feed.gdax.com')->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                //echo "Received: {$msg}\n";
                $response = json_decode($msg);
                switch($response->type)
                {
                    case "match":
                        echo "Match Message\n";
                        print_r($response);
                        $pair = $this->exchange->pairs()->firstOrCreate(['name'=>strtoupper($response->product_id)]);
                        $pair->ticks()->create([
                            'trade_id'=>$response->trade_id,
                            'size'=>$response->size,
                            'price'=>$response->price,
                            'timestamp'=>date('Y-m-d H:i:s',strtotime($response->time)),
                        ]);
                        break;
                    case "heartbeat":
                        //echo "Heartbeat Message\n";
                        break;
                    case "ticker":
                        //echo "Ticker Message\n";
                        break;
                    case "last_match":
                        //echo "Last Match Message\n";
                        break;
                    case "subscriptions":
                        //echo "Subscriptions Message\n";
                        break;
                    default:
                        echo "Unknown Message Type: ".$response->type."\n";
                }
            });
            $product_ids = array();
            foreach($this->exchange->pairs()->where('enabled',TRUE)->get() as $pair)
            {
                $product_ids[] = strtolower($pair->name);
            }
            $request = array(
                "type"=>"subscribe",
                "product_ids"=>$product_ids,
                "channels"=>array(
                    "matches",
                    "heartbeat",
                    array(
                        "name"=>"ticker",
                        "product_ids"=>$product_ids
                    )
                )
            );
            $conn->send(json_encode($request));
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}
