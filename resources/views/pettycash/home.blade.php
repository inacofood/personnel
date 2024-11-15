@extends('layouts.main')
@section('content')
<section class="section">
    <div class="container">
        <p class="has-line"></p>
        <div class="row mt-3">
            <div class="col-md-12 mt-3">
            <div class="d-flex justify-content-end" style="padding:10px">
            <a href="{{ route('pettycash.input') }}" class="btn btn-primary">Add New</a>
            <a href="#" class="btn btn-primary" style="margin-left:10px;" data-toggle="modal" data-target="#modalExport">
                Export to Excel
            </a>
            <button class="btn btn-primary" style="margin-left:10px;" data-toggle="modal" data-target="#modalExportPP">Cetak PP</button>
            </div>
            </div>
        </div>
        <br>
        <div class="text-center">
            <ul class="nav nav-pills nav-justified">
                <li class="nav-item">
                  <a class="nav-link active" id="btnKlr" href="#">Pengeluaran</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="btnMsk" href="#">Pemasukan</a>
                </li>
              </ul>
        </div>

        {{-- BEGIN::Table Data Pengeluaran --}}
        <br>
        <div class="has-line" id="divKlr">
            <div class="row mb-4">
                <div class="col-md-6">
                    <span class="display-4 text-center" style="font-size:25px; font-weight:bold; color:red; margin-bottom:20px;">Total Pengeluaran : Rp. </span>
                    <span class="display-4 text-center currency" style="font-size:25px; font-weight:bold; color:red; margin-bottom:20px;">{{ number_format($totalKlr, 0, ',', '.') }}</span>
                </div>
                <div class="col-md-6 text-right">
                    <span class="display-4 text-center" style="font-size:25px; font-weight:bold;">Saldo : Rp. </span>
                    <span class="display-4 text-center currency" style="font-size:25px; font-weight:bold;">{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
            <table id="dttable" class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>Uraian</th>
                        <th>Qty</th>
                        <th>Stn</th>
                        <th>Harga Stn (Rp)</th>
                        <th>Total (Rp)</th>
                        <th>Keterangan</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                    <tr>
                        <td>{{ $d->tgl }}</td>
                        <td>{{ $d->uraian }}</td>
                        <td>{{ $d->qty }}</td>
                        <td>{{ $d->stn }}</td>
                        <td>{{ number_format($d->harga_stn, 0, ',', '.') }}</td>
                        <td>{{ number_format($d->total, 0, ',', '.') }}</td>
                        <td>{{ $d->ket }}</td>
                        <td>
                            <!-- Your buttons for edit, delete, view -->
                            <a data-toggle="modal" href="#modalEdit" class="openModalEdit"
                            data-id="{{$d->id}}"
                            data-tgl="{{$d->tgl}}"
                            data-uraian="{{$d->uraian}}"
                            data-kategori="{{$d->kategori_id}}" 
                            data-qty="{{$d->qty}}"
                            data-stn="{{$d->stn}}"
                            data-harga_stn="{{$d->harga_stn}}"
                            data-total="{{$d->total}}"
                            data-cost_center="{{$d->cost_center_id}}"
                            data-ket="{{$d->ket}}">
                                <button class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                                </button>
                            </a>
                            <a data-toggle="modal" href="#modalDel" class="openModalDel1" data-id="{{$d->id}}">
                                <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                                </button>
                            </a>
                            @if($d->filename)
                            <a target="_blank" href="{{ asset('uploads/kwitansi/'. $d->filename) }}">
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        {{-- END::Table Data Pengeluaran --}}

        {{-- BEGIN::Table Data Pemasukan --}}
        <br>
        <div class="has-line" id="divMsk">
            <div class="row mb-4">
                <div class="col-md-6">
                    <span class="display-4 text-center" style="font-size:25px; font-weight:bold; color:rgb(10, 194, 10); margin-bottom:20px;">Total Pemasukan : Rp. </span>
                    <span class="display-4 text-center currency" style="font-size:25px; font-weight:bold; color:rgb(10, 194, 10); margin-bottom:20px;">{{ number_format($totalMsk, 0, ',', '.') }}</span>
                </div>
                <div class="col-md-6 text-right">
                    <span class="display-4 text-center" style="font-size:25px; font-weight:bold;">Saldo : Rp. </span>
                    <span class="display-4 text-center currency" style="font-size:25px; font-weight:bold;">{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
            <table id="dttable2" class="table table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Uraian</th>
                    <th>Total (Rp)</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data2 as $d)
                <tr>
                    <td>{{ $d->tgl }}</td>
                    <td>{{ $d->uraian }}</td>
                    <td>{{ number_format($d->total, 0, ',', '.') }}</td>
                    <td>{{ $d->ket }}</td>
                    <td>
                        <a data-toggle="modal" href="#modalEdit2" class="openModalEdit2"
                        data-id="{{$d->id}}"
                        data-tgl="{{$d->tgl}}"
                        data-uraian="{{$d->uraian}}"
                        data-total="{{$d->total}}"
                        data-ket="{{$d->ket}}">
                            <button class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i>
                            </button>
                        </a>
                        <a data-toggle="modal" href="#modalDel2" class="openModalDel2" data-id="{{$d->id}}">
                        <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
{{-- END::Data Pemasukan --}}

{{-- BEGIN::Modal Edit Pengeluaran --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary"> 
                <h5 class="modal-title" id="modalEditLabel">Edit Pengeluaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButton">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pettycash.update.pengeluaran') }}" id="pengeluaranForm" method="POST">
                    @csrf
                    <input type="hidden" name="idEdit" id="idEdit" value="">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="tglEdit"><b>Tanggal</b><span style="color: red;">*</span></label>
                                <input type="date" class="form-control" name="tglEdit" id="tglEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="uraianEdit"><b>Judul</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="uraianEdit" id="uraianEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="kategori_idEdit"><b>Kategori</b><span style="color: red;">*</span></label>
                                <select name="kategori_idEdit" id="kategori_idEdit" class="form-control">
                                    @foreach($kategori as $k)
                                        <option value="{{$k->id_kat}}">{{$k->name_kat}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="qtyEdit"><b>Quantity</b><span style="color: red;">*</span></label>
                                <input type="number" class="form-control" name="qtyEdit" id="qtyEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="stnEdit"><b>Satuan</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="stnEdit" id="stnEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="harga_stnEdit"><b>Harga Satuan (Rp)</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control currency" name="harga_stnEdit" id="harga_stnEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="totalEdits"><b>Total (Rp)</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control currency" name="totalEdit" id="totalEdits" value="" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="cost_centerEdit"><b>Cost Center</b><span style="color: red;">*</span></label>
                                <select name="cost_centerEdit" id="cost_centerEdit" class="form-control" required>
                                    @foreach($cc as $c)
                                        <option value="{{$c->id_cc}}">{{$c->name_cc}} - {{$c->code_cc}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for="ketEdit"><b>Keterangan</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="ketEdit" id="ketEdit" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END::Modal Edit Pengeluaran --}}


{{-- BEGIN::Modal Edit Pemasukan --}}
<div class="modal fade" id="modalEdit2" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary"> 
            <h5 class="modal-title" id="modalEditLabel">Edit Pemasukan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pettycash.update.pemasukan') }}" id="pemasukanForm" method="POST">
                    @csrf
                    <input type="hidden" name="idEdit" id="idEdit" value="">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for=""><b>Tanggal</b><span style="color: red;">*</span></label>
                                <input type="date" class="form-control" name="tglEdit" id="tglEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for=""><b>Judul</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="uraianEdit" id="uraianEdit" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for=""><b>Total (Rp)</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control currency" name="totalEdit" id="totalEdit" maxlength="20" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode ==0)" ondrop="return false;" onpaste="return false;" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for=""><b>Keterangan</b><span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="ketEdit" id="ketEdit" required>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END::Modal Edit Pemasukan --}}


{{-- BEGIN::Modal Delete Pengeluaran --}}
<div class="modal fade" id="modalDel" tabindex="-1" role="dialog" aria-labelledby="modalDelLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDelLabel2">Hapus Pengeluaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pettycash.delete.pengeluaran') }}" method="POST">
                    @csrf
                    <input type="hidden" id="idDel" name="idDel" value="">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END::Modal Delete Pengeluaran --}}

{{-- BEGIN::Modal Delete Pemasukan --}}
<div class="modal fade" id="modalDel2" tabindex="-1" role="dialog" aria-labelledby="modalDelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDelLabel">Hapus Pemasukan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pettycash.delete.pemasukan') }}" method="POST">
                    @csrf
                    <input type="hidden" id="idDel2" name="idDel" value="">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END::Modal Delete Pemasukan --}}


{{-- BEGIN::Modal Export Excel --}}
<div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Export Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group pb-1">
                            <label for=""><b>Jenis</b></label>
                            <select onchange="showExport(this.value);" class="form-control">
                                <option value="" hidden selected>--</option>
                                <option value="All">All</option>
                                <option value="Pemasukan">Pemasukan</option>
                                <option value="Pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Export Pengeluaran -->
                <form action="{{ route('exportexcel') }}" method="post" id="pengeluaranExport" style="display: none;">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label><b>Dari Tanggal</b></label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label><b>Sampai Tanggal</b></label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary mt-3">Export</button>
                    </div>
                </form>

                <!-- Export Pemasukan -->
                <form action="{{ route('exportinexcel') }}" method="post" id="pemasukanExport" style="display: none;">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label><b>Dari Tanggal</b></label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label><b>Sampai Tanggal</b></label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary mt-3">Export</button>
                    </div>
                </form>

                <!-- Export All -->
                <form action="{{ route('exportallexcel') }}" method="post" id="allExport" style="display: none;">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label><b>Dari Tanggal</b></label>
                                <input type="date" class="form-control" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label><b>Sampai Tanggal</b></label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary mt-3">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END::Modal Export Excel --}}

{{-- BEGIN::Modal Cetak PP --}}
<div class="modal fade" id="modalExportPP" tabindex="-1" role="dialog" aria-labelledby="exportModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="modal">Cetak PP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('exportpp') }}" method="get" id="" target="_blank">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for=""><b>Dari Tanggal</b></label>
                                <input type="date" class="form-control" name="start_date" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group pb-1">
                                <label for=""><b>Sampai Tanggal</b></label>
                                <input type="date" class="form-control" name="end_date" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary mt-3">Print</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END::Modal Cetak PP --}}
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<!-- <script>
    $(document).ready(function() {
        $('#dttable1').DataTable({
            order: [[0, 'asc']]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#dttable2').DataTable({
            order: [[0, 'asc']]
        });
    });
</script> -->

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

<script>
    $(document).ready(function(){

        $(".currency").mask('000.000.000.000.000.000.000.000', {reverse: true});

        $("#pengeluaranForm").on('input', function () {
            var qty = $("#qtyEdit").val();
            var price = $("#harga_stnEdit").val();

            price = price.replaceAll('.','');
            total = parseInt(qty * price);

            $('#totalEdits').attr("value",total);
        });
    })
</script>

<script>
 $(document).on("click", ".openModalEdit", function() {
    var id = $(this).data('id');
    var tgl = $(this).data('tgl');
    var uraian = $(this).data('uraian');
    var kategori = $(this).data('kategori'); // Pastikan ini benar
    var qty = $(this).data('qty');
    var stn = $(this).data('stn');
    var harga_stn = $(this).data('harga_stn');
    var total = $(this).data('total');
    var cost_center = $(this).data('cost_center'); 
    var ket = $(this).data('ket');

    // Set nilai di input form
    $("#modalEdit #idEdit").val(id);
    $("#modalEdit #tglEdit").val(tgl);
    $("#modalEdit #uraianEdit").val(uraian);
    $("#modalEdit #qtyEdit").val(qty);
    $("#modalEdit #stnEdit").val(stn);
    $("#modalEdit #harga_stnEdit").val(harga_stn);
    $("#modalEdit #totalEdits").val(total);
    $("#modalEdit #ketEdit").val(ket);

    // Set selected value di dropdown kategori
    $("#modalEdit #kategori_idEdit").val(kategori).change(); // Pastikan change() untuk trigger update select
    $("#modalEdit #cost_centerEdit").val(cost_center).change(); // Pastikan change() untuk trigger update select


    // Tampilkan modal
    $('#modalEdit').modal('show');
});

    $(document).on("click", ".openModalEdit2", function() {
        var id = $(this).data('id');
        var tgl = $(this).data('tgl');
        var uraian = $(this).data('uraian');
        var total = $(this).data('total');
        var ket = $(this).data('ket');

        $("#modalEdit2 #idEdit").val(id);
        $("#modalEdit2 #tglEdit").val(tgl);
        $("#modalEdit2 #uraianEdit").val(uraian);
        $("#modalEdit2 #totalEdit").val(total);
        $("#modalEdit2 #ketEdit").val(ket);
        
        $('#modalEdit2').modal('show');
        $('#modalEdit2').on('hidden.bs.modal', function () {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
});
    });
</script>

<script>
   $(document).on("click", ".openModalDel1", function() {
    var id = $(this).data('id');
    $("#idDel").val(id);
    });

    $(document).ready(function() {
    $(document).on("click", ".openModalDel2", function() {
        var id = $(this).data('id');
        $("#idDel2").val(id); 
    });
});
</script>

<script>
    function showExport(value) {
        document.getElementById('pengeluaranExport').style.display = 'none';
        document.getElementById('pemasukanExport').style.display = 'none';
        document.getElementById('allExport').style.display = 'none';
        
        if (value === 'Pengeluaran') {
            document.getElementById('pengeluaranExport').style.display = 'block';
        } else if (value === 'Pemasukan') {
            document.getElementById('pemasukanExport').style.display = 'block';
        } else if (value === 'All') {
            document.getElementById('allExport').style.display = 'block';
        }
    }
</script>

<script>
    $(document).ready(function(){
        $('#modalExportPP').on('show.bs.modal', function (e) {
            console.log("Modal is opened!");
        });
    });
</script>


<script>
    $("#btnKlr").click(function(){
        $('#btnMsk').removeClass('active');
        $(this).addClass('active');
        event.preventDefault();

        $("#divKlr").show();
        $("#divMsk").hide();
    });

    $("#btnMsk").click(function(){
        $('#btnKlr').removeClass('active');
        $(this).addClass('active');
        event.preventDefault();

        $("#divMsk").show();
        $("#divKlr").hide();
    });

    $('#closeModalButton').click(function() {
    $('#modalEdit').modal('hide');
    $('.modal-backdrop').remove();
});
</script>

@endsection
