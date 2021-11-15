<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


///////////////////////////////////////////////////////////////////////////////////////////
// ADMIN
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('admin/store', 'AdminController@store');
Route::post('admin/update', 'AdminController@update');
Route::post('admin/delete', 'AdminController@delete');
Route::post('admin/login', 'AdminController@login');
Route::post('admin/showById', 'AdminController@showById');
Route::get('admin/show', 'AdminController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// USERS
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('users/store', 'UsersController@store');
Route::post('users/update', 'UsersController@update');
Route::post('users/delete', 'UsersController@delete');
Route::post('users/login', 'UsersController@login');
Route::post('users/showById', 'UsersController@showById');
Route::get('users/show', 'UsersController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// FACILITIES
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('facilities/store', 'FacilitiesController@store');
Route::post('facilities/update', 'FacilitiesController@update');
Route::post('facilities/delete', 'FacilitiesController@delete');
Route::post('facilities/showById', 'FacilitiesController@showById');
Route::get('facilities/show', 'FacilitiesController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// BOROUGH
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('borough/store', 'BoroughController@store');
Route::post('borough/update', 'BoroughController@update');
Route::post('borough/delete', 'BoroughController@delete');
Route::post('borough/showById', 'BoroughController@showById');
Route::get('borough/show', 'BoroughController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// DISTRICTS
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('districts/store', 'DistrictsController@store');
Route::post('districts/update', 'DistrictsController@update');
Route::post('districts/delete', 'DistrictsController@delete');
Route::post('districts/showById', 'DistrictsController@showById');
Route::post('districts/showByIdBorough', 'DistrictsController@showByIdBorough');
Route::get('districts/show', 'DistrictsController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// About Us About Us About Us
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('AboutUs/update', 'AboutUsController@update');
Route::get('AboutUs/show', 'AboutUsController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// Mosque
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque/store', 'Mosque\MosqueController@store');
Route::post('mosque/update', 'Mosque\MosqueController@update');
Route::post('mosque/delete', 'Mosque\MosqueController@delete');
Route::post('mosque/undelete', 'Mosque\MosqueController@undelete');
Route::post('mosque/showById', 'Mosque\MosqueController@showById');
Route::post('mosque/showByFilter', 'Mosque\MosqueController@showByFilter');
Route::get('mosque/show', 'Mosque\MosqueController@show');




///////////////////////////////////////////////////////////////////////////////////////////
// Mosque
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-salah-times/store', 'Mosque\MosqueSalahTimesController@store');
Route::post('mosque-salah-times/update', 'Mosque\MosqueSalahTimesController@update');
Route::post('mosque-salah-times/delete', 'Mosque\MosqueSalahTimesController@delete');
Route::post('mosque-salah-times/undelete', 'Mosque\MosqueSalahTimesController@undelete');
Route::post('mosque-salah-times/showById', 'Mosque\MosqueSalahTimesController@showById');
Route::post('mosque-salah-times/showByFilter', 'Mosque\MosqueSalahTimesController@showByFilter');
Route::get('mosque-salah-times/show', 'Mosque\MosqueSalahTimesController@show');