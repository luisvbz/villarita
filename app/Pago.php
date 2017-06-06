<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
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
    	return $this->belongsTo('App\FormaPago', 'forma_pago_id','id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function periodo()
    {
        return $this->belongsTo('App\Periodo', 'codperi', 'cod');
    }
}