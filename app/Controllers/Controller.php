<?php

namespace App\Controllers;

class Controller {
/*
Metodo view:
Metodo que tendra todos los controladores para mostrar una vista 
*/
  public function view($route, $data = []){

    //Destructurar Array
    extract($data);
    
    $route = str_replace(".","/",$route);

     if(file_exists("../resources/views/{$route}.php")){
      
       ob_start();
       require_once("../resources/views/{$route}.php");
       $response = ob_get_clean();

       return $response;
      
     }else{
        
      return "No existe la pagina";

     }
    
  }

  /*
  Metodo redirect
  Metodo que nos redirige a la pagina enviada por parametro
  */
  public function redirect($route){
    header("Location:{$route}");
  }
}