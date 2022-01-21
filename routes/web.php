<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'web'], function () {
    Route::get(env('LARAVUE_PATH'), 'LaravueController@index')->where('any', '.*')->name('laravue');
});

//Praktikum
Route::get('praktikum', 'PraktikumController@index');
Route::get('praktikum/{id}', 'PraktikumController@getPraktikum');
Route::post('praktikum', 'PraktikumController@store');
Route::delete('praktikum/{id}', 'PraktikumController@destroy');


//Modul
Route::get('modul', 'ModulController@index');
Route::get('modul/{id}', 'ModulController@getModul');


//Dosen
Route::get('dosen', 'DosenController@index');
