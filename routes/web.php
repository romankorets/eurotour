<?php

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

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', function (){
        if (Auth::user()->hasRole('admin')){
            return view('admin.index');
        } else return redirect()->back();
    })->name('admin');

    Route::resource('place', 'PlaceController', ['except' => [
        'index', 'show'
    ]]);

    Route::resource('tour', 'TourController', ['except' => [
        'index', 'show'
    ]]);
});

Route::get('place/{place}', 'PlaceController@show')->name('place.show');
Route::get('api/place/index', 'PlaceController@getPlaces')->name('place.getPlaces');
Route::get('place/index', 'PlaceController@index')->name('place.index');

Route::get('tour/{tour}', 'TourController@show')->name('tour.show');
Route::get('api/tour/index', 'TourController@index')->name('tour.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
