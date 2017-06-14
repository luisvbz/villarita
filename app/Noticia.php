<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    public function comentarios(){
    	return $this->hasMany('App\Comentario');
    }

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
