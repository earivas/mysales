<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plataforma extends Model
{
    protected $fillable =['nombre_plataforma','porcentaje','condicion'];
    
    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

   
}
