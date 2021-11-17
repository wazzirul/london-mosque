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
Route::post('suggest-mosque/store', 'SuggestMosque\SuggestMosqueController@store');
Route::post('suggest-mosque/update', 'SuggestMosque\SuggestMosqueController@update');
Route::post('suggest-mosque/delete', 'SuggestMosque\SuggestMosqueController@delete');
Route::post('suggest-mosque/unDelete', 'SuggestMosque\SuggestMosqueController@unDelete');
Route::post('suggest-mosque/showById', 'SuggestMosque\SuggestMosqueController@showById');
Route::get('suggest-mosque/show', 'SuggestMosque\SuggestMosqueController@show');
Route::post('suggest-mosque/showByFilter', 'SuggestMosque\SuggestMosqueController@showByFilter');


///////////////////////////////////////////////////////////////////////////////////////////
// SUGGEST MOSQUE FACILITIES
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('suggest-mosque/facilities/store', 'SuggestMosque\SuggestMosqueFacilitiesController@store');
Route::post('suggest-mosque/facilities/update', 'SuggestMosque\SuggestMosqueFacilitiesController@update');
Route::post('suggest-mosque/facilities/delete', 'SuggestMosque\SuggestMosqueFacilitiesController@delete');
Route::post('suggest-mosque/facilities/unDelete', 'SuggestMosque\SuggestMosqueFacilitiesController@unDelete');
Route::post('suggest-mosque/facilities/showById', 'SuggestMosque\SuggestMosqueFacilitiesController@showById');
Route::get('suggest-mosque/facilities/show', 'SuggestMosque\SuggestMosqueFacilitiesController@show');
Route::post('suggest-mosque/facilities/showByFilter', 'SuggestMosque\SuggestMosqueFacilitiesController@showByFilter');


///////////////////////////////////////////////////////////////////////////////////////////
// SUGGEST MOSQUE IMAGE
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('suggest-mosque/image/store', 'SuggestMosque\SuggestMosqueImageController@store');
Route::post('suggest-mosque/image/update', 'SuggestMosque\SuggestMosqueImageController@update');
Route::post('suggest-mosque/image/delete', 'SuggestMosque\SuggestMosqueImageController@delete');
Route::post('suggest-mosque/image/unDelete', 'SuggestMosque\SuggestMosqueImageController@unDelete');
Route::post('suggest-mosque/image/showById', 'SuggestMosque\SuggestMosqueImageController@showById');
Route::get('suggest-mosque/image/show', 'SuggestMosque\SuggestMosqueImageController@show');
Route::post('suggest-mosque/image/showByFilter', 'SuggestMosque\SuggestMosqueImageController@showByFilter');


///////////////////////////////////////////////////////////////////////////////////////////
// SUGGEST MOSQUE SALAH TIMES
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('suggest-mosque/salah-times/store', 'SuggestMosque\SuggestMosqueSalahTimesController@store');
Route::post('suggest-mosque/salah-times/update', 'SuggestMosque\SuggestMosqueSalahTimesController@update');
Route::post('suggest-mosque/salah-times/delete', 'SuggestMosque\SuggestMosqueSalahTimesController@delete');
Route::post('suggest-mosque/salah-times/unDelete', 'SuggestMosque\SuggestMosqueSalahTimesController@unDelete');
Route::post('suggest-mosque/salah-times/showById', 'SuggestMosque\SuggestMosqueSalahTimesController@showById');
Route::get('suggest-mosque/salah-times/show', 'SuggestMosque\SuggestMosqueSalahTimesController@show');
Route::post('suggest-mosque/salah-times/showByFilter', 'SuggestMosque\SuggestMosqueSalahTimesController@showByFilter');


///////////////////////////////////////////////////////////////////////////////////////////
// SUGGEST MOSQUE JUMMAH TIMES
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('suggest-mosque/jummah-times/store', 'SuggestMosque\SuggestMosqueJummahTimesController@store');
Route::post('suggest-mosque/jummah-times/update', 'SuggestMosque\SuggestMosqueJummahTimesController@update');
Route::post('suggest-mosque/jummah-times/delete', 'SuggestMosque\SuggestMosqueJummahTimesController@delete');
Route::post('suggest-mosque/jummah-times/unDelete', 'SuggestMosque\SuggestMosqueJummahTimesController@unDelete');
Route::post('suggest-mosque/jummah-times/showById', 'SuggestMosque\SuggestMosqueJummahTimesController@showById');
Route::get('suggest-mosque/jummah-times/show', 'SuggestMosque\SuggestMosqueJummahTimesController@show');
Route::post('suggest-mosque/jummah-times/showByFilter', 'SuggestMosque\SuggestMosqueJummahTimesController@showByFilter');


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
// Mosque Salah Times
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-salah-times/store', 'Mosque\MosqueSalahTimesController@store');
Route::post('mosque-salah-times/update', 'Mosque\MosqueSalahTimesController@update');
Route::post('mosque-salah-times/delete', 'Mosque\MosqueSalahTimesController@delete');
Route::post('mosque-salah-times/undelete', 'Mosque\MosqueSalahTimesController@undelete');
Route::post('mosque-salah-times/showById', 'Mosque\MosqueSalahTimesController@showById');
Route::post('mosque-salah-times/showByFilter', 'Mosque\MosqueSalahTimesController@showByFilter');
Route::get('mosque-salah-times/show', 'Mosque\MosqueSalahTimesController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// Mosque Facilities
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-facilities/store', 'Mosque\MosqueFacilitiesController@store');
Route::post('mosque-facilities/update', 'Mosque\MosqueFacilitiesController@update');
Route::post('mosque-facilities/delete', 'Mosque\MosqueFacilitiesController@delete');
Route::post('mosque-facilities/undelete', 'Mosque\MosqueFacilitiesController@undelete');
Route::post('mosque-facilities/showById', 'Mosque\MosqueFacilitiesController@showById');
Route::post('mosque-facilities/showByFilter', 'Mosque\MosqueFacilitiesController@showByFilter');
Route::get('mosque-facilities/show', 'Mosque\MosqueFacilitiesController@show');



///////////////////////////////////////////////////////////////////////////////////////////
// Mosque Facilities
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-facilities/store', 'Mosque\MosqueFacilitiesController@store');
Route::post('mosque-facilities/update', 'Mosque\MosqueFacilitiesController@update');
Route::post('mosque-facilities/delete', 'Mosque\MosqueFacilitiesController@delete');
Route::post('mosque-facilities/undelete', 'Mosque\MosqueFacilitiesController@undelete');
Route::post('mosque-facilities/showById', 'Mosque\MosqueFacilitiesController@showById');
Route::post('mosque-facilities/showByFilter', 'Mosque\MosqueFacilitiesController@showByFilter');
Route::get('mosque-facilities/show', 'Mosque\MosqueFacilitiesController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// Mosque Jummah Times
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-jummah-times/store', 'Mosque\MosqueJummahTimeController@store');
Route::post('mosque-jummah-times/update', 'Mosque\MosqueJummahTimeController@update');
Route::post('mosque-jummah-times/delete', 'Mosque\MosqueJummahTimeController@delete');
Route::post('mosque-jummah-times/undelete', 'Mosque\MosqueJummahTimeController@undelete');
Route::post('mosque-jummah-times/showById', 'Mosque\MosqueJummahTimeController@showById');
Route::post('mosque-jummah-times/showByFilter', 'Mosque\MosqueJummahTimeController@showByFilter');
Route::get('mosque-jummah-times/show', 'Mosque\MosqueJummahTimeController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// Mosque Image
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-image/store', 'Mosque\MosqueImageController@store');
Route::post('mosque-image/update', 'Mosque\MosqueImageController@update');
Route::post('mosque-image/delete', 'Mosque\MosqueImageController@delete');
Route::post('mosque-image/undelete', 'Mosque\MosqueImageController@undelete');
Route::post('mosque-image/showById', 'Mosque\MosqueImageController@showById');
Route::post('mosque-image/showByFilter', 'Mosque\MosqueImageController@showByFilter');
Route::get('mosque-image/show', 'Mosque\MosqueImageController@show');


///////////////////////////////////////////////////////////////////////////////////////////
// Mosque Favorite
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-favorite/favorite', 'Mosque\MosqueFavoriteController@favorite');
Route::post('mosque-favorite/unfavorite', 'Mosque\MosqueFavoriteController@unfavorite');
Route::post('mosque-favorite/showByFilter', 'Mosque\MosqueFavoriteController@showByFilter');


///////////////////////////////////////////////////////////////////////////////////////////
// Mosque Review
///////////////////////////////////////////////////////////////////////////////////////////
Route::post('mosque-review/store', 'Mosque\MosqueReviewController@store');
Route::post('mosque-review/update', 'Mosque\MosqueReviewController@update');
Route::post('mosque-review/delete', 'Mosque\MosqueReviewController@delete');
Route::post('mosque-review/undelete', 'Mosque\MosqueReviewController@undelete');
Route::post('mosque-review/showById', 'Mosque\MosqueReviewController@showById');
Route::post('mosque-review/showByFilter', 'Mosque\MosqueReviewController@showByFilter');
Route::get('mosque-review/show', 'Mosque\MosqueReviewController@show');