<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $sendmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendmail)
    {
        $this->sendmail = $sendmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('[Notifikasi Kendaraan]')
        // ->view('mail.alert')->with([
        //     'sendmail'      => $this->sendmail,
        // ]);

        return $this->subject('[Notifikasi Kendaraan]')
            ->view('mail.alert')
            ->with([
                'sendmail' => $this->sendmail,
            ])
            ->attach(storage_path('app/public/files/file.pdf')); // Lampirkan file

    }
}
