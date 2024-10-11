@extends('layouts.main')

@section('content')
<div class="card">

    <div class="card-body">
       <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
    Data Presensi
    <button type="button" class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#importModal">
        Import Data
    </button>
</h5>
    </button></h5>
            <table class="display table-head-bg-primary" id="dttable">
                <thead>
                    <tr>
                        <th class="content" style="display: none">ID</th>
                        <th class="content">Nik</th>
                        <th class="content">Nama</th>
                        <th class="content">Department</th>
                        <th class="content">Superior</th>
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
                            <td style="display: none"> </td>
                            <td class="content">{{ $data->nik ?? null }}</td>
                            <td class="content">{{ $data->nama }}</td>
                            <td class="content">{{ $data->department }}</td>
                            <td class="content">{{ $data->nama_superior }}</td>
                            <td class="content">{{ $data->tanggal ?? null }}</td>
                            <td class="content">{{ $data->jamkerja }}</td>
                            <td class="content">{{ $data->masuk }}</td>
                            <td class="content">{{ $data->keluar }}</td>
                            <td class="content">{{ $data->terlambat ?? null }}</td>
                            <td class="content">{{ $data->pulangcepat }}</td>
                            <td class="content">{{ $data->pengecualian }}</td>
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
            var table = $('#dttable').DataTable({
                "order": [[0, 'desc']],
                "columnDefs": [
                    { "targets": 0, "visible": false } // Menyembunyikan kolom pertama
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });



        });


    </script>
@endsection

