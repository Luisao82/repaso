<?php

use Lib\Route;

use App\Controllers\HomeController;
use App\Controllers\ContactController;

Route::get('/',[HomeController::class,"index"]);

Route::resource('contacts',ContactController::class);
/*
Route::get('/contacts',[ContactController::class,"index"]);
Route::get('/contacts/create',[ContactController::class,"create"]);
Route::post('/contacts',[ContactController::class,"store"]);
Route::get('/contacts/:id',[ContactController::class,"show"]);
Route::get('/contacts/:id/edit',[ContactController::class,"edit"]);
Route::post('/contacts/:id',[ContactController::class,"update"]);
Route::post('/contacts/:id/delete',[ContactController::class,"destroy"]);
*/
Route::dispatch();