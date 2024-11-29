@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
        <form id="sewaForm" action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_asset" value="{{ $kendaraan->id_asset ?? '' }}">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-4"><b>Master Vendor</b></h5>
                            <table id="vendorTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Vendor</th>
                                        <th>Nama PIC</th>
                                        <th>No Telepon</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendor as $v)
                                    <tr>
                                        <td>{{ $v->nama_vendor }}</td>
                                        <td>{{ $v->nama_pic }}</td>
                                        <td>{{ $v->no_telepon }}</td>
                                        <td>{{ $v->email }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm select-vendor" 
                                                data-id="{{ $v->id }}" 
                                                data-nama="{{ $v->nama_vendor }}">
                                                Pilih
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    // Fungsi untuk menangani tombol "Pilih"
    document.querySelectorAll('.select-vendor').forEach(button => {
        button.addEventListener('click', function () {
            const vendorId = this.getAttribute('data-id');
            const vendorName = this.getAttribute('data-nama');
            
            alert(`Vendor ${vendorName} dipilih!`);
            // Tambahkan logika tambahan jika diperlukan
        });
    });

    // Format Rupiah
    function formatRupiah(input) {
        let value = input.value.replace(/[^,\d]/g, '');
        const split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

        input.value = 'Rp ' + rupiah;
        document.getElementById('harga').value = value.replace(/\./g, '');
    }
</script>
@endsection
