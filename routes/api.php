<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/rekappresensi', [App\Http\Controllers\Api\RekapPresensiController::class, 'index']);
Route::get('/rekappresensi/{id}', [App\Http\Controllers\Api\RekapPresensiController::class, 'show']);
Route::middleware('auth:api')->get('/user', [UserController::class, 'show']);
