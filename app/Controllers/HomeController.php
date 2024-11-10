<?php

namespace App\Controllers;

class HomeController extends Controller
{

  public function index(){
    
    return $this->view("home", [
      'titulo' => 'Home',
      'descripcion' => 'Descripcion de la pagina'
    ]);

  }

  
}