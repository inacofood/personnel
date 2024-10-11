<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersroleController;
use App\Http\Controllers\EmoduleController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\MonitoringinvoiceController;
use App\Http\Controllers\PettycashController;
use App\Http\Controllers\PresensiController;

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
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
Route::prefix('payroll')->middleware(['auth'])->group(function () {
    Route::get('/presensi', 'PresensiController@index')->name('presensi.index');
    Route::post('/import', 'PresensiController@import')->name('presensi.import');
    Route::get('/reportpresensi', 'PresensiController@rekapPresensiBulanan')->name('report.presensi');
    Route::post('presensi/export-excel', [PresensiController::class, 'exportExcel'])->name('presensi.exportExcel');
    Route::get('/presensi/detail', [PresensiController::class, 'getPresensiDetail'])->name('presensi.detail');




});

    Route::get('/hcmworld', 'HCMController@index')->name('hcm.index');
    Route::get('/usersrole', [UsersroleController::class, 'index'])->name('usersrole');
    Route::post('/usersrole', [UsersroleController::class, 'store'])->name('usersrole.store');
    Route::get('/usersrole/{id}/edit', [UsersroleController::class, 'edit'])->name('usersrole.edit');
    Route::put('/usersrole/{id}', [UsersroleController::class, 'update'])->name('usersrole.update');
    Route::delete('/usersrole/{id_users}', [UsersroleController::class, 'destroy'])->name('usersrole.destroy');    

    Route::get('/roles', [RoleController::class, 'index'])->name('role');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    
    Route::get('/emodule', [EmoduleController::class, 'index'])->name('emodule');
    Route::post('/add-new-module', [InputController::class, 'addNewModule'])->name('add');
    Route::get('/subcategory-options', [EmoduleController::class, 'getOptions']);
    Route::put('/update-data', [EmoduleController::class, 'update'])->name('update');
    Route::get('/emodule/get-modal-data', [EmoduleController::class, 'getModalData'])->name('get-modal-data');    
    Route::delete('/destroy/{id}', [EmoduleController::class, 'destroy'])->name('destroy');
    Route::post('/import-from-excel', [InputController::class, 'importFromExcel'])->name('import');
    Route::get('/export-list-links', [EmoduleController::class, 'export'])->name('export');
    Route::get('/download', 'InputController@Download');

    Route::get('/users/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/invoice', [MonitoringinvoiceController::class, 'index'])->name('invoice');
    Route::get('/{id}/detail', [MonitoringinvoiceController::class,'detail'])->name('detail');
    Route::delete('/{id}/delete', [MonitoringinvoiceController::class,'delete'])->name('delete');
    Route::get('/{id}/edit', [MonitoringinvoiceController::class,'edit'])->name('edit');
    Route::get('/input', [MonitoringinvoiceController::class,'show'])->name('addinvoice');
    Route::post('/store', [MonitoringinvoiceController::class, 'add'])->name('store');    
    Route::post('/updateinvoice', [MonitoringinvoiceController::class, 'updateinvoice'])->name('updateinvoice');
    Route::get('/exportinvoice', [MonitoringinvoiceController::class, 'show'])->name('exportinvoice');
    Route::post('/downloadexcel', [MonitoringinvoiceController::class, 'downloadexcel'])->name('downloadexcel');
    
    Route::get('/pettycash', [PettycashController::class, 'index'])->name('home');
    Route::get('/inputpettycash', [PettycashController::class, 'inputindex'])->name('pettycash.input');
    Route::post('/insertpemasukan', [PettycashController::class, 'insertpemasukan'])->name('pettycash.insert.pemasukan');
    Route::post('/insertpengeluaran', [PettycashController::class, 'insertpengeluaran'])->name('pettycash.insert.pengeluaran');
    Route::post('/updatepemasukan', [PettycashController::class, 'updatepemasukan'])->name('pettycash.update.pemasukan');
    Route::post('/updatepengeluaran', [PettycashController::class, 'updatepengeluaran'])->name('pettycash.update.pengeluaran');
    Route::post('/deletepemasukan', [PettycashController::class, 'deletepemasukan'])->name('pettycash.delete.pemasukan');
    Route::post('/deletepengeluaran', [PettycashController::class, 'deletepengeluaran'])->name('pettycash.delete.pengeluaran');
    Route::post('/exportexcel', [PettycashController::class, 'indexexport'])->name('pettycash.export');
    Route::post('/export-excel', [PettycashController::class, 'exportexcel'])->name('exportexcel');
    Route::post('/export-in-excel', [PettycashController::class, 'exportinexcel'])->name('exportinexcel');
    Route::post('/export-all-excel', [PettycashController::class, 'exportallexcel'])->name('exportallexcel');
    Route::get('/export-pp', [PettycashController::class, 'exportpp'])->name('exportpp');
    Route::get('/import', [PettycashController::class, 'indeximport'])->name('importpp');
    Route::post('/import-cc', [PettycashController::class, 'importcc'])->name('importcc');




