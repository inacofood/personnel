@extends('layouts.main')

@section('content')
<div class="container">
    <h5 class="text-dark" style="padding-top:10px; padding-bottom: 5px"><b>Leave Details</b>&nbsp; 
        <button class="btn btn-sm btn-warning mb-3" onclick="history.back()" style="margin-top: 10px">
            <i class="fa fa-arrow-left"></i> Back
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($presensi as $index => $leave)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $leave->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->tanggal)->locale('id')->translatedFormat('l, d F Y') ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#dttable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        autoWidth: false,
        responsive: true
    });
});
</script>
@endsection
