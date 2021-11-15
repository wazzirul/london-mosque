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
Route::post('admin/unDelete', 'AdminController@unDelete');
Route::post('admin/login', 'AdminController@login');
Route::post('admin/showById', 'AdminController@showById');
Route::get('admin/show', 'AdminController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// USERS
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('users/store', 'UsersController@store');
Route::post('users/update', 'UsersController@update');
Route::post('users/delete', 'UsersController@delete');
Route::post('users/unDelete', 'UsersController@unDelete');
Route::post('users/login', 'UsersController@login');
Route::post('users/resend-verif-account', 'UsersController@resendVerifAccount');
Route::get('users/verif-account', 'UsersController@verifAccount');
Route::post('users/logout', 'UsersController@logout');
Route::post('users/showById', 'UsersController@showById');
Route::get('users/show', 'UsersController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// USERS DONATION
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('users-donation/store', 'UsersDonationController@store');
Route::post('users-donation/update', 'UsersDonationController@update');
Route::post('users-donation/delete', 'UsersDonationController@delete');
Route::post('users-donation/unDelete', 'UsersDonationController@unDelete');
Route::post('users-donation/showById', 'UsersDonationController@showById');
Route::post('users-donation/showByIdUser', 'UsersDonationController@showByIdUser');
Route::get('users-donation/show', 'UsersDonationController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// FACILITIES
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('facilities/store', 'FacilitiesController@store');
Route::post('facilities/update', 'FacilitiesController@update');
Route::post('facilities/delete', 'FacilitiesController@delete');
Route::post('facilities/unDelete', 'FacilitiesController@unDelete');
Route::post('facilities/showById', 'FacilitiesController@showById');
Route::get('facilities/show', 'FacilitiesController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// BOROUGH
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('borough/store', 'BoroughController@store');
Route::post('borough/update', 'BoroughController@update');
Route::post('borough/delete', 'BoroughController@delete');
Route::post('borough/unDelete', 'BoroughController@unDelete');
Route::post('borough/showById', 'BoroughController@showById');
Route::get('borough/show', 'BoroughController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// DISTRICTS
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('districts/store', 'DistrictsController@store');
Route::post('districts/update', 'DistrictsController@update');
Route::post('districts/delete', 'DistrictsController@delete');
Route::post('districts/unDelete', 'DistrictsController@unDelete');
Route::post('districts/showById', 'DistrictsController@showById');
Route::post('districts/showByIdBorough', 'DistrictsController@showByIdBorough');
Route::get('districts/show', 'DistrictsController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// DONATIONS
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('donations/store', 'DonationsController@store');
Route::post('donations/update', 'DonationsController@update');
Route::post('donations/delete', 'DonationsController@delete');
Route::post('donations/unDelete', 'DonationsController@unDelete');
Route::post('donations/showById', 'DonationsController@showById');
Route::post('donations/showByIdUsersDonation', 'DonationsController@showByIdUsersDonation');
Route::get('donations/show', 'DonationsController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// SUGGEST MOSQUE
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('suggest-mosque/store', 'SuggestMosque/SuggestMosqueController@store');
Route::post('suggest-mosque/update', 'SuggestMosque/SuggestMosqueController@update');
Route::post('suggest-mosque/delete', 'SuggestMosque/SuggestMosqueController@delete');
Route::post('suggest-mosque/unDelete', 'SuggestMosque/SuggestMosqueController@unDelete');
Route::post('suggest-mosque/showById', 'SuggestMosque/SuggestMosqueController@showById');
Route::post('suggest-mosque/showByIdUsers', 'SuggestMosque/SuggestMosqueController@showByIdUsers');
Route::post('suggest-mosque/showByIdMosque', 'SuggestMosque/SuggestMosqueController@showByIdMosque');
Route::get('suggest-mosque/show', 'SuggestMosque/SuggestMosqueController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// About Us About Us About Us
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('AboutUs/update', 'AboutUsController@update');
Route::get('AboutUs/show', 'AboutUsController@show');