<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


// group route of student API
Route::group(array('prefix'=>'APICheckPoint'), function(){
    Route::get('students','StudentsController@index');
    Route::post('students/register','StudentsController@register');
    Route::put('students/{id}/update','StudentsController@update');
    Route::delete('students/{id}/destroy','StudentsController@destroy');
    Route::get('students/search','StudentsController@search');
    Route::post('students/login','StudentsController@login');
    Route::get('students/{id}','StudentsController@show');
});