<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Casa extends Model
{
    public function propietario()
    {
    	return $this->belongsTo('App\Propietario');
    }

    public function ingreso()
    {
    	return $this->hasMany('App\Ingreso');	
    }
}
