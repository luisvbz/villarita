<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    public function casa()
    {
    	return $this->belongsTo('App\Casa');
    }

    public function cuenta()
    {
    	return $this->belongsTo('App\Cuenta');
    }

    public function formapago()
    {
    	return $this->belongsTo('App\FormaPago');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function periodo()
    {
        return $this->belongsTo('App\Periodo', 'codperi', 'cod');
    }

    public function tipoIngreso()
    {
        return $this->belongsTo('App\TipoIngreso', 'tipo_ingreso_id', 'id');
    }

}
