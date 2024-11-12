@extends('layouts.main')

@section('content')
<section class="section" id="input">
    <div class="container">
        <form id="sewaForm" action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input type="hidden" name="id_asset" value="{{ $kendaraan->id_asset ?? '' }}">
            <div class="row">
                <!-- Form Column -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5><b>Service Kendaraan Asset</b></h5>
                            <button class="btn btn-sm btn-warning" onclick="history.back()">
                                <i class="fa fa-arrow-left"></i> Back
                            </button>
                        </div>
                            <br>
                            <div class="form-group pb-1">
                                <label for="km_sebelum"><b>KM Sebelum</b></label>
                                <input name="km_sebelum" class="form-control" placeholder="KM Sebelum" required>
                            </div>
                            <div class="form-group pb-1">
                                <label for="km_saat_ini"><b>KM Saat Ini</b></label>
                                <input name="km_saat_ini" class="form-control" placeholder="KM Saat Ini" required>
                            </div>  
                            <div class="form-group pb-1">
                                <label for="jenis_service"><b>Jenis Service</b></label>
                                <input name="jenis_service" class="form-control" placeholder="Jenis Service" required>
                            </div>
                            <div class="form-group pb-1">
                                <label for="vendor"><b>Vendor</b></label>
                                <input name="vendor" class="form-control" placeholder="Vendor" required>
                            </div>
                            <div class="form-group pb-1">
                                <label for="harga"><b>Harga</b></label>
                                <input name="harga" class="form-control" placeholder="Harga" required>
                            </div>
                            <div class="form-group pb-1">
                                <label for="keterangan"><b>Keterangan</b></label>
                                <input name="keterangan" class="form-control" placeholder="Keterangan" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
                <!-- Service History Column -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5><b>Riwayat Service</b></h5>
                            <br>
                            <table id="dttable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>KM Sebelum</th>
                                        <th>KM Saat Ini</th>
                                        <th>Jenis Service</th>
                                        <th>Vendor</th>
                                        <th>Harga</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->serviceAssets as $service)
                                    <tr>
                                        <td>{{ $service->km_sebelum }}</td>
                                        <td>{{ $service->km_saat_ini }}</td>
                                        <td>{{ $service->jenis_service }}</td>
                                        <td>{{ $service->vendor }}</td>
                                        <td>{{ $service->harga }}</td>
                                        <td>{{ $service->keterangan }}</td>
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
@endsection
