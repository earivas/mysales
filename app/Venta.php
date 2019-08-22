<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable =[
        'idpersona','fecha','cantidad','total_acumulado','condicion'
        ];
     public function persona (){
           return $this->belongsTo('App\Persona'); 
     }

}
