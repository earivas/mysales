<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';
    protected $fillable = ['fecha','mestexto','anyo','mes','quincena','periodo'];
    public $timestamps = false;
}

