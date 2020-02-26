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

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('admin')->middleware('can:admin');

    Route::get('place', 'PlaceController@index')->name('place.index')->middleware('can:admin');
    Route::post('place', 'PlaceController@store')->name('place.store')->middleware('can:create,App\Place');
    Route::get('place/create', 'PlaceController@create')->name('place.create')->middleware('can:create,App\Place');
    Route::get('place/{place}/edit', 'PlaceController@edit')->name('place.edit')->middleware('can:update,place');
    Route::put('place/{place}', 'PlaceController@update')->name('place.update')->middleware('can:update,place');
    Route::delete('place/{place}', 'PlaceController@destroy')->name('place.destroy')->middleware('can:delete,place');

    Route::get('tour', 'TourController@index')->name('tour.index')->middleware('can:admin');
    Route::post('tour', 'TourController@store')->name('tour.store')->middleware('can:create,App\Tour');
    Route::get('tour/create', 'TourController@create')->name('tour.create')->middleware('can:create,App\Tour');
    Route::get('tour/{tour}/edit', 'TourController@edit')->name('tour.edit')->middleware('can:update,tour');
    Route::put('tour/{tour}', 'TourController@update')->name('tour.update')->middleware('can:update,tour');
    Route::delete('tour/{tour}', 'TourController@destroy')->name('tour.destroy')->middleware('can:delete,tour');
});

Route::get('api/user/roles', 'UserController@getUserRoles');
Route::get('api/user', 'UserController@getUserId');

Route::post('api/place/{place}/like', 'LikeController@store')->name('like.store');
Route::delete('api/place/{place}/like/delete', 'LikeController@destroy')->name('like.destroy');

Route::post('api/place/{place}/comment', 'CommentController@store');
Route::put('api/comment/{comment}', 'CommentController@update');

Route::get('api/place/index', 'PlaceController@getPlaces')->name('place.getPlaces');

Route::get('api/place/{place}', 'PlaceController@getPlace')->name('place.getPlace');

Route::get('api/place/count', 'PlaceController@getCount')->name('place.getCount');

Route::get('api/tour/index', 'TourController@getTours')->name('tour.getTours');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
