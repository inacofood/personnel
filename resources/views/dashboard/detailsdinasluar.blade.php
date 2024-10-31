@extends('layouts.main')

@section('content')
<div class="container">
    <h5 class="text-dark" style="padding-top:10px; padding-bottom: 5px"><b>Detail Dinas Luar</b>&nbsp; 
    <button class="btn btn-sm btn-warning mb-3" onclick="history.back()" style="margin-top: 10px">
        <i class="fa fa-arrow-left"></i>
         Back
    </button>
    </h5>
    <div class="row">
        <div class="col-md-12 mt-4">   
            <table id="dttable" class="table table-bordered table-hover">
                <thead class="bg-success text-white">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Scan Masuk</th>
                        <th>Scan Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dinasLuarDetails as $index => $dinasluar)
                    <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dinasluar->nama ?? '-' }}</td>
                    <td>{{ $dinasluar->tanggal ?? '-' }}</td>
                    <td>{{ $dinasluar->scan_masuk ?? '-' }}</td>
                    <td>{{ $dinasluar->scan_pulang ?? '-' }}</td>
                    </tr>
                     @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#dttable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [4, 5, 6, 7, 8] } 
        ],
        destroy: true
    });
});
</script>
@endsection
@endsection
