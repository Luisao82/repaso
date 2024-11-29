<?php

namespace Lib;

class Route {

  private static $routes = [];

  
  public static function get($uri,$callback){
    $uri = trim($uri, "/");
    self::$routes['GET'][$uri] = $callback;
  }

  public static function post($uri,$callback){
    $uri = trim($uri, "/");
    self::$routes['POST'][$uri] = $callback;
  }

  public static function resource($name,$class){

    self::get("/{$name}",[$class,"index"]);
    self::get("/{$name}/create",[$class,"create"]);
    self::post("/{$name}",[$class,"store"]);
    self::get("/{$name}/:id",[$class,"show"]);
    self::get("/{$name}/:id/edit",[$class,"edit"]);
    self::post("/{$name}/:id",[$class,"update"]);
    self::post("/{$name}/:id/delete",[$class,"destroy"]);
  }

  public static function  dispatch(){

    $uri = trim($_SERVER['REQUEST_URI'], "/");

    /*
    Se comprueba si en la uri viene el parametro 'page=x'.
    En el caso afirmativo, se elimina para poder seguir con una uri correcta a nuestro route
    */
    $uri = strpos($uri,'?') ? substr($uri,0,(strpos($uri,'?'))) : $uri;
    
    $method  = $_SERVER['REQUEST_METHOD'];
    
    foreach(self::$routes[$method]  as  $route => $callback){
      
      /*
      Se comprueba si el Route tiene algun parametro definido mediante  ':'
      */

      if(strpos($route,':') !== false){

        $route = preg_replace('#:[a-zA-Z]+#','([a-zA-Z0-9]+)',$route);

      }
      
      if(preg_match("#^$route$#",$uri,$matches)){
        
        $params = array_splice($matches,1);  
        //$response = $callback(...$params);

        /*
        Vamos a preguntar si el callback enviado es una funcion o un array que contiene la clase y el metodo a ejecutar.
        La clase es del controlador enviado como  callback
        */
        // Primero pregunto si es una funcion lo que mando
        if(is_callable($callback)){
          $response = $callback(...$params);
        }
        /*
          Segundo pregunto  si es un array  lo que se manda, si es asi la estructura del array es la siguiente
          $callback[0] = Controller::class
          $callback[1] = metodo de la clase 
        */
        if(is_array($callback)){
          $controller = new $callback[0];
          $response = $controller->{$callback[1]}(...$params);
        }

        
        if(is_array($response) || is_object($response)){
          echo json_encode($response);
        }else{
          echo $response;
        }

        return;
      }
    }

    echo "Error 404";

  }
  
}
