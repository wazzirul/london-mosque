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

Route::get('ping', function() {
    return 'PONGs';
});

Route::post('login-proses', 'Docs\LoginDocsController@login_proses'); //login proses
Route::get('login','Docs\LoginDocsController@login')->name('login');
Route::get('logout','Docs\LoginDocsController@logout')->name('logout');