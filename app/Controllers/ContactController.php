<?php

namespace App\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{

  private  $model;

  public function __construct(){

    $this->model = new Contact;

  }
  

  public function index(){

    return $this->model->select("name","id")
                      ->where('name','LIKE','%d%') 
                      ->where('id','<','5')
                      ->order_by('name','DESC')
                      ->all();

    if(isset($_GET['search'])){
      $contacts = $this->model->where('name','LIKE','%'.$_GET['search'].'%') 
                              ->order_by('name','DESC')                             
                              ->paginate(3);      
    }else{
      $contacts =  $this->model->where('id','>','1')
                              ->order_by('phone','DESC') 
                              ->order_by('name','DESC') 
                              ->paginate(3);
      
    }

    return $this->view('contacts.index',compact('contacts'));

  }

  public function create(){

    return $this->view('contacts.create');

  }

  public function store(){

    $data = $_POST;
    $model = new Contact;
    $model->create($data);
    
    return $this->redirect("/contacts");

  }
      
  public function show($id){

    $contact  = $this->model->find($id);

    return $this->view('contacts.show',compact('contact'));
 
  } 
  
  public function edit($id){
    
    $contact = $this->model->find($id);

    return $this->view('contacts.edit',compact('contact'));
  } 
  
  public function update($id){

    $this->model->update($id,$_POST);

    return $this->redirect("/contacts/{$id}");
  } 

  public function destroy($id){
    
    $this->model->delete($id);

    return $this->redirect('/contacts');
  }   
  
};