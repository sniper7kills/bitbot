<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class websocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:websocket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WebSocket Testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $request = array("type"=>"subscribe","product_ids"=>array("btc-usd"),"channels"=>array("level2","heartbeat",array("name"=>"ticker","product_ids"=>array("btc-usd"))));
        \Ratchet\Client\connect('wss://ws-feed.gdax.com')->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                echo "Received: {$msg}\n";
                //$conn->close();
            });

            //$conn->send('subscribe');
//            $conn->on('open', function() use ($conn) {
//                $conn->send(json_encode(array("type"=>"subscribe","product_ids"=>array("btc-usd"),"channels"=>array("heartbeat"))));
//            });
            $request = array("type"=>"subscribe","product_ids"=>array("btc-usd"),"channels"=>array("matches","heartbeat",array("name"=>"ticker","product_ids"=>array("btc-usd"))));
            $conn->send(json_encode($request));
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });


    }
}
