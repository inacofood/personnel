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
            Data Temuan GA
        </h5>
        <div class="d-flex align-items-center mb-3">
            <div class="flex-grow-1">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">Semua</option>
                    <option value="done">Done</option>
                    <option value="open">Open</option>
                </select>
            </div>
            <div class="ms-2" style="padding-top: 25px">
                <a href="#" id="export-data" class="btn btn-primary" >Export Data</a>
            </div>
        </div>
        <table class="display table-head-bg-primary" id="dttable4">
            <thead>
                <tr>
                    <th class="content" style="display: none">ID</th>
                    <th class="content">Tanggal Input</th>
                    <th class="content">Tanggal Perubahan Status</th>
                    <th class="content">Nama Inputer</th>
                    <th class="content">Lokasi Temuan Spesifik</th>
                    <th class="content">File Laporan</th>
                    <th class="content">File Penyelesaian</th>
                    <th class="content">Status</th>
                    <th class="content">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($temuan as $data)
                    <tr>
                        <td style="display: none"></td>
                        <td class="content">{{ $data->created_at !== '#N/A' ? ($data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') : '-') : '-' }}</td>
                        <td class="content">{{ $data->updated_at !== '#N/A' ? ($data->updated_at ? \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') : '-') : '-' }}</td>
                        <td class="content">{{ $data->name !== '#N/A' ? ($data->name ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->area !== '#N/A' ? ($data->area ?? '-') : '-' }}</td>
                        <td class="content">
                            @if ($data->file_name !== '#N/A' && $data->file_name)
                                <a href="{{ asset('/' . $data->file_name) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="content">
                            @if ($data->file_done_name !== '#N/A' && $data->file_done_name)
                                <a href="{{ asset('/' . $data->file_done_name) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="content">
                            <span class="status-button 
                                {{ strtolower($data->status) == 'done' ? 'status-done' : (strtolower($data->status) == 'open' ? 'status-open' : 'status-default') }}">
                                {{ $data->status }}
                            </span>
                        </td>
                        <td class="content">
                            <button class="btn btn-info btn-sm btn-view-module"
                                    data-id="{{ $data->id }}" 
                                    data-unsafe_envi="{{ $data->unsafe_envi }}" 
                                    data-recom="{{ $data->recom }}" 
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
                <h5 class="modal-title" id="viewModalLabel"><b>Detail Temuan</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="fw-bold" style="font-size: 16px; font-family: Arial, sans-serif;"><b>Temuan Keadaan</b></label>
                            <textarea class="form-control" id="modal-unsafe-envi" readonly 
                                      style="font-size: 16px; font-family: Arial, sans-serif; line-height: 1.5; height: 120px;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold" style="font-size: 16px; font-family: Arial, sans-serif;"><b>Rekomendasi</b></label>
                            <textarea class="form-control" id="modal-recom" readonly 
                                      style="font-size: 16px; font-family: Arial, sans-serif; line-height: 1.5; height: 120px;"></textarea>
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
        const unsafeEnvi = $(this).data('unsafe_envi');  
        const recom = $(this).data('recom');

        $('#modal-unsafe-envi').val(unsafeEnvi || '-'); 
        $('#modal-recom').val(recom || '-'); 
    });


    $(document).ready(function() {
    var table = $('#dttable4').DataTable({
        order: [[0, 'asc']],
        columnDefs: [
            { targets: [0], visible: false }, 
        ],
    });

    $('#filter-status').on('change', function() {
        let status = $(this).val();
        console.log("Filtering for status:", status); 
        table.column(7).search(status, true, false).draw(); 
    });
    });

    var modal = new bootstrap.Modal(document.getElementById('viewModal'));
    modal.hide(); 

    $('#export-data').on('click', function(e) {
        e.preventDefault();
        let status = $('#filter-status').val();
        let url = "{{ route('temuanga.export') }}"; 
        if (status) {
            url += `?status=${status}`; 
        }
        window.location.href = url;
    });
});
</script>
@endsection
