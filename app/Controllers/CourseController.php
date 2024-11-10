<?php

namespace  App\Controllers;

class CourseController extends Controller
{
  public function index(){
    return $this->view('course',[
      "titulo" => 'Course',
      "descripcion" => 'Descripcion'
    ]);
  }
}