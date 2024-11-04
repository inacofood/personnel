@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
    <h2 style="font-size: 24px;" class="text-center">Edit Presensi</h2>
        <p class="has-line"></p>
        <form action="{{ route('presensi.update', $presensi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nik">NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $presensi->nik) }}" required>

            <label for="nama">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $presensi->nama) }}" required>

            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" value="{{ old('tanggal', $presensi->tanggal) }}" required>

            <label for="nik">Hari</label>
            <input type="text" name="week" value="{{ old('week', $presensi->week) }}" required>

            <label for="nik">Shift</label>
            <input type="text" name="jam_kerja" value="{{ old('jam_kerja', $presensi->jam_kerja) }}" required>

            <label for="nik">Jam Masuk</label>
            <input type="text" name="scan_masuk" value="{{ old('scan_masuk', $presensi->scan_masuk) }}" required>

            <label for="nik">Jam Pulang</label>
            <input type="text" name="scan_pulang" value="{{ old('scan_pulang', $presensi->scan_pulang) }}" required>

            <label for="nik">Terlambat</label>
            <input type="text" name="scan_pulang" value="{{ old('scan_pulang', $presensi->scan_pulang) }}" required>

            <label for="nik">Pulang Cepat</label>
            <input type="text" name="scan_pulang" value="{{ old('scan_pulang', $presensi->scan_pulang) }}" required>

            <label for="nik">Absent</label>
            <input type="text" name="scan_pulang" value="{{ old('scan_pulang', $presensi->scan_pulang) }}" required>

            <label for="nik">Absent</label>
            <input type="text" name="scan_pulang" value="{{ old('scan_pulang', $presensi->scan_pulang) }}" required>


            <button type="submit">Update</button>
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
