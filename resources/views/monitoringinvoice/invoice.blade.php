@extends('layouts.main')

@section('content')
<section class="section" id="home">
    <div class="container text-center">
    <h2 style="font-size: 24px;" class="text-center">List Monitoring Invoice</h2>
        <div class="row mt-3">
            <div class="col-md-12 mt-3">
            <div class="float-right">
                <a href="{{ route('addinvoice') }}" class="btn btn-primary ml-3">Add New</a>
                <a href="#" class="btn btn-primary mr-2" data-toggle="modal" data-target="#exportModal">Export to Excel</a>
            </div>
            </div>
        </div>
        <br>
    <div class="container">
        <table id="dttable" class="table table-striped mb-0 align-middle">
        <thead>
        <tr>
            <th class="text-center" style="width: 10%;">No PP</th>
            <th class="text-center" style="width: 10%;">Section</th>
            <th class="text-center" style="width: 10%;">Kategori</th>
            <th class="text-center" style="width: 15%;">Nama Perusahaan</th>
            <th class="text-center" style="width: 10%;">Nominal</th>
            <th class="text-center" style="width: 15%;">Tanggal Sign PP</th>
            <th class="text-center" style="width: 15%;">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($invoices as $invoice)
            <tr>
                <td style="width: 10%;">{{ $invoice->no_pp }}</td>
                <td style="width: 10%;">{{ $invoice->section }}</td>
                <td style="width: 10%;">{{ $invoice->kategori }}</td>
                <td style="width: 15%;">{{ $invoice->nama_perusahaan }}</td>
                <td style="width: 10%;">{{ 'Rp. ' . number_format(floatval($invoice->nominal), 0, ',', '.') }}</td>
                <td style="width: 15%;">{{ $invoice->tgl_sign_pp_invoice }}</td>
                <td style="width: 15%;">
                    <a href="{{ route('edit', $invoice->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('delete', $invoice->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin ingin menghapus data ini ?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    <a href="{{ route('detail', $invoice->id) }}" class="btn btn-sm btn-info">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
</section>

<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Ekspor Data Monitoring Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('downloadexcel') }}" method="post" id="exportFormModal">
                    @csrf
                    <div class="form-group">
                        <label for="">Dari Tanggal</label>
                        <input type="date" class="form-control" name="start_date" placeholder="Pilih Tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="">Sampai</label>
                        <input type="date" class="form-control" name="end_date" placeholder="Pilih Tanggal" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary"><i class="fa fa-save"></i> Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<style>
   .modal-header {
    position: relative;
}
.close {
    background-color: transparent;
    padding: 0;
    border-radius: 50%;
    border: none; 
    width: 30px; 
    height: 30px; 
    position: absolute; 
    top: 50%; 
    right: 15px; 
    transform: translateY(-50%); 
    display: flex;
    justify-content: center;
    align-items: center;
}
.close span {
    color: black;
    font-size: 1.2rem; 
    line-height: 1; 
    display: inline-block; 
}
</style>
@endsection
