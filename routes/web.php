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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');

# Create Item
Route::post('/save', 'HomeController@createItem')->name('home');

#Get All Todos
Route::get('/getTodos', 'HomeController@getAllItems')->name('home');

#Update todo item
Route::post('/update', 'HomeController@update')->name('home');

#Delete an item
Route::post('/delete', 'HomeController@delete')->name('home');

#Mark todo as complete
Route::post('/complete', 'HomeController@completeItem')->name('home');

