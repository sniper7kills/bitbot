<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCandles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitbot:makeCandles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Candles from Info in DB';

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
        //
    }
}
