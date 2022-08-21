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
    return redirect('/task');
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('/task')->name('taks.')->group(function(){
    Route::get('/','TaskController@index')->name('list');
    Route::get('/list','TaskController@listTasks')->name('list-all');
    Route::post('/store','TaskController@store')->name('store');
    Route::post('/update','TaskController@update')->name('update');
    Route::post('/update-time','TaskController@updateTime')->name('update-time');
    Route::post('/delete','TaskController@delete')->name('delete-task');
});