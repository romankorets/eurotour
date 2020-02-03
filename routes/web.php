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
});

Route::get('api/user/{user}/roles', 'UserController@getUserRoles');

Route::post('api/like', 'LikeController@store')->name('like.store');
Route::put('api/like/{user}/{place}/update', 'LikeController@update')->name('like.update');
Route::delete('api/like/{user}/{place}/delete', 'LikeController@destroy')->name('like.destroy');

Route::post('api/comment', 'CommentController@store');
Route::put('api/comment/{comment}', 'CommentController@update');

Route::get('api/place/index', 'PlaceController@getPlaces')->name('place.getPlaces');

Route::get('api/tour/index', 'TourController@getTours')->name('tour.getTours');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
