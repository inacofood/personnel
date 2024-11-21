<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;

class InvoiceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $query = Invoice::select(
            'created_at', 
            'nama_perusahaan', 
            'tipe', 
            'no_pp', 
            'id_invoice', 
            'kategori', 
            'section', 
            'nominal', 
            'keterangan', 
            'tgl_sign_pp_invoice', 
            'tgl_input_pr_sap', 
            'tgl_approve_pr_direksi', 
            'tgl_invoice_hcm_ke_finance', 
            'tgl_email_ke_ga', 
            'tgl_ses_user', 
            'tgl_rilis_ses_user', 
            'tgl_bayar'
        );

        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$this->start_date, $this->end_date]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Perusahaan',
            'Jenis',
            'No PP',
            'ID Invoice',
            'Kategori',
            'Section',
            'Nominal',
            'Keterangan',
            'Tanggal Invoice Diterima',
            'Tgl Sign PP Invoice',
            'Tgl Input PR SAP',
            'Tgl Approve PR Direksi',
            'Tgl Invoice HCM Ke Finance',
            'Tgl Email Dari Purchasing Ke GA',
            'Tgl SES User',
            'Tgl Rilis SES User',
            'Tgl Bayar',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama_perusahaan,
            $row->tipe,
            $row->no_pp,
            $row->id_invoice,
            $row->kategori,
            $row->section,
            $row->nominal,
            $row->keterangan,
            $row->created_at->format('d-m-Y'),
            $row->tgl_sign_pp_invoice ? date('d-m-Y', strtotime($row->tgl_sign_pp_invoice)) : '',
            $row->tgl_input_pr_sap ? date('d-m-Y', strtotime($row->tgl_input_pr_sap)) : '',
            $row->tgl_approve_pr_direksi ? date('d-m-Y', strtotime($row->tgl_approve_pr_direksi)) : '',
            $row->tgl_invoice_hcm_ke_finance ? date('d-m-Y', strtotime($row->tgl_invoice_hcm_ke_finance)) : '',
            $row->tgl_email_ke_ga ? date('d-m-Y', strtotime($row->tgl_email_ke_ga)) : '',
            $row->tgl_ses_user ? date('d-m-Y', strtotime($row->tgl_ses_user)) : '',
            $row->tgl_rilis_ses_user ? date('d-m-Y', strtotime($row->tgl_rilis_ses_user)) : '',
            $row->tgl_bayar ? date('d-m-Y', strtotime($row->tgl_bayar)) : '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:R1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}
