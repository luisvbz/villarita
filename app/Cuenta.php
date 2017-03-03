<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = "cuentas_bancos";

    public function banco(){

    	return $this->belongsTo('App\Banco');
    }
}
