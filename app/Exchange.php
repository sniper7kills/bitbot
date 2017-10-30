<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model 
{

    protected $table = 'exchanges';
    public $timestamps = true;
    protected $fillable = array('name');
    protected $visible = array('name');

}