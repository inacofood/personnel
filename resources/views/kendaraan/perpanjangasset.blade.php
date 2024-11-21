@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
        <div class="row">
            <!-- Form Section -->
            <div class="col-md-4">
                <form id="sewaForm" action="{{ route('history.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_asset" value="{{ $kendaraan->id_asset ?? '' }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5><b>Form Perpanjang</b></h5>
                                <a href="{{ route('kendaraanasset') }}" class="btn btn-sm btn-warning">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                            <div class="form-group pb-1">
                                <label for="tipe"><b>Tipe</b></label>
                                <div class="form-check">
                                    <label class="form-check-label radio-inline">
                                        <input class="form-check-input" type="radio" name="tipe" id="Pajak" value="Pajak" onclick="toggleFields('Pajak')" required>Pajak
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label radio-inline">
                                        <input class="form-check-input" type="radio" name="tipe" id="Asuransi" value="Asuransi" onclick="toggleFields('Asuransi')">Asuransi
                                    </label>
                                </div>
                            </div>
                            <div class="form-group pb-1">
                                <label for="plat_no"><b>Plat No</b></label>
                                <input name="plat_no" class="form-control" placeholder="Plat No" value="{{ old('plat_no', $kendaraan->plat_no) }}" readonly>
                            </div>
                            <div class="form-group pb-1">
                                <label for="nama_karyawan"><b>Nama</b></label>
                                <input name="nama_karyawan" class="form-control" placeholder="User" value="{{ old('nama_karyawan', $kendaraan->nama_karyawan) }}" required>
                            </div>
                            <div class="form-group pb-1">
                                <label for="harga_asset"><b>Harga</b></label>
                                <input name="harga_asset" id="harga_asset" class="form-control" placeholder="Harga" oninput="formatRupiah(this)">
                            </div>


                            <!-- File upload -->
                            <div class="form-group pb-1">
                                <label for="file"><b>Upload File</b></label>
                                <input name="file" class="form-control" type="file">
                            </div>

                            <!-- Conditional fields for Asuransi -->
                            <div class="form-group pb-1 Asuransi yes" style="display:none;">
                                <label for="no_polis_asuransi"><b>No Polis Kendaraan</b></label>
                                <input type="text" class="form-control" name="no_polis_asuransi" placeholder="No Polis Kendaraan">
                            </div>
                            <div class="form-group pb-1 Asuransi yes" style="display:none;">
                                <label for="asuransi_start_date"><b>Asuransi Start Date</b></label>
                                <input type="date" class="form-control" name="asuransi_start_date">
                            </div>
                            <div class="form-group pb-1 Asuransi yes" style="display:none;">
                                <label for="asuransi_end_date"><b>Asuransi End Date</b></label>
                                <input type="date" class="form-control" name="asuransi_end_date">
                            </div>

                            <!-- Conditional fields for Pajak -->
                            <div class="form-group pb-1 Pajak yes" style="display:none;">
                                <label for="satu_tahunan_start"><b>Satu Tahunan Start</b></label>
                                <input type="date" class="form-control" name="satu_tahunan_start">
                            </div>
                            <div class="form-group pb-1 Pajak yes" style="display:none;">
                                <label for="satu_tahunan_end"><b>Satu Tahunan End</b></label>
                                <input type="date" class="form-control" name="satu_tahunan_end">
                            </div>
                            <div class="form-group pb-1 Pajak yes" style="display:none;">
                                <label for="lima_tahunan_start"><b>Lima Tahunan Start</b></label>
                                <input type="date" class="form-control" name="lima_tahunan_start">
                            </div>
                            <div class="form-group pb-1 Pajak yes" style="display:none;">
                                <label for="lima_tahunan_end"><b>Lima Tahunan End</b></label>
                                <input type="date" class="form-control" name="lima_tahunan_end">
                            </div>

                            <div class="text-right">
                                <input type="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- History Section -->
            <div class="col-md-8">
                <!-- Riwayat Perpanjangan Asuransi -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5><b>Riwayat Perpanjangan Asuransi</b></h5>
                        <div class="table-responsive">
                            <table id="dttable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>File</th>
                                        <th>No Polis Asuransi</th>
                                        <th>Asuransi Start Date</th>
                                        <th>Asuransi End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->historyAssets as $history)
                                        @if($history->tipe === 'Asuransi')
                                            <tr>
                                                <td>{{ $history->nama_karyawan }}</td>
                                                <td>{{ 'Rp ' . number_format((float) $history->harga_asset ?? 0, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($history->file_asset)
                                                        <a href="{{ asset('storage/aset/' . $history->file_asset) }}" target="_blank">Lihat Bukti</a>
                                                    @else
                                                        Tidak ada bukti
                                                    @endif
                                                </td>
                                                <td>{{ $history->no_polis_asuransi }}</td>
                                                <td>{{ $history->asuransi_start_date ? \Carbon\Carbon::parse($history->asuransi_start_date)->format('d-m-Y') : '-' }}</td>
                                                <td>{{ $history->asuransi_end_date ? \Carbon\Carbon::parse($history->asuransi_end_date)->format('d-m-Y') : '-' }}</td>
                                                <td>
                                                    <form action="{{ route('historyasset.delete', $history->id_history_asset) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this history?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Perpanjangan Pajak -->
                <div class="card">
                    <div class="card-body">
                        <h5><b>Riwayat Perpanjangan Pajak</b></h5>
                        <div class="table-responsive">
                            <table id="dttable2" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Harga</th>
                                        <th>File</th>
                                        <th>Satu Tahunan Start</th>
                                        <th>Satu Tahunan End</th>
                                        <th>Lima Tahunan Start</th>
                                        <th>Lima Tahunan End</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->historyAssets as $history)
                                        @if($history->tipe === 'Pajak')
                                            <tr>
                                                <td>{{ $history->nama_karyawan }}</td>
                                                <td>{{ 'Rp ' . number_format((float) $history->harga_asset ?? 0, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($history->file_asset)
                                                        <a href="{{ asset('storage/aset/' . $history->file_asset) }}" target="_blank">Lihat Bukti</a>
                                                    @else
                                                        Tidak ada bukti
                                                    @endif
                                                </td>
                                                <td>{{ $history->satu_tahunan_start ? \Carbon\Carbon::parse($history->satu_tahunan_start)->format('d-m-Y') : '-' }}</td>
                                                <td>{{ $history->satu_tahunan_end ? \Carbon\Carbon::parse($history->satu_tahunan_end)->format('d-m-Y') : '-' }}</td>
                                                <td>{{ $history->lima_tahunan_start ? \Carbon\Carbon::parse($history->lima_tahunan_start)->format('d-m-Y') : '-' }}</td>
                                                <td>{{ $history->lima_tahunan_end ? \Carbon\Carbon::parse($history->lima_tahunan_end)->format('d-m-Y') : '-' }}</td>
                                                <td>
                                                    <form action="{{ route('historyasset.delete', $history->id_history_asset) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this history?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleFields(type) {
    const asuransiFields = document.querySelectorAll('.Asuransi');
    const pajakFields = document.querySelectorAll('.Pajak');
    asuransiFields.forEach(field => field.style.display = (type === 'Asuransi') ? 'block' : 'none');
    pajakFields.forEach(field => field.style.display = (type === 'Pajak') ? 'block' : 'none');
}

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
}
</script>
@endsection
