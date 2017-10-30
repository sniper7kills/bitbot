<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tick extends Model 
{

    protected $table = 'ticks';
    public $timestamps = false;
    protected $fillable = array('trade_id', 'price', 'size', 'timestamp');
    protected $visible = array('trade_id', 'price', 'size', 'timestamp');

}