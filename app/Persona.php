<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable =['nombre','meta','condicion'];
    
    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }

    public function users()
    {
        return $this->hasOne('App\User');
    }
}
