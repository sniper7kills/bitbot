<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candle extends Model 
{

    protected $table = 'candles';
    public $timestamps = false;
    protected $fillable = array('timestamp', 'open', 'close', 'high', 'low');
    protected $visible = array('timestamp', 'open', 'close', 'high', 'low');

}