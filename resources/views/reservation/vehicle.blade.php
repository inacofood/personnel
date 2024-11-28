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
            Reservation Vehicle
        </h5>
        <div class="d-flex justify-content-end gap-4 mb-6">
            <form action="{{ route('vehicle.export') }}" method="GET" class="mb-3">
                <div class="d-flex gap-3 align-items-end">
                    <div class="d-flex flex-column" style="width: 250px;">
                        <label for="date_start" class="form-label">Start Date</label>
                        <input type="date" name="date_start" id="date_start" class="form-control" style="width: 100%;" required>
                    </div>
                    <div class="d-flex flex-column" style="width: 250px;">
                        <label for="date_end" class="form-label">End Date</label>
                        <input type="date" name="date_end" id="date_end" class="form-control" style="width: 100%;" required>
                    </div>
                    <div class="d-flex flex-column align-items-end" style="padding-bottom: 3px">
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="display table-head-bg-primary" id="dttable">
            <thead>
                <tr>
                    <th class="content" style="display: none">ID</th>
                    <th class="content">Input Date</th>
                    <th class="content">Date</th>
                    <th class="content">Time</th>
                    <th class="content">PIC</th>
                    <th class="content">Departemen</th>
                    <th class="content">Needs</th>
                    <th class="content">Driver</th>
                    <th class="content">Nama Driver</th>
                    <th class="content">Opsi Lain</th>
                    <th class="content">Biaya</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vehicle as $data)
                    <tr>
                        <td style="display: none"></td>
                        <td class="content">{{ $data->created_at !== '#N/A' ? ($data->created_at ? \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') : '-') : '-' }}</td>
                        <td class="content">{{ $data->date_start !== '#N/A' ? ($data->date_start ? \Carbon\Carbon::parse($data->date_start)->format('d-m-Y') : '-') : '-' }}</td>
                        <td class="content">
                            {{ $data->start_time && $data->end_time ? $data->start_time . ' - ' . $data->end_time : '-' }}
                        </td>
                        <td class="content">{{ $data->pic !== '#N/A' ? ($data->pic ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->dept !== '#N/A' ? ($data->dept ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->needs !== '#N/A' ? ($data->needs ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->driver !== '#N/A' ? ($data->driver ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->nama_driver !== '#N/A' ? ($data->nama_driver ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->opsi !== '#N/A' ? ($data->opsi ?? '-') : '-' }}</td>
                        <td class="content">{{ $data->biaya !== '#N/A' ? ($data->biaya ?? '-') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    
    });
</script>
@endsection
