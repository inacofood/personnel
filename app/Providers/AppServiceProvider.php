<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkOrderMail;
use App\Models\KendaraanSewa;
use App\Models\KendaraanAsset;
use App\Models\HistoryAsset;
use App\Models\HistorySewa;
use App\Models\HistoryUser;
use App\Models\ServiceAsset;
use App\Imports\KendaraanImport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $sendmail = [
        //     'namefrom' => $auth->name,
        // ];

        // Mail::to('oliviaagustina08@gmail.com')->send(new WorkOrderMail($sendmail));

        // Dapatkan tanggal 2 bulan dari sekarang
        // $twoMonthsFromNow = Carbon::now()->addMonths(2);

        // // Cek KendaraanSewa yang mendekati sewa_end_date
        // $kendaraanSewaExpiring = KendaraanSewa::whereDate('sewa_end_date', '=', $twoMonthsFromNow->format('Y-m-d'))->get();
        
        // foreach ($kendaraanSewaExpiring as $kendaraanSewa) {
        //     $sendmail = [
        //         'namefrom' => $kendaraanSewa->user->name, // Pastikan ini sesuai dengan model dan relasi Anda
        //         'details' => 'Peringatan sewa akan berakhir dalam 2 bulan.',
        //     ];

        //     Mail::to('oliviaagustina08@gmail.com')->send(new WorkOrderMail($sendmail));
        // }

        // // Cek KendaraanAsset yang mendekati satu_tahunan_end
        // $kendaraanAssetExpiring = KendaraanAsset::whereDate('satu_tahunan_end', '=', $twoMonthsFromNow->format('Y-m-d'))->get();
        
        // foreach ($kendaraanAssetExpiring as $kendaraanAsset) {
        //     $sendmail = [
        //         'namefrom' => $kendaraanAsset->user->name, // Pastikan ini sesuai dengan model dan relasi Anda
        //         'details' => 'Peringatan perpanjangan satu tahunan akan berakhir dalam 2 bulan.',
        //     ];

        //     Mail::to('oliviaagustina08@gmail.com')->send(new WorkOrderMail($sendmail));
        // }
    }
    
}
