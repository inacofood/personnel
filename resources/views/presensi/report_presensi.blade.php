@extends('layouts.main')

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@section('content')
<style>
    .select2-container--default .select2-selection--multiple {

    padding-bottom: 13px;

}
</style>
<div class="card" style="max-width: 110%; margin: auto;">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
            Report Presensi
        </h5>
        <form action="{{ route('presensi.exportExcel') }}" method="POST">
            @csrf
            <div class="row mb-3">

            <div class="col-md-3">
                <select id="namaFilter" class="form-control" name="namafilter">
                    <option value="">Pilih Nama</option>
                    @foreach ($datanama as $nama)
                        <option value="{{ $nama->nama }}">{{ $nama->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="department" class="js-example-basic-multiple form-control" name="dept[]" multiple="multiple">
                    <option value="All" selected>Select Department</option> <!-- Default -->
                    @foreach ($datadept as $item)
                        <option>{{ $item->dept }}</option>
                    @endforeach
                  </select>
            </div>

            <div class="col-md-2">
                <input type="date" id="startDate" class="form-control" name="start_date">
            </div>
            <div class="col-md-2">
                <input type="date" id="endDate" class="form-control" name="end_date">
            </div>
            <div class="col-md-2 text-right">
                <button type="submit" class="btn btn-primary">Export</button>
            </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered display" id="dttable">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Department</th>
                        <th>Grade</th>
                        <th>Bulan</th>
                        <th hidden>Bulan</th>
                        <th>Tahun</th>
                        <th>Hadir</th>
                        <th>Telat</th>
                        <th>Pulang Cepat</th>
                        <th>Absen</th>
                        <th>HK</th>
                        <th>Leave</th>
                        <th title="Sakit">S</th>
                        <th title="Sakit Tanpa Surat Dokter">STSD</th>
                        <th title="Cuti">C</th>
                        <th title="Izin">I</th>
                        <th title="Dinas Luar">DL</th>
                        <th title="Cuti Tahunan">CT</th>
                        <th title="Cuti Melahirkan">CM</th>
                        <th title="Menikah">M</th>
                        <th title="Istri Melahirkan">IM</th>
                        <th title="Anak BTIS/Sunat">AS</th>
                        <th title="OT/MTUA/KLG Meninggal">MGL</th>
                        <th title="Work From Home">WFH</th>
                        <th title="Paruh Waktu">PW</th>
                        <th title="Libur">L</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rekapKehadiran as $kehadiran)
                    <tr>
                        <td>{{ $kehadiran->nik }}</td>
                        <td>{{ $kehadiran->nama }}</td>
                        <td>{{ $kehadiran->dept }}</td>
                        <td>{{ $kehadiran->grade }}</td>
                        <td>{{ \Carbon\Carbon::create()->month($kehadiran->bulan)->translatedFormat('F') }}</td>
                        <td hidden>{{ $kehadiran->bulan }}</td>
                        <td>{{ $kehadiran->tahun }}</td>
                        <td>
                            <a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="Hadir">{{ $kehadiran->total_hadir }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="Telat">{{ $kehadiran->total_telat }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="Awal">{{ $kehadiran->total_awal }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="absent">{{ $kehadiran->total_absent }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="HK">{{ $kehadiran->total_hk }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="Leave">{{ $kehadiran->total_pengecualian }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="Sakit">{{ $kehadiran->total_sakit }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="stsd">{{ $kehadiran->total_sakit_tanpa_sd }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="cuti">{{ $kehadiran->total_cuti }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="izin">{{ $kehadiran->total_izin }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="dl">{{ $kehadiran->total_dinas_luar }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="ct">{{ $kehadiran->total_cuti_tahunan }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="cm">{{ $kehadiran->total_cuti_melahirkan }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="nikah">{{ $kehadiran->total_menikah }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="im">{{ $kehadiran->total_istri_melahirkan }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="as">{{ $kehadiran->total_anak_btis }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="mgl">{{ $kehadiran->total_ot_mtua_klg_mgl }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="wfh">{{ $kehadiran->total_wfh }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="pw">{{ $kehadiran->total_paruh_waktu }}</a></td>
                        <td><a href="#" class="show-presensi-modal"
                               data-nama="{{ $kehadiran->nama }}"
                               data-bulan="{{ $kehadiran->bulan }}"
                               data-tahun="{{ $kehadiran->tahun }}"
                               data-status="libur">{{ $kehadiran->total_libur }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Detail Presensi -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Detail Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                        </tr>
                    </thead>
                    <tbody id="modal-presensi-data">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    var table = $('#dttable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [4, 5, 6, 7, 8] }
        ],
        destroy: true,

    });
    $('#department').on('change', function () {
        var selectedValues = $(this).val(); // Mengambil nilai array dari select2 (multiple selection)

        if (selectedValues && selectedValues.length > 0) {
            // Gabungkan nilai dengan regex OR untuk mencocokkan multiple values
            var regex = selectedValues.join('|');
            table.column(2).search(regex, true, false).draw(); // Set regex, dan nonaktifkan pencarian global
        } else {
            // Jika tidak ada yang dipilih, kosongkan pencarian
            table.column(2).search('').draw();
        }
    });
    $('#namaFilter').on('change', function () {
        table.columns(1).search(this.value).draw();
        });

    $('#bulanFilter').on('change', function () {
        table.columns(3).search(this.value).draw();
    });

    $('#tahunFilter').on('change', function () {
        table.columns(4).search(this.value).draw();
    });

    $('#namaFilter, #bulanFilter, #tahunFilter').on('input change', function() {
    if ($('#namaFilter').val() === '' && $('#bulanFilter').val() === '' && $('#tahunFilter').val() === '') {
        table.search('').columns().search('').draw();
    }
    });

    $(document).on('click', '.show-presensi-modal', function(e) {
    e.preventDefault();

    var nama = $(this).data('nama');
    var bulan = $(this).data('bulan');
    var tahun = $(this).data('tahun');
    var status = $(this).data('status');

    $.ajax({
        url: '{{ route('presensi.detail') }}',
        method: 'GET',
        data: {
            nama: nama,
            bulan: bulan,
            tahun: tahun,
            status: status
        },
        success: function(response) {
            $('#modal-presensi-data').empty();
            var nomor = 1;
            function formatTanggal(tanggal) {
                if (!tanggal) return '-';
                var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                var dateObj = new Date(tanggal);
                var day = days[dateObj.getUTCDay()];
                var date = dateObj.getUTCDate();
                var month = months[dateObj.getUTCMonth()];
                var year = dateObj.getUTCFullYear();

                return `${day}, ${date} ${month} ${year}`;
            }

            $.each(response.presensi, function(index, presensi) {
                $('#modal-presensi-data').append(`
                    <tr>
                        <td>${nomor}</td> <!-- Nomor urut -->
                        <td>${formatTanggal(presensi.tanggal)}</td> <!-- Format date with day -->
                        <td>${presensi.jam_kerja || '-'}</td> <!-- Show '-' if jam_kerja is null -->
                        <td>${presensi.scan_masuk || '-'}</td> <!-- Show '-' if scan_masuk is null -->
                        <td>${presensi.scan_pulang || '-'}</td> <!-- Show '-' if scan_pulang is null -->
                    </tr>
                `);
                nomor++;
            });

            $('#editModal').modal('show');
        }
    });
});
});
</script>
@endsection
