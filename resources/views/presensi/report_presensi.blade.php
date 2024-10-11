@extends('layouts.main')

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


@section('content')
<div class="card" style="max-width: 110%; margin: auto;">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
            Report Presensi
        </h5>
        <!-- Filters Section -->
        <form action="{{ route('presensi.exportExcel') }}" method="POST">
            @csrf
            <div class="row mb-3">
            <div class="col-md-4">
                <select id="namaFilter" class="form-control" name="namafilter">
                    <option value="">Pilih Nama</option>
                    @foreach ($datanama as $nama)
                        <option value="{{ $nama->nama }}">{{ $nama->nama }}</option>
                    @endforeach
                </select>
            </div>
                <div class="col-md-3">
                    <select id="bulanFilter" class="form-control" name="bulanfilter">
                        <option value="">Pilih Bulan</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="tahunFilter" class="form-control" name="tahunfilter">
                        <option value="">Pilih Tahun</option>
                        @foreach (range(date('Y'), 2000) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary">Export Excel</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered display" id="dttable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Bulan</th>
                        <th hidden>Bulan</th>
                        <th>Tahun</th>
                        <th>Hadir</th>
                        <th>Telat</th>
                        <th>Pulang Cepat</th>
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
                        <td>{{ $kehadiran->nama }}</td>
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
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal</th>
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
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#dttable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [4, 5, 6, 7, 8] }
        ],
        destroy: true // Allows reinitialization of DataTable if necessary
    });

    // Filter by name
    $('#namaFilter').on('change', function () {
        table.columns(0).search(this.value).draw();
        });
    // Filter by month
    $('#bulanFilter').on('change', function () {
        table.columns(2).search(this.value).draw();
        table.columns(2).search(this.value).draw();
    });

    // Filter by year
    $('#tahunFilter').on('change', function () {
        table.columns(3).search(this.value).draw();
    });

    // Reset filters if all inputs are empty
    $('#namaFilter, #bulanFilter, #tahunFilter').on('input change', function() {
    if ($('#namaFilter').val() === '' && $('#bulanFilter').val() === '' && $('#tahunFilter').val() === '') {
        table.search('').columns().search('').draw(); // Reset all filters
    }
    });

    // Event delegation for showing presensi modal
    $(document).on('click', '.show-presensi-modal', function(e) {
        e.preventDefault();

        var nama = $(this).data('nama');
        var bulan = $(this).data('bulan');
        var tahun = $(this).data('tahun');
        var status = $(this).data('status');

        // AJAX request to fetch presensi details
        $.ajax({
            url: '{{ route('presensi.detail') }}', // Use the route helper to ensure the route is correct
            method: 'GET',
            data: {
                nama: nama,
                bulan: bulan,
                tahun: tahun,
                status: status
            },
            success: function(response) {
                // Clear previous modal content
                $('#modal-presensi-data').empty();

                // Iterate through presensi data and append it to the modal
                $.each(response.presensi, function(index, presensi) {
                    $('#modal-presensi-data').append(`
                        <tr>
                            <td>${presensi.nama}</td>
                            <td>${presensi.tanggal}</td>
                            <td>${presensi.scan_masuk}</td>
                            <td>${presensi.scan_pulang}</td>
                        </tr>
                    `);
                });

                // Show the modal
                $('#editModal').modal('show');
            }
        });
    });
});
</script>

@endsection
