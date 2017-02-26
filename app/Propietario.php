<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    public function casa()
    {
        return $this->hasOne('App\Casa');
    }
    public function conyuge()
    {
    	return $this->hasOne('App\Conyuge');
    }

    public function hijos()
    {
    	return $this->hasMany('App\Hijo');
    }

    public function vehiculos()
    {
    	return $this->hasMany('App\Vehiculo');
    }


}
