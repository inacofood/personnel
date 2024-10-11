@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
    <h2 style="font-size: 24px;" class="text-center">Edit Form Monitoring Invoice</h2>
        <p class="has-line"></p>

        <form method="POST" action="{{ route('updateinvoice') }}">
            @csrf
            <div class="container">
                <div class="row mb-4">
                    @foreach ($invoices as $invoice)
                    <input type="hidden" name="id" value="{{$invoice->id}}">
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for="name"><b>Tipe*</b></label>
                            <div class="form-check">
                                <label class="form-check-label radio-inline" for="flexRadioDefault1"><input class="form-check-input" type="radio" name="tipe" id="yes-pr" value="pr" onclick="pr()" {{ $invoice->tipe == 'pr' ? 'checked' : '' }} required>PR</label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label radio-inline" for="flexRadioDefault2"><input class="form-check-input" type="radio" name="tipe" id="non-pr" value="nonpr" onclick="non_pr()" {{ $invoice->tipe == 'nonpr' ? 'checked' : '' }}>Non PR</label>
                            </div>
                            <div class="form-group">
                                <p class="text-muted"><span class="text-danger">*</span> Hati - hati dalam mengubah kategori<br/>Setelah submit, tanggal kategori Non-PR akan hilang</p>
                            </div>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Kategori*</b></label>
                            <input name="kategori" class="form-control selectpicker" value="{{$invoice->kategori}}" data-live-search="true" placeholder="..." required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>No PP*</b></label>
                            <input name="no_pp" class="form-control selectpicker" value="{{$invoice->no_pp}}" data-live-search="true" placeholder="..." required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>ID Invoice</b></label>
                            <input name="id_invoice" class="form-control selectpicker" data-live-search="true" value="{{$invoice->id_invoice}}">
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Section*</b></label>
                            <select class="form-select form-control" aria-label="Default select example" name="section" required>
                                <option value="" disabled>...</option>
                                <option value="CSR & HSE" {{ $invoice->section == 'CSR & HSE' ? 'selected' : '' }}>CSR & HSE</option>
                                <option value="GA Manager" {{ $invoice->section == 'GA Manager' ? 'selected' : '' }}>GA Manager</option>
                                <option value="General Affair" {{ $invoice->section == 'General Affair' ? 'selected' : '' }}>General Affair</option>
                                <option value="Learning & Development" {{ $invoice->section == 'Learning & Development' ? 'selected' : '' }}>Learning & Development</option>
                                <option value="Payroll" {{ $invoice->section == 'Payroll' ? 'selected' : '' }}>Payroll</option>
                                <option value="Personalia" {{ $invoice->section == 'Personalia' ? 'selected' : '' }}>Personalia</option>
                                <option value="Recruitment" {{ $invoice->section == 'Recruitment' ? 'selected' : '' }}>Recruitment</option>
                              </select>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Nama Perusahaan*</b></label>
                            <input type="text" class="form-control" name="nama_perusahaan" id="perusahaan" value="{{$invoice->nama_perusahaan}}" required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Nominal*</b></label>
                            <input type="text" class="form-control" name="nominal" id="rupiah" value="{{'Rp. ' . number_format(floatval($invoice->nominal), 0, ',', '.') }}" required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Keterangan*</b></label>
                            <input type="text" class="form-control" name="keterangan" value="{{$invoice->keterangan}}" required>
                        </div>
                        <div class="form-group pb-1 non">
                            <label for="name"><b>No PR</b></label>
                            <input type="text" class="form-control" name="no_pr" value="{{$invoice->no_pr}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Terima Invoice*</b></label>
                            <input type="date" class="form-control" name="tgl_resepsionis_terima" id="date1" value="{{$invoice->tgl_resepsionis_terima}}" required>
                        </div>
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Sign PP Invoice</b></label>
                            <input type="date" class="form-control" name="tgl_sign_pp_invoice" value="{{$invoice->tgl_sign_pp_invoice}}">
                        </div>
                        <div class="form-group pb-1 yes pr non pr">
                            <label for="name"><b>Tanggal Input PR SAP</b></label>
                            <input type="date" class="form-control" name="tgl_input_pr_sap" value="{{$invoice->tgl_input_pr_sap}}">
                        </div>
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Approve PR Direksi</b></label>
                            <input type="date" class="form-control" name="tgl_approve_pr_direksi" value="{{$invoice->tgl_approve_pr_direksi}}">
                        </div>
                        <div class="form-group pb-1 yes pr">
                            <label for="name"><b>Tanggal Invoice Kirim HCM ke Finance</b></label>
                            <input type="date" class="form-control" name="tgl_invoice_hcm_ke_finance" value="{{$invoice->tgl_invoice_hcm_ke_finance}}">
                        </div>
                        <div class="form-group pb-1 yes pr non pr">
                            <label for="name"><b>Tanggal Pengiriman Email SES dari Purchasing ke GA</b></label>
                            <input type="date" class="form-control" name="tgl_email_ke_ga"   value="{{$invoice->tgl_email_ke_ga}}">
                        </div>
                        <div class="form-group yes pr non pr">
                            <label for="name"><b>Tanggal Pengiriman Email SES dari GA ke User</b></label>
                            <input type="date" class="form-control" name="tgl_ses_user" value="{{$invoice->tgl_ses_user}}">
                        </div>
                        <div class="form-group yes pr non pr">
                            <label for="name"><b>Tanggal rilis approval SES dari User</b></label>
                            <input type="date" class="form-control" name="tgl_rilis_ses_user" value="{{$invoice->tgl_rilis_ses_user     }}">
                        </div>
                        <div class="form-group yes pr">
                            <label for="name"><b>Tanggal Bayar</b></label>
                            <input type="date" class="form-control" name="tgl_bayar" value="{{$invoice->tgl_bayar}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="text-right">
                            <input type="submit" class="btn btn-primary" id="submit" value="Submit">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('script')
<script>
    if (document.getElementById('non-pr').checked) {
        $( "#non-pr" ).ready(function() {
            $(".non").hide();
        });
    } else {
        $('#yes-pr').ready(function() {
            $(".non").show();
        });
    }

    $('#yes-pr').click(function() {
        $(".non").show();

    });

    $("#non-pr").click(function() {
        $(".non").hide();

    });

    var rupiah = document.getElementById("rupiah");
    rupiah.addEventListener("keyup", function(e) {
    rupiah.value = formatRupiah(this.value, "Rp. ");
    });

    function formatRupiah(angka, prefix)
    {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }
</script>
@endsection
