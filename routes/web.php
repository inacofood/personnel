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
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\DashboardController;

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
    Route::get('/leave/detail', [DashboardController::class, 'showLeaveDetails'])->name('detail.count.dashboard');
    Route::get('/late/detail', [DashboardController::class, 'getLateRecords'])->name('detail.late.dashboard');
    Route::get('/awal/detail', [DashboardController::class, 'getAwalRecords'])->name('detail.awal.dashboard');
    Route::get('/dinasluar/detail', [DashboardController::class, 'getDinasLuarData'])->name('detail.dinasluar.dashboard');
    Route::get('/leave/terbanyak', [DashboardController::class, 'leaveterbanyak'])->name('detail.lima.leave');
    Route::get('/kehadiran/detail', [DashboardController::class, 'kehadiranDetail'])->name('kehadiran.detail');
    Route::get('/leave/mingguan/detail', [DashboardController::class, 'LeaveMingguanDetail'])->name('leave.mingguan.detail');
    Route::get('/late/mingguan/detail', [DashboardController::class, 'LateMingguanDetail'])->name('late.mingguan.detail');
    Route::get('/kategori/detail', [DashboardController::class, 'getLeaveDetail'])->name('leave.detail');
    Route::get('/absent/detail', [DashboardController::class, 'getAbsentData'])->name('absent.detail');
    Route::get('/wfh/detail', [DashboardController::class, 'getWFH'])->name('wfh.detail');
});
Route::prefix('presensi')->middleware(['auth'])->group(function () {
    Route::get('/presensi', 'PresensiController@index')->name('presensi.index');
    Route::post('/import', 'PresensiController@import')->name('presensi.import');
    Route::get('/reportpresensi', 'PresensiController@rekapPresensiBulanan')->name('report.presensi');
    Route::post('presensi/export-excel', [PresensiController::class, 'exportExcel'])->name('presensi.exportExcel');
    Route::get('/presensi/detail', [PresensiController::class, 'getPresensiDetail'])->name('presensi.detail');
    Route::get('/presensi/rekap/{tahun}/{bulan}', [PresensiController::class, 'rekapPresensiBulanan'])->name('presensi.rekap');
    Route::get('/rekap-piechart', [PresensiController::class, 'rekapPieChart'])->name('rekap.piechart');
    Route::get('/editpresensi/{id_presensi_bulanan}', [PresensiController::class, 'editpresensi'])->name('presensi.edit');
    Route::put('/updatepresensi/{id_presensi_bulanan}', [PresensiController::class, 'updatepresensi'])->name('presensi.update');
    Route::delete('/deletepresensi/{id_presensi_bulanan}', [PresensiController::class, 'deletepresensi'])->name('presensi.delete');
    
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

    Route::get('/kendaraanasset', [KendaraanController::class, 'indexasset'])->name('kendaraanasset');
    Route::get('/kendaraansewa', [KendaraanController::class, 'indexsewa'])->name('kendaraansewa');
    Route::post('/import-kendaraan', [KendaraanController::class, 'importKendaraan'])->name('kendaraan.import');
    Route::post('/import-kendaraan-sewa', [KendaraanController::class, 'importKendaraanSewa'])->name('kendaraan.sewa.import');
    Route::put('/update-data-sewa', [KendaraanController::class, 'updatesewa'])->name('sewa.update');
    Route::put('/update-user-sewa', [KendaraanController::class, 'updateuser'])->name('user.update');
    Route::put('/perpanjang-sewa', [KendaraanController::class, 'perpanjangsewa'])->name('perpanjangsewa');
    Route::put('/update-data-asset', [KendaraanController::class, 'updateasset'])->name('asset.update');
    Route::post('/create-data-asset', [KendaraanController::class, 'createasset'])->name('asset.store');
    Route::post('/create-data-sewa', [KendaraanController::class, 'createsewa'])->name('sewa.store');
    Route::get('/kendaraan/{id}/perpanjangasset', [KendaraanController::class, 'perpanjangasset'])->name('perpanjangasset');
    Route::post('/kendaraan/history/store', [KendaraanController::class, 'HistoryAsset'])->name('history.store');    
    Route::post('/service', [KendaraanController::class, 'service'])->name('service.store');
    Route::get('/serviceasset/{id}', [KendaraanController::class, 'serviceasset'])->name('service.form');
    Route::delete('/delete-history-asset/{id_history_asset}', [KendaraanController::class, 'deleteHistoryAsset'])->name('historyasset.delete');








    

    







