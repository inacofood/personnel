@extends('layouts.main')

<style>
    table {
        width: 100%;
        border-collapse: collapse; 
    }
    th, td {
        padding: 10px;
        text-align: center;
        vertical-align: middle;
    th {
        background-color: #f2f2f2;
    }
    td {
        border-bottom: 1px solid #ddd;
    }
</style>

@section('content')
<div class="card">
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
    Data Presensi
    <div class="ms-auto">
        <a href="{{ route('report.presensi') }}" class="btn btn-primary btn-sm">
            Report Absensi
        </a>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
            Import Data
        </button>
    </div>
</h5>

</h5>
    </button></h5>
    <table class="display table-head-bg-primary" id="dttable">
    <thead>
        <tr>
            <th class="content" style="display: none">ID</th>
            <th class="content">NIK</th>
            <th class="content">Nama</th>
            <th class="content">Department</th>
            <th class="content">Tanggal</th>
            <th class="content">Jam Kerja</th>
            <th class="content">Masuk</th>
            <th class="content">Keluar</th>
            <th class="content">Terlambat</th>
            <th class="content">Pulang Cepat</th>
            <th class="content">Pengecualian</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($presensi as $data)
        <tr>
            <td style="display: none"></td>
            <td class="content">{{ $data->nik !== '#N/A' ? ($data->nik ?? '-') : '-' }}</td>
            <td class="content">{{ $data->nama !== '#N/A' ? ($data->nama ?? '-') : '-' }}</td>
            <td class="content">{{ $data->dept !== '#N/A' ? ($data->dept ?? '-') : '-' }}</td>
            <td class="content">{{ ($data->tanggal && $data->tanggal !== '#N/A') ? \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') : '-' }}</td>
            <td class="content">{{ $data->jam_kerja !== '#N/A' ? ($data->jam_kerja ?? '-') : '-' }}</td>
            <td class="content">{{ $data->scan_masuk !== '#N/A' ? ($data->scan_masuk ?? '-') : '-' }}</td>
            <td class="content">{{ $data->scan_pulang !== '#N/A' ? ($data->scan_pulang ?? '-') : '-' }}</td>
            <td class="content">{{ $data->terlambat !== '#N/A' ? ($data->terlambat ?? '-') : '-' }}</td>
            <td class="content">{{ $data->pulang_cepat !== '#N/A' ? ($data->pulang_cepat ?? '-') : '-' }}</td>
            <td class="content">{{ $data->pengecualian !== '#N/A' ? ($data->pengecualian ?? '-') : '-' }}</td>
        </tr>
    @endforeach
</tbody>

</table>
    </div>
</div>
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('presensi.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose File</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xls,.xlsx">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
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
        });
    </script>
@endsection

