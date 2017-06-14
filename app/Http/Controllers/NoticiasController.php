<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Noticia;

class NoticiasController extends Controller
{
    public function getAll(){

    	$noticias = Noticia::with('user')->get();

    	return $noticias->toJson();
    }

    public function getAllPaginate(){

    	$noticias = Noticia::with('user')->paginate(10);

    	return $noticias->toJson();
    }
}
