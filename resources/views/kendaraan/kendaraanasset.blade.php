@extends('layouts.main')

<style>
    .table-responsive {
        overflow-x: auto; 
    }
    table {
        width: 100%;
        border-collapse: collapse; 
    }
    th, td {
        padding: 10px;
        text-align: center;
        vertical-align: middle;
    }
    th {
        background-color: #f2f2f2;
    }
    td {
        border-bottom: 1px solid #ddd;
    }
</style>

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
            Kendaraan Asset
            <div class="ms-auto">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Create
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import 
                </button>
            </div>
        </h5>
    <div class="table-responsive">
    <table class="display table-head-bg-primary" id="dttable">
    <thead>
        <tr>
            <th class="text-center">Plat No</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Lokasi</th>
            <th class="text-center">Departemen</th>
            <th class="text-center">Satu Tahunan Start</th>
            <th class="text-center">Satu Tahunan End</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kendaraan as $data)
            <tr>
                <td class="content" style="text-align: left;">{{ $data->plat_no }}</td>
                <td class="content" style="text-align: left;">{{ $data->nama_karyawan }}</td>
                <td class="content" style="text-align: left;">{{ $data->lokasi }}</td>
                <td class="content" style="text-align: left;">{{ $data->dept }}</td>
                <td class="content" style="text-align: left;">{{ $data->satu_tahunan_start ? \Carbon\Carbon::parse($data->satu_tahunan_start)->format('d-m-Y') : '-' }}</td>
                <td class="content" style="text-align: left;">{{ $data->satu_tahunan_end ? \Carbon\Carbon::parse($data->satu_tahunan_end)->format('d-m-Y') : '-' }}</td>
                <td style="white-space: nowrap;">
                    <button class="btn btn-info btn-sm btn-view-module" 
                        data-plat_no="{{ $data->plat_no }}" 
                        data-nik="{{ $data->nik }}" 
                        data-nama_karyawan="{{ $data->nama_karyawan }}" 
                        data-lokasi="{{ $data->lokasi }}" 
                        data-cc="{{ $data->cc }}" 
                        data-cc_nama="{{ $data->cc_nama }}" 
                        data-dept="{{ $data->dept }}" 
                        data-grade_title="{{ $data->grade_title }}" 
                        data-merk="{{ $data->merk }}" 
                        data-tipe="{{ $data->tipe }}" 
                        data-tahun="{{ $data->tahun }}" 
                        data-jenis="{{ $data->jenis }}" 
                        data-warna="{{ $data->warna }}" 
                        data-kategori="{{ $data->kategori }}" 
                        data-no_rangka="{{ $data->no_rangka }}" 
                        data-no_mesin="{{ $data->no_mesin }}" 
                        data-no_bpkb="{{ $data->no_bpkb }}" 
                        data-asuransi_start_date="{{ $data->asuransi_start_date }}" 
                        data-asuransi_end_date="{{ $data->asuransi_end_date }}" 
                        data-vendor_asuransi="{{ $data->vendor_asuransi }}" 
                        data-no_polis_asuransi="{{ $data->no_polis_asuransi }}" 
                        data-premi_asuransi="{{ $data->premi_asuransi }}" 
                        data-satu_tahunan_start="{{ $data->satu_tahunan_start }}"
                        data-satu_tahunan_end="{{ $data->satu_tahunan_end }}" 
                        data-lima_tahunan_start="{{ $data->lima_tahunan_start }}" 
                        data-lima_tahunan_end="{{ $data->lima_tahunan_end }}" 
                        data-ket="{{ $data->ket }}"  
                        data-toggle="modal" 
                        data-target="#viewModal">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-warning btn-sm btn-edit-module" 
                        data-id="{{ $data->id_asset }}"
                        data-plat_no="{{ $data->plat_no }}" 
                        data-nik="{{ $data->nik }}" 
                        data-nama_karyawan="{{ $data->nama_karyawan }}" 
                        data-lokasi="{{ $data->lokasi }}" 
                        data-cc="{{ $data->cc }}" 
                        data-cc_nama="{{ $data->cc_nama }}" 
                        data-dept="{{ $data->dept }}" 
                        data-grade_title="{{ $data->grade_title }}" 
                        data-merk="{{ $data->merk }}" 
                        data-tipe="{{ $data->tipe }}" 
                        data-tahun="{{ $data->tahun }}" 
                        data-jenis="{{ $data->jenis }}" 
                        data-warna="{{ $data->warna }}" 
                        data-kategori="{{ $data->kategori }}" 
                        data-no_rangka="{{ $data->no_rangka }}" 
                        data-no_mesin="{{ $data->no_mesin }}" 
                        data-no_bpkb="{{ $data->no_bpkb }}" 
                        data-asuransi_start_date="{{ $data->asuransi_start_date }}" 
                        data-asuransi_end_date="{{ $data->asuransi_end_date }}" 
                        data-vendor_asuransi="{{ $data->vendor_asuransi }}" 
                        data-no_polis_asuransi="{{ $data->no_polis_asuransi }}" 
                        data-premi_asuransi="{{ $data->premi_asuransi }}" 
                        data-satu_tahunan_start="{{ $data->satu_tahunan_start }}"
                        data-satu_tahunan_end="{{ $data->satu_tahunan_end }}" 
                        data-lima_tahunan_start="{{ $data->lima_tahunan_start }}" 
                        data-lima_tahunan_end="{{ $data->lima_tahunan_end }}" 
                        data-ket="{{ $data->ket }}" 
                        data-toggle="modal" 
                        data-target="#editModal">
                        <i class="fas fa-pencil"></i>
                    </button>
                    <button class="btn btn-success btn-sm btn-sewa-module" 
                        data-nama_karyawan="{{ $data->nama_karyawan }}" 
                        data-departemen="{{ $data->departemen }}" 
                        data-masa_sewa_start="{{ $data->masa_sewa_start }}" 
                        data-masa_sewa_end="{{ $data->masa_sewa_end }}" 
                        data-id="{{ $data->id }}" 
                        data-toggle="modal" 
                        data-target="#sewaModal">
                        <i class="fas fa-book"></i> 
                    </button>
                    <button class="btn btn-primary btn-sm btn-user-module" 
                        data-nama_karyawan="{{ $data->nama_karyawan }}" 
                        data-departemen="{{ $data->departemen }}" 
                        data-id="{{ $data->id }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#userModal">
                        <i class="fas fa-wrench"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
</div>

<!-- MODAL CREATE -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form id="createForm" method="POST" action="{{ route('asset.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel"><b>Create New Asset Kendaraan</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>Plat No</b></label>
                                <input type="text" class="form-control" name="plat_no">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>NIK</b></label>
                                <input type="text" class="form-control" name="nik">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Nama Karyawan</b></label>
                                <input type="text" class="form-control" name="nama_karyawan">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Lokasi</b></label>
                                <input type="text" class="form-control" name="lokasi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>CC</b></label>
                                <input type="text" class="form-control" name="cc">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>CC Nama</b></label>
                                <input type="text" class="form-control" name="cc_nama">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Departemen</b></label>
                                <input type="text" class="form-control" name="dept">
                            </div>
                        </div>
                        <!-- Second Column -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>Grade Title</b></label>
                                <input type="text" class="form-control" name="grade_title">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Merk</b></label>
                                <input type="text" class="form-control" name="merk">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Tipe</b></label>
                                <input type="text" class="form-control" name="tipe">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Tahun</b></label>
                                <input type="text" class="form-control" name="tahun">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Jenis</b></label>
                                <input type="text" class="form-control" name="jenis">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Warna</b></label>
                                <input type="text" class="form-control" name="warna">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Kategori</b></label>
                                <input type="text" class="form-control" name="kategori">
                            </div>
                        </div>
                        <!-- Third Column -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. Rangka</b></label>
                                <input type="text" class="form-control" name="no_rangka">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. Mesin</b></label>
                                <input type="text" class="form-control" name="no_mesin">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. BPKB</b></label>
                                <input type="text" class="form-control" name="no_bpkb">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. Polis Asuransi</b></label>
                                <input type="text" class="form-control" name="no_polis_asuransi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Premi Asuransi</b></label>
                                <input type="text" class="form-control" name="premi_asuransi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Vendor Asuransi</b></label>
                                <input type="text" class="form-control" name="vendor_asuransi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Keterangan</b></label>
                                <input type="text" class="form-control" name="ket">
                            </div>
                        </div>
                        <!-- Fourth Column -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>Asuransi Start Date</b></label>
                                <input type="date" class="form-control" name="asuransi_start_date">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Asuransi End Date</b></label>
                                <input type="date" class="form-control" name="asuransi_end_date">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Satu Tahunan Start</b></label>
                                <input type="date" class="form-control" name="satu_tahunan_start">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Satu Tahunan End</b></label>
                                <input type="date" class="form-control" name="satu_tahunan_end">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Lima Tahunan Start</b></label>
                                <input type="date" class="form-control" name="lima_tahunan_start">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Lima Tahunan End</b></label>
                                <input type="date" class="form-control" name="lima_tahunan_end">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL IMPORT -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel"><b>Import File</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kendaraan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xls,.xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <form id="editForm" method="POST" action="{{ route('asset.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel"><b>Edit Data Asset Kendaraan</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>Plat No</b></label>
                                <input type="text" class="form-control" id="edit_plat_no" name="plat_no">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>NIK</b></label>
                                <input type="text" class="form-control" id="edit_nik" name="nik">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Nama Karyawan</b></label>
                                <input type="text" class="form-control" id="edit_nama_karyawan" name="nama_karyawan">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Lokasi</b></label>
                                <input type="text" class="form-control" id="edit_lokasi" name="lokasi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>CC</b></label>
                                <input type="text" class="form-control" id="edit_cc" name="cc">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>CC Nama</b></label>
                                <input type="text" class="form-control" id="edit_cc_nama" name="cc_nama">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Departemen</b></label>
                                <input type="text" class="form-control" id="edit_dept" name="dept">
                            </div>
                        </div>
                        <!-- Second Column -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>Grade Title</b></label>
                                <input type="text" class="form-control" id="edit_grade_title" name="grade_title">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Merk</b></label>
                                <input type="text" class="form-control" id="edit_merk" name="merk">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Tipe</b></label>
                                <input type="text" class="form-control" id="edit_tipe" name="tipe">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Tahun</b></label>
                                <input type="text" class="form-control" id="edit_tahun" name="tahun">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Jenis</b></label>
                                <input type="text" class="form-control" id="edit_jenis" name="jenis">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Warna</b></label>
                                <input type="text" class="form-control" id="edit_warna" name="warna">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Kategori</b></label>
                                <input type="text" class="form-control" id="edit_kategori" name="kategori">
                            </div>
                        </div>
                        <!-- Third Column -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. Rangka</b></label>
                                <input type="text" class="form-control" id="edit_no_rangka" name="no_rangka">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. Mesin</b></label>
                                <input type="text" class="form-control" id="edit_no_mesin" name="no_mesin">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. BPKB</b></label>
                                <input type="text" class="form-control" id="edit_no_bpkb" name="no_bpkb">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>No. Polis Asuransi</b></label>
                                <input type="text" class="form-control" id="edit_no_polis_asuransi" name="no_polis_asuransi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Premi Asuransi</b></label>
                                <input type="text" class="form-control" id="edit_premi_asuransi" name="premi_asuransi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Vendor Asuransi</b></label>
                                <input type="text" class="form-control" id="edit_vendor_asuransi" name="vendor_asuransi">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Keterangan</b></label>
                                <input type="text"class="form-control" id="edit_ket" name="ket">
                            </div>
                        </div>
                        <!-- Fourth Column -->
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="fw-bold"><b>Asuransi Start Date</b></label>
                                <input type="date" class="form-control" id="edit_asuransi_start_date" name="asuransi_start_date">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Asuransi End Date</b></label>
                                <input type="date" class="form-control" id="edit_asuransi_end_date" name="asuransi_end_date">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Satu Tahunan Start</b></label>
                                <input type="date" class="form-control" id="edit_satu_tahunan_start" name="satu_tahunan_start">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Satu Tahunan End</b></label>
                                <input type="date" class="form-control" id="edit_satu_tahunan_end" name="satu_tahunan_end">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Lima Tahunan Start</b></label>
                                <input type="date" class="form-control" id="edit_lima_tahunan_start" name="lima_tahunan_start">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold"><b>Lima Tahunan End</b></label>
                                <input type="date" class="form-control" id="edit_lima_tahunan_end" name="lima_tahunan_end">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAILS-->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"><b>Detail Kendaraan Asset</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
    <!-- First Column -->
    <div class="col-md-3">
        <div class="mb-3">
            <label class="fw-bold"><b>Plat No</b></label>
            <div id="plat_no"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>NIK</b></label>
            <div id="nik"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Nama Karyawan</b></label>
            <div id="nama_karyawan"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Lokasi</b></label>
            <div id="lokasi"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>CC</b></label>
            <div id="cc"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>CC Nama</b></label>
            <div id="cc_nama"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Departemen</b></label>
            <div id="dept"></div>
        </div>
    </div>
    <!-- Second Column -->
    <div class="col-md-3">
        <div class="mb-3">
            <label class="fw-bold"><b>Grade Title</b></label>
            <div id="grade_title"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Merk</b></label>
            <div id="merk"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Tipe</b></label>
            <div id="tipe"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Tahun</b></label>
            <div id="tahun"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Jenis</b></label>
            <div id="jenis"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Warna</b></label>
            <div id="warna"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Kategori</b></label>
            <div id="kategori"></div>
        </div>
    </div>
    <!-- Third Column -->
    <div class="col-md-3">
        <div class="mb-3">
            <label class="fw-bold"><b>No Rangka</b></label>
            <div id="no_rangka"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>No Mesin</b></label>
            <div id="no_mesin"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>No BPKB</b></label>
            <div id="no_bpkb"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>No Polis Asuransi</b></label>
            <div id="no_polis_asuransi"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Vendor Asuransi</b></label>
            <div id="vendor_asuransi"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Premi Asuransi</b></label>
            <div id="premi_asuransi"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Keterangan</b></label>
            <div id="ket"></div>
        </div>
    </div>
    <!-- Fourth Column -->
    <div class="col-md-3">
            <div class="mb-3">
                <label class="fw-bold"><b>Asuransi Start Date</b></label>
                <div id="asuransi_start_date"></div>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><b>Asuransi End Date</b></label>
                <div id="asuransi_end_date"></div>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><b>Satu Tahunan Start</b></label>
                <div id="satu_tahunan_start"></div>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><b>Satu Tahunan End</b></label>
                <div id="satu_tahunan_end"></div>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><b>Lima Tahunan Start</b></label>
                <div id="lima_tahunan_start"></div>
            </div>
            <div class="mb-3">
                <label class="fw-bold"><b>Lima Tahunan End</b></label>
                <div id="lima_tahunan_end"></div>
            </div>
        </div>
    </div> 
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    });

    function formatDate(date) {
    if (!date) return '-';
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}-${month}-${year}`;
}

$('.btn-view-module').on('click', function() {
    const data = {
        plat_no: $(this).data('plat_no') || '-',
        nik: $(this).data('nik') || '-',
        nama_karyawan: $(this).data('nama_karyawan') || '-',
        lokasi: $(this).data('lokasi') || '-',
        cc: $(this).data('cc') || '-',
        cc_nama: $(this).data('cc_nama') || '-',
        dept: $(this).data('dept') || '-',
        grade_title: $(this).data('grade_title') || '-',
        merk: $(this).data('merk') || '-',
        tipe: $(this).data('tipe') || '-',
        tahun: $(this).data('tahun') || '-',
        jenis: $(this).data('jenis') || '-',
        warna: $(this).data('warna') || '-',
        kategori: $(this).data('kategori') || '-',
        no_rangka: $(this).data('no_rangka') || '-',
        no_mesin: $(this).data('no_mesin') || '-',
        no_bpkb: $(this).data('no_bpkb') || '-',
        asuransi_start_date: formatDate($(this).data('asuransi_start_date')),
        asuransi_end_date: formatDate($(this).data('asuransi_end_date')),
        vendor_asuransi: $(this).data('vendor_asuransi') || '-',
        no_polis_asuransi: $(this).data('no_polis_asuransi') || '-',
        premi_asuransi: $(this).data('premi_asuransi') || '-',
        satu_tahunan_start: formatDate($(this).data('satu_tahunan_start')) || '-',
        satu_tahunan_end: formatDate($(this).data('satu_tahunan_end')) || '-',
        lima_tahunan_start: formatDate($(this).data('lima_tahunan_start')) || '-',
        lima_tahunan_end: formatDate($(this).data('lima_tahunan_end')) || '-',
        ket: $(this).data('ket') || '-'
    };
    $('id').val($(this).data('id') || '-');
    $('#plat_no').text(data.plat_no);
    $('#nik').text(data.nik);
    $('#nama_karyawan').text(data.nama_karyawan);
    $('#lokasi').text(data.lokasi);
    $('#cc').text(data.cc);
    $('#cc_nama').text(data.cc_nama);
    $('#dept').text(data.dept);
    $('#grade_title').text(data.grade_title);
    $('#merk').text(data.merk);
    $('#tipe').text(data.tipe);
    $('#tahun').text(data.tahun);
    $('#jenis').text(data.jenis);
    $('#warna').text(data.warna);
    $('#kategori').text(data.kategori);
    $('#no_rangka').text(data.no_rangka);
    $('#no_mesin').text(data.no_mesin);
    $('#no_bpkb').text(data.no_bpkb);
    $('#asuransi_start_date').text(data.asuransi_start_date);
    $('#asuransi_end_date').text(data.asuransi_end_date);
    $('#vendor_asuransi').text(data.vendor_asuransi);
    $('#no_polis_asuransi').text(data.no_polis_asuransi);
    $('#premi_asuransi').text(data.premi_asuransi);
    $('#satu_tahunan_start').text(data.satu_tahunan_start);
    $('#satu_tahunan_end').text(data.satu_tahunan_end);
    $('#lima_tahunan_start').text(data.lima_tahunan_start);
    $('#lima_tahunan_end').text(data.lima_tahunan_end);
    $('#ket').text(data.ket);

    $('#viewModal').modal('show');
});

$('.btn-edit-module').on('click', function() {
    $('#edit-id').val($(this).data('id'));
    $('#edit_plat_no').val($(this).data('plat_no'));
    $('#edit_nik').val($(this).data('nik'));
    $('#edit_nama_karyawan').val($(this).data('nama_karyawan'));
    $('#edit_lokasi').val($(this).data('lokasi'));
    $('#edit_cc').val($(this).data('cc'));
    $('#edit_cc_nama').val($(this).data('cc_nama'));
    $('#edit_dept').val($(this).data('dept'));
    $('#edit_grade_title').val($(this).data('grade_title'));
    $('#edit_merk').val($(this).data('merk'));
    $('#edit_tipe').val($(this).data('tipe'));
    $('#edit_tahun').val($(this).data('tahun'));
    $('#edit_jenis').val($(this).data('jenis'));
    $('#edit_warna').val($(this).data('warna'));
    $('#edit_kategori').val($(this).data('kategori'));
    $('#edit_no_rangka').val($(this).data('no_rangka'));
    $('#edit_no_mesin').val($(this).data('no_mesin'));
    $('#edit_no_bpkb').val($(this).data('no_bpkb'));
    $('#edit_no_polis_asuransi').val($(this).data('no_polis_asuransi'));
    $('#edit_premi_asuransi').val($(this).data('premi_asuransi'));
    $('#edit_vendor_asuransi').val($(this).data('vendor_asuransi'));
    $('#edit_asuransi_start_date').val($(this).data('asuransi_start_date'));
    $('#edit_asuransi_end_date').val($(this).data('asuransi_end_date'));
    $('#edit_satu_tahunan_start').val($(this).data('satu_tahunan_start'));
    $('#edit_satu_tahunan_end').val($(this).data('satu_tahunan_end'));
    $('#edit_lima_tahunan_start').val($(this).data('lima_tahunan_start'));
    $('#edit_lima_tahunan_end').val($(this).data('lima_tahunan_end'));
    $('#edit_ket').val($(this).data('ket'));
    
    $('#editModal').modal('show');
});

$('#btn-edit-submit').on('click', function() {
    $('#editForm').submit();
});

$('#viewModal, #editModal, #sewaModal, #userModal').on('hidden.bs.modal', function() {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    });
</script>
@endsection
