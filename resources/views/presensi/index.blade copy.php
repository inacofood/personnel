@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Data Presensi</h5>
            <table class="display table-head-bg-primary" id="dttable">
                <thead>
                    <tr>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#dttable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endsection
