@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
        <h2 style="font-size: 24px;" class="text-center">Edit Presensi</h2>
        <p class="has-line"></p>

        <form action="{{ route('presensi.update', ['id_presensi_bulanan' => $presensi->id_presensi_bulanan]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for="nik"><b>NIK</b></label>
                            <input type="text" name="nik" class="form-control" value="{{ old('nik', $presensi->nik) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="nama"><b>Nama</b></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $presensi->nama) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="tanggal"><b>Tanggal</b></label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $presensi->tanggal) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="week"><b>Hari</b></label>
                            <input type="text" name="week" class="form-control" value="{{ old('week', $presensi->week) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="jam_kerja"><b>Shift</b></label>
                            <input type="text" name="jam_kerja" class="form-control" value="{{ old('jam_kerja', $presensi->jam_kerja) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="dept"><b>Department</b></label>
                            <input type="text" name="dept" class="form-control" value="{{ old('dept', $presensi->dept) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="section"><b>Section</b></label>
                            <input type="text" name="section" class="form-control" value="{{ old('section', $presensi->section) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="grade"><b>Grade</b></label>
                            <input type="text" name="grade" class="form-control" value="{{ old('grade', $presensi->grade) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="join_date"><b>Join Date</b></label>
                            <input type="date" name="join_date" class="form-control" value="{{ old('join_date', $presensi->join_date) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for="scan_masuk"><b>Jam Masuk</b></label>
                            <input type="time" name="scan_masuk" class="form-control" value="{{ old('scan_masuk', $presensi->scan_masuk) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="scan_pulang"><b>Jam Pulang</b></label>
                            <input type="time" name="scan_pulang" class="form-control" value="{{ old('scan_pulang', $presensi->scan_pulang) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="terlambat"><b>Terlambat</b></label>
                            <input type="text" name="terlambat" class="form-control" value="{{ old('terlambat', $presensi->terlambat) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="pulang_cepat"><b>Pulang Cepat</b></label>
                            <input type="text" name="pulang_cepat" class="form-control" value="{{ old('pulang_cepat', $presensi->pulang_cepat) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="absent"><b>Absent</b></label>
                            <input type="text" name="absent" class="form-control" value="{{ old('absent', $presensi->absent) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="pengecualian"><b>Ketidakhadiran</b></label>
                            <input type="text" name="pengecualian" class="form-control" value="{{ old('pengecualian', $presensi->pengecualian) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="HK"><b>HK</b></label>
                            <input type="text" name="HK" class="form-control" value="{{ old('HK', $presensi->HK) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="jt"><b>Jabatan</b></label>
                            <input type="text" name="jt" class="form-control" value="{{ old('jt', $presensi->jt) }}">
                        </div>

                        <div class="form-group pb-1">
                            <label for="atasan"><b>Atasan</b></label>
                            <input type="text" name="atasan" class="form-control" value="{{ old('atasan', $presensi->atasan) }}">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <input type="submit" class="btn btn-primary" value="Update">
                    <a href="javascript:history.back()" class="btn btn-warning">Back</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('script')
<script>
    $(document).ready(function() {
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

        function formatRupiah(angka, prefix) {
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
    });
</script>
@endsection

