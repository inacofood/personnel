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
    Data Temuan GA
</div>

</h5>
</h5>
    </button></h5>
    <table class="display table-head-bg-primary" id="dttable">
    <thead>
        <tr>
            <th class="content" style="display: none">ID</th>
            <th class="content">Nama</th>
            <th class="content">Departemen</th>
            <th class="content">Area</th>
            <th class="content">Spesifikasi Area</th>
            <th class="content">Unsafe Activity</th>
            <th class="content">Unsafe Environment</th>
            <th class="content">Recom</th>
            <th class="content">Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($temuan as $data)
        <tr>
            <td style="display: none"></td>
            <td class="content">{{ $data->name !== '#N/A' ? ($data->name ?? '-') : '-' }}</td>
            <td class="content">{{ $data->dept !== '#N/A' ? ($data->dept ?? '-') : '-' }}</td>
            <td class="content">{{ $data->area !== '#N/A' ? ($data->area ?? '-') : '-' }}</td>
            <td class="content">{{ $data->spec_area !== '#N/A' ? ($data->spec_area ?? '-') : '-' }}</td>
            <td class="content">{{ $data->unsafe_activity !== '#N/A' ? ($data->unsafe_activity ?? '-') : '-' }}</td>
            <td class="content">{{ $data->unsafe_envi !== '#N/A' ? ($data->unsafe_envi ?? '-') : '-' }}</td>
            <td class="content">{{ $data->recom !== '#N/A' ? ($data->recom ?? '-') : '-' }}</td>
            <td class="content">{{ $data->status !== '#N/A' ? ($data->status ?? '-') : '-' }}</td>
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

