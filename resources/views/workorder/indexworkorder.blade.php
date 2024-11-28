@extends('layouts.main')

<style>
    table {
        width: 100%;
        border-collapse: collapse; 
    }
    th, td {
        padding: 10px;
        text-align: left;
        vertical-align: middle;
    }
    th {
        background-color: #f2f2f2;
    }
    td {
        border-bottom: 1px solid #ddd;
    }
    .status-button {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        color: #fff;
    }

    .status-done {
        background-color: #28a745; 
        color: #fff; 
    }

    .status-open {
        background-color: #ffc107; 
        color: #fff;
    }

    .status-default {
        background-color: #6c757d; 
        color: #fff; 
    }

    .form-select, .btn {
        height: calc(1.5em + 0.75rem + 2px); 
        line-height: 1.5; 
    }
</style>

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
            List Work Order
        </h5>
        <div class="d-flex align-items-center mb-3 gap-3 flex-wrap">
            <div class="flex-grow-1">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">All</option>
                    <option value="atasan">Menunggu Persetujuan Atasan (U)</option>
                    <option value="ga">Menunggu Persetujuan GA</option>
                    <option value="dataga">Menunggu Data GA</option>
                    <option value="open">Open</option>
                    <option value="close">Closed</option>
                    <option value="cancel">Cancel</option>
                </select>
            </div>
            <div class="flex-grow-1">
                <label for="filter-month" class="form-label">Month</label>
                <select id="filter-month" class="form-select">
                    <option value="">All</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="flex-grow-1">
                <label for="filter-year" class="form-label">Year</label>
                <select id="filter-year" class="form-select">
                    <option value="">All</option>
                    <!-- Menambahkan tahun dari 2020 hingga 2030 -->
                    <script>
                        for (let year = 2020; year <= 2030; year++) {
                            document.write(`<option value="${year}">${year}</option>`);
                        }
                    </script>
                </select>
            </div>
            <div class="flex-grow-1">
                <label for="filter-department" class="form-label">Department</label>
                <select id="filter-department" class="form-select">
                    <option value="">All</option>
                    <option value="finance">Finance</option>
                    <option value="hr">Human Resources</option>
                    <option value="it">IT</option>
                    <option value="sales">Sales</option>
                </select>
            </div>
            <div class="ms-2" style="padding-top: 25px">
                <a href="#" id="export-data" class="btn btn-primary">Export Data</a>
            </div>
        </div>
        <table class="display table-head-bg-primary" id="dttable4">
        <thead>
        <tr>
            <th style="display: none">ID</th>
            <th>Tanggal Pengajuan</th>
            <th>Tenggat Waktu</th>
            <th>Pekerjaan</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($workorder as $data)
            <tr>
                <td style="display: none">{{ $data->id }}</td>
                <td>{{ $data->sdate ? \Carbon\Carbon::parse($data->sdate)->format('d-m-Y') : '-' }}</td>
                <td>{{ $data->edate ? \Carbon\Carbon::parse($data->edate)->format('d-m-Y') : '-' }}</td>
                <td>{{ $data->pekerjaan ?? '-' }}</td>
                <td>
                    @if ($data->is_approve === 0)
                        <button class="btn btn-primary btn-sm"><b>Atasan</b></button>
                    @elseif($data->is_approve === 1)
                        <button class="btn btn-secondary btn-sm"><b>GA</b></button>
                    @elseif($data->is_approve === 2)
                        <button class="btn btn-success btn-sm"><b>Open</b></button>
                    @elseif($data->is_approve === 3)
                        <button class="btn btn-danger btn-sm"><b>Closed</b></button>
                    @elseif($data->is_approve === null)
                        <button class="btn btn-light btn-sm"><b>Cancel</b></button>
                    @else
                        <button class="btn btn-secondary btn-sm"><b>Unknown</b></button>
                    @endif
                </td>
                <td>
                    <button class="btn btn-info btn-sm btn-view-module"
                            data-id="{{ $data->id }}" 
                            data-no_wo="{{ $data->no_wo }}" 
                            data-pekerjaan="{{ $data->pekerjaan }} "
                            data-jenis="{{ $data->jenis }}" 
                            data-area="{{ $data->area }}" 
                            data-sdate="{{ $data->sdate }}" 
                            data-pic="{{ $data->pic }}"  
                            data-keterangan="{{ $data->keterangan }}" 
                            data-tenggat="{{ $data->tenggat }}" 
                            data-is_approve="{{ $data->is_approve }}" 
                            data-gambar="{{ $data->gambar }}" 
                            data-toggle="modal" 
                            data-target="#viewModal"
                            title="Details">
                            <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- MODAL DETAILS -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"><b>Detail Work Order</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Kolom Pertama -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold"><b>No Work Order</b></label>
                            <div id="no_wo">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Pekerjaan</b></label>
                            <div id="pekerjaan">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Area</b></label>
                            <div id="area">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Dikerjakan Oleh</b></label>
                            <div id="jenis">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Tanggal Pengajuan</b></label>
                            <div id="sdate">-</div>
                        </div>
                    </div>

                    <!-- Kolom Kedua -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold"><b>Approval Superior</b></label>
                            <div id="tanggal_masuk">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Approval GA</b></label>
                            <div id="jam_masuk">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>PIC</b></label>
                            <div id="pic">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Superior</b></label>
                            <div id="jam_keluar">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Department</b></label>
                            <div id="bertemu_dengan">-</div>
                        </div>
                    </div>

                    <!-- Kolom Ketiga -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold"><b>Keterangan</b></label>
                            <div id="keterangan">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Tenggat Waktu (Deadline)</b></label>
                            <div id="tenggat">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Status</b></label>
                            <div id="is_approve">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold"><b>Gambar</b></label>
                            <div id="gambar">-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
 $(document).ready(function() {
    var successMessage = "{{ session('success') }}";
    var errorMessage = "{{ session('error') }}";

    if (successMessage) {
        toastr.success(successMessage);
    }

    if (errorMessage) {
        toastr.error(errorMessage);
    }

    $('.btn-view-module').on('click', function() {
    const no_wo = $(this).data('no_wo');
    const pekerjaan = $(this).data('pekerjaan');
    const jenis = $(this).data('jenis');
    const area = $(this).data('area');
    const sdate = $(this).data('sdate');
    const pic = $(this).data('pic');
    const keterangan = $(this).data('keterangan');
    const tenggat = $(this).data('tenggat');
    const is_approve = $(this).data('is_approve');
    const gambar = $(this).data('gambar');

    $('#no_wo').text(no_wo || '-');
    $('#pekerjaan').text(pekerjaan || '-');
    $('#jenis').text(jenis || '-');
    $('#area').text(area || '-');
    $('#sdate').text(sdate || '-');
    $('#pic').text(pic || '-');
    $('#keterangan').text(keterangan || '-');
    $('#tenggat').text(tenggat || '-');
    $('#is_approve').text(is_approve || '-');
    $('#gambar').text(gambar || '-');

    $('#viewModal').modal('show');
    });

    var table = $('#dttable4').DataTable({
        order: [[0, 'asc']],
        columnDefs: [
            { targets: [0], visible: false }, 
            { targets: [4, 5], orderable: false } 
        ],
        responsive: true, 
        paging: true, 
        searching: true, 
        lengthChange: false,
    });

    $('#filter-status').on('change', function() {
        let status = $(this).val();
        console.log("Filtering for status:", status); 
        table.column(4).search(status, true, false).draw(); 
    });

    var modal = new bootstrap.Modal(document.getElementById('viewModal'));
    modal.hide(); 
});
</script>
@endsection
