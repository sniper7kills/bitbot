<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pair extends Model 
{

    protected $table = 'pairs';
    public $timestamps = true;
    protected $fillable = array('name','enabled');
    protected $visible = array('name','enabled');

    public function exchange()
    {
        return $this->belongsTo('App\Exchange');
    }

    public function ticks()
    {
        return $this->hasMany('App\Tick');
    }
}
