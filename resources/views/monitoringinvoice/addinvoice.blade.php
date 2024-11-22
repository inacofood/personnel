@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
    <h2 style="font-size: 20px;" class="text-center">Form Input Monitoring Invoice</h2>
        <p class="has-line"></p>
        <form method="POST" action="{{ route('store') }}">
            @csrf
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for="name"><b>Tipe</b></label>
                            <div class="form-check">
                                <label class="form-check-label radio-inline" for="flexRadioDefault1"><input class="form-check-input" type="radio" name="tipe" id="yes-pr" value="pr" onclick="pr()" required>PR</label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label radio-inline" for="flexRadioDefault2"><input class="form-check-input" type="radio" name="tipe" id="non-pr" value="nonpr" onclick="non_pr()">Non PR</label>
                            </div>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Kategori*</b></label>
                            <input name="kategori" class="form-control selectpicker" data-live-search="true" placeholder="..." required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>No PP*</b></label>
                            <input name="no_pp" class="form-control selectpicker" data-live-search="true" placeholder="..." required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>ID Invoice</b></label>
                            <input name="id_invoice" class="form-control selectpicker" data-live-search="true" placeholder="...">
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Section*</b></label>
                            <select class="form-control" aria-label="Default select example" name="section" required title="...">
                                <option value="CSR & HSE">CSR & HSE</option>
                                <option value="General Affair">General Affair</option>
                                <option value="Learning & Development">Learning & Development</option>
                                <option value="Payroll">Payroll</option>
                                <option value="Personalia">Personalia</option>
                                <option value="Recruitment">Recruitment</option>
                              </select>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Nama Perusahaan</b></label>
                            <select name="nama_perusahaan" id="perusahaan" class="form-control" data-live-search="true" title="..." style="display: block" required>
                                @foreach($perusahaan as $item)
                                <option value="{{ $item->nama_perusahaan }}">{{ $item->nama_perusahaan }}</option>
                                @endforeach
                                <option value="other">Other</option>
                            </select>
                            <input type="text" name="nama_perusahaan" id="otherPerusahaan" class="form-control" style="display: none;margin-top: 1rem" placeholder="..." required>
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Nominal*</b></label>
                            <input type="text" class="form-control" name="nominal" id="rupiah" required placeholder="...">
                        </div>
                        <div class="form-group pb-1">
                            <label for="name"><b>Keterangan*</b></label>
                            <input type="text" class="form-control" name="keterangan" required placeholder="...">
                        </div>
                        <div class="form-group pb-1 non">
                            <label for="name"><b>No PR</b></label>
                            <input type="text" class="form-control" name="no_pr" placeholder="...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Terima Invoice*</b></label>
                            <input type="date" class="form-control" name="tgl_resepsionis_terima" id="date1" required placeholder="...">
                        </div>
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Sign PP Invoice</b></label>
                            <input type="date" class="form-control" name="tgl_sign_pp_invoice" placeholder="...">
                        </div>
                        <div class="form-group pb-1 yes pr non pr">
                            <label for="name"><b>Tanggal Input SAP</b></label>
                            <input type="date" class="form-control" name="tgl_input_pr_sap">
                        </div>
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Invoice Kirim HCM ke Finance</b></label>
                            <input type="date" class="form-control" name="tgl_invoice_hcm_ke_finance" placeholder="Tanggal2">
                        </div>
                        <div class="form-group pb-1 yes pr ">
                            <label for="name"><b>Tanggal Approve Direksi</b></label>
                            <input type="date" class="form-control" name="tgl_approve_pr_direksi" placeholder="Tanggal2">
                        </div>
                        <div class="form-group pb-1 yes pr non pr">
                            <label for="name"><b>Tanggal Pengiriman Email SES dari Purchasing ke GA</b></label>
                            <input type="date" class="form-control " name="tgl_email_ke_ga"   placeholder="...">
                        </div>
                        <div class="form-group yes pr non pr">
                            <label for="name"><b>Tanggal Pengiriman Email SES dari GA ke User</b></label>
                            <input type="date" class="form-control " name="tgl_ses_user" placeholder="Tanggal2">
                        </div>
                        <div class="form-group yes pr non pr">
                            <label for="name"><b>Tanggal rilis approval SES dari User</b></label>
                            <input type="date" class="form-control " name="tgl_rilis_ses_user" placeholder="Tanggal2">
                        </div>
                        <div class="form-group yes pr">
                            <label for="name"><b>Tanggal Bayar</b></label>
                            <input type="date" class="form-control" name="tgl_bayar" placeholder="Tanggal2">
                        </div>
                    </div>
                    <br>
                    <div class="col-md-12">
                    <br>
                        <div class="text-right">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('script')
<script>
    var perusahaanSelect = document.getElementById("perusahaan");
    var otherSelect = document.getElementById("otherPerusahaan");
    perusahaanSelect.addEventListener("change", function () {
        var selectedOption = perusahaanSelect.options[perusahaanSelect.selectedIndex];
        var selectedValue = selectedOption.value;
        if (selectedValue !== "other") {
            otherSelect.style.display = "none";
            otherSelect.removeAttribute("name");
            otherSelect.removeAttribute("required");
            perusahaanSelect.setAttribute("name","nama_perusahaan");
            otherSelect.value = "";
        }else{
            otherSelect.style.display = "block";
            otherSelect.setAttribute("name","nama_perusahaan");
            otherSelect.setAttribute("required", "required");
            perusahaanSelect.removeAttribute("name");
            otherSelect.getAttribute("name");
        }
    });
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

    $('#yes-pr').click(function(){
        $(".non").show();
    });

    $( "#non-pr" ).click(function() {
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
