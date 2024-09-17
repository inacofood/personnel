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

// Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('/register', 'Auth\RegisterController@register');



Route::get('/', 'AuthController@showLoginForm')->middleware('checkUserId');
Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout')->name('logout');
Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'DashboardController@indexpayroll')->name('dashboard.payroll');
});
Route::prefix('payroll')->middleware(['auth'])->group(function () {
    Route::get('/presensi', 'PresensiController@index')->name('presensi.index');
    Route::post('/import', 'PresensiController@import')->name('presensi.import');
});

Route::prefix('lnd')->middleware(['auth'])->group(function () {
    Route::get('/hcmworld', 'HCMController@index')->name('hcm.index');
});
