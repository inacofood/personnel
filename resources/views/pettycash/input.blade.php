@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
    <h2 style="font-size: 20px;" class="text-center">INPUT DATA PETTY CASH</h2>
        <p class="mb-5 pb-2 text-center">Hati - hati dalam input, periksa kembali sebelum submit!</p>
        <div class="col-md-6">
            <div class="form-group pb-1">
                <label for=""><b>Jenis</b></label>
                <select onchange="showMe(this.value);" class="form-control">
                    <option value="" hidden selected>--</option>
                    <option value="Pemasukan">Pemasukan</option>
                    <option value="Pengeluaran">Pengeluaran</option>
                </select>
            </div>
        </div>

        <form action="{{ route('pettycash.insert.pengeluaran') }}" method="post" id="pengeluaranForm" style="display: none;" enctype="multipart/form-data">
            @csrf
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Tanggal</b><span style="color: red;">*</span></label>
                            <input type="date" class="form-control" name="tgl" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Uraian</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="uraian" required>
                        </div>
                    </div>
                    <!-- Kategori -->
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Kategori</b><span style="color: red;">*</span></label>
                            <select name="kategori_id" class="form-control" data-live-search="true" required>
                                <option value="" hidden selected>--</option>
                                @foreach($kategori as $k)
                                    <option value="{{$k->id_kat}}">{{$k->name_kat}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Kuantitas</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control count" name="qty" id="qty" maxlength="100" onkeypress="return ((event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode ==0)" ondrop="return false;" onpaste="return false;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Satuan</b><span style="color: red;">*</span></label>
                            <select name="stn" class="form-control" data-live-search="true" required>
                                <option value="" hidden selected>--</option>
                                @foreach($satuan as $s)
                                    <option value="{{$s->name}}">{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Harga Satuan (Rp)</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control currency count" name="harga_stn" id="harga_stn" maxlength="20" onkeypress="return ((event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode ==0)" ondrop="return false;" onpaste="return false;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Total (Rp)</b></label>
                            <input type="text" class="form-control currency count" name="total" id="total" value="" maxlength="20" onkeypress="return ((event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode ==0)" ondrop="return false;" onpaste="return false;" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Cost Center</b><span style="color: red;">*</span></label>
                            <select name="cost_center" class="form-control" data-live-search="true" required>
                                <option value="" hidden selected>--</option>
                                @foreach($cc as $c)
                                    <option value="{{$c->id_cc}}">{{$c->name_cc}} - {{$c->code_cc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Keterangan</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="ket" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Kwitansi</b></label>
                            <input type="file" class="form-control" name="file">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group pb-1">
                        <button class="btn btn-primary mt-3">Submit</button>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('pettycash.insert.pemasukan') }}" method="post" id="pemasukanForm" style="display: none;">
            @csrf
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Tanggal</b><span style="color: red;">*</span></label>
                            <input type="date" class="form-control" name="tglPemasukan" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Uraian</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="uraianPemasukan" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Total (Rp)</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control currency" name="totalPemasukan" maxlength="20" onkeypress="return ((event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode ==0)" ondrop="return false;" onpaste="return false;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group pb-1">
                            <label for=""><b>Keterangan</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="ketPemasukan" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group pb-1">
                        <button class="btn btn-primary mt-3">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection


@section('script')
@if(session('message'))
<script>
    toastr.success('{{ session('message')['type'] }}');
</script>
@endif

<script>
    $(document).ready(function(){
        $(".currency").mask('000.000.000.000', {reverse: true});

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        $("#pengeluaranForm").on('input', function () {
            var qty = parseFloat($("#qty").val()) || 0;
            var price = $("#harga_stn").val().replace(/\./g, ''); 
            price = parseFloat(price) || 0;

            var total = qty * price;
            var formattedTotal = formatNumber(total);

            $('#total').val(formattedTotal);
        });
    });
</script>

<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
<script src="{{ asset ('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript">

function showMe(value) {
    if (value == "Pemasukan") {
        document.getElementById("pemasukanForm").style.display = "block";
        document.getElementById("pengeluaranForm").style.display = "none";
    } else if (value == "Pengeluaran") {
        document.getElementById("pemasukanForm").style.display = "none";
        document.getElementById("pengeluaranForm").style.display = "block";
    }
}
</script>
@endsection
