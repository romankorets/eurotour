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
    })->name('admin')->middleware('auth');

    Route::resource('place', 'PlaceController', ['except' => [
        'show'
    ]])->middleware('auth');

    Route::resource('tour', 'TourController', ['except' => [
        'show'
    ]])->middleware('auth');
//    Route::resource('comment', 'CommentController', ['except' =>[
//        'show', 'index'
//    ]]);
});


Route::post('api/comment', 'CommentController@store');
Route::get('api/comment/{comment}', 'CommentController@getCommentUser')->name('comment.getCommentUser');

Route::get('api/place/{place}/comments', 'PlaceController@getComments')->name('place.getComments');
Route::get('place/{place}', 'PlaceController@show')->name('place.show');
Route::get('api/place/index', 'PlaceController@getPlaces')->name('place.getPlaces');

Route::get('tour/{tour}', 'TourController@show')->name('tour.show');
Route::get('api/tour/index', 'TourController@getTours')->name('tour.getTours');
Route::get('api/tour/{tour}/places', 'TourController@getTourPlaces')->name('tour.getTourPlaces');
Route::get('api/tour/{tour}/start-place', 'TourController@getTourStartPlace')->name('tour.getTourStartPlace');
Route::get('api/tour/{tour}/finish-place', 'TourController@getTourFinishPlace')->name('tour.getTourFinishPlace');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
