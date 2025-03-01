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
        text-align: left;
        vertical-align: middle;
    }
    th {
        background-color: #f2f2f2;
    }
    td {
        border-bottom: 1px solid #ddd;
    }
    th.start-date {
      width: 150px;
    }
    th.end-date {
      width: 150px;
    }

    @keyframes blinkYellowBackground {
    0% { background-color: yellow; }
    50% { background-color: transparent; }
    100% { background-color: yellow; }
    }

    @keyframes blinkRedBackground {
        0% { background-color: red; }
        50% { background-color: transparent; }
        100% { background-color: red; }
    }

    .blink-yellow {
        animation: blinkYellowBackground 1s ease-in-out 5;
        color: black; 
    }

    .blink-red {
        animation: blinkRedBackground 1s ease-in-out 5;
        color: black; 
    }

    .text-red {
        color: red;
    }

</style>

@section('content')
<div class="card">
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
            Kendaraan Sewa
            <div class="ms-auto">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Create
                </button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    Import
                </button>
                <button type="button" class="btn btn-warning btn-sm">
                    Vendor
                </button>
            </div>
        </h5>
        <div class="table-responsive">
        <table class="display table-head-bg-primary" id="dttable">
                <thead>
                    <tr>
                        <th class="text-center">Plat No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Departemen</th>
                        <th class="text-center">Vendor</th>
                        <th class="text-center">No Telepon</th>
                        <th class="text-center start-date">Start Date</th>
                        <th class="text-center end-date">End Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="kendaraanTableBody">
                    @foreach ($kendaraan as $data)
                    @php
                        $masaSewaEnd = \Carbon\Carbon::parse($data->masa_sewa_end);
                        $isNearExpiry = $masaSewaEnd->diffInMonths(now()) <= 2;
                        $isExpired = $masaSewaEnd->isPast();

                        $rowClass = '';
                        if ($data->status == 'Non Aktif') {
                            $rowClass = 'text-red'; 
                        } elseif ($isExpired && $data->status == 'Aktif') {
                            $rowClass = 'blink-red'; 
                        } elseif ($isNearExpiry && $data->status == 'Aktif') {
                            $rowClass = 'blink-yellow';
                        }
                    @endphp
                      <tr class="{{ $rowClass }}">
                  <td class="text-left">{{ $data->plat_no }}</td>
                  <td class="text-left">{{ $data->nama_karyawan }}</td>
                  <td class="text-left">{{ $data->departemen }}</td>
                  <td class="text-left">{{ $data->vendor }}</td>
                  <td class="text-left">{{ $data->no_tlp }}</td>
                  <td class="text-left">{{ \Carbon\Carbon::parse($data->masa_sewa_start)->format('d-m-Y') }}</td>
                  <td class="text-left">{{ \Carbon\Carbon::parse($data->masa_sewa_end)->format('d-m-Y') }}</td>
                  <td class="text-left">{{ $data->status }}</td>
                  <td class="text-left" style="white-space: nowrap;">
                      <button class="btn btn-info btn-sm btn-view-module" 
                              data-plat_no="{{ $data->plat_no }}" 
                              data-nik="{{ $data->nik }}" 
                              data-nama_karyawan="{{ $data->nama_karyawan }}" 
                              data-lokasi="{{ $data->lokasi }}" 
                              data-cc="{{ $data->cc }}" 
                              data-cc_nama="{{ $data->cc_nama }}" 
                              data-departemen="{{ $data->departemen }}" 
                              data-vendor="{{ $data->vendor }}" 
                              data-grade_title="{{ $data->grade_title }}" 
                              data-no_tlp="{{ $data->no_tlp }}" 
                              data-merk="{{ $data->merk }}" 
                              data-tipe="{{ $data->tipe }}" 
                              data-tahun="{{ $data->tahun }}" 
                              data-jenis="{{ $data->jenis }}" 
                              data-harga_sewa="{{ $data->harga_sewa }}" 
                              data-harga_sewa_ppn="{{ $data->harga_sewa_ppn }}" 
                              data-masa_sewa_start="{{ $data->masa_sewa_start }}" 
                              data-masa_sewa_end="{{ $data->masa_sewa_end }}" 
                              data-end_date_h_empatlima="{{ $data->end_date_h_empatlima }}" 
                              data-alert_masa_sewa="{{ $data->alert_masa_sewa }}" 
                              data-status="{{ $data->status }}" 
                              data-note_to_do="{{ $data->note_to_do }}" 
                              data-ket="{{ $data->ket }}"
                              data-kondisi="{{ $data->kondisi }}"  
                              data-pic_vendor="{{ $data->pic_vendor }}" 
                              data-kontak_vendor="{{ $data->kontak_vendor }}"
                              data-foto_tanda_terima="{{ $data->foto_tanda_terima }}"
                              data-foto_stnk="{{ $data->foto_stnk }}"  
                              data-lokasi_parkir="{{ $data->lokasi_parkir }}"  
                              data-toggle="modal" 
                              data-target="#viewModal"
                              title="Details">
                          <i class="fas fa-eye"></i>
                      </button>
                      
                      <button class="btn btn-warning btn-sm btn-edit-module" 
                              data-id="{{ $data->id_sewa }}"  
                              data-plat_no="{{ $data->plat_no }}" 
                              data-nik="{{ $data->nik }}" 
                              data-nama_karyawan="{{ $data->nama_karyawan }}" 
                              data-lokasi="{{ $data->lokasi }}" 
                              data-cc="{{ $data->cc }}" 
                              data-cc_nama="{{ $data->cc_nama }}" 
                              data-departemen="{{ $data->departemen }}" 
                              data-vendor="{{ $data->vendor }}" 
                              data-grade_title="{{ $data->grade_title }}" 
                              data-no_tlp="{{ $data->no_tlp }}" 
                              data-merk="{{ $data->merk }}" 
                              data-tipe="{{ $data->tipe }}" 
                              data-tahun="{{ $data->tahun }}" 
                              data-jenis="{{ $data->jenis }}" 
                              data-harga_sewa="{{ $data->harga_sewa }}" 
                              data-harga_sewa_ppn="{{ $data->harga_sewa_ppn }}" 
                              data-masa_sewa_start="{{ $data->masa_sewa_start }}" 
                              data-masa_sewa_end="{{ $data->masa_sewa_end }}" 
                              data-end_date_h_empatlima="{{ $data->end_date_h_empatlima }}" 
                              data-alert_masa_sewa="{{ $data->alert_masa_sewa }}" 
                              data-status="{{ $data->status }}" 
                              data-note_to_do="{{ $data->note_to_do }}" 
                              data-ket="{{ $data->ket }}" 
                              data-kondisi="{{ $data->kondisi }}"  
                              data-pic_vendor="{{ $data->pic_vendor }}" 
                              data-kontak_vendor="{{ $data->kontak_vendor }}"
                              data-foto_tanda_terima="{{ $data->foto_tanda_terima }}"
                              data-foto_stnk="{{ $data->foto_stnk }}"  
                              data-lokasi_parkir="{{ $data->lokasi_parkir }}"  
                              data-toggle="modal" 
                              data-target="#editModal"
                              title="Edit">
                          <i class="fas fa-pencil-alt"></i>
                      </button>
                      
                      <button class="btn btn-success btn-sm btn-sewa-module" 
                              data-nama_karyawan="{{ $data->nama_karyawan }}" 
                              data-departemen="{{ $data->departemen }}" 
                              data-masa_sewa_start="{{ $data->masa_sewa_start }}" 
                              data-masa_sewa_end="{{ $data->masa_sewa_end }}" 
                              data-id="{{ $data->id_sewa }}"
                              title="Perpanjang Sewa">
                          <i class="fas fa-book"></i> 
                      </button>
                      
                      <button class="btn btn-primary btn-sm btn-user-module" 
                              data-nama_karyawan="{{ $data->nama_karyawan }}" 
                              data-id="{{ $data->id_sewa }}"
                              title="Perpindahan User">
                          <i class="fas fa-user"></i>
                      </button>
                  </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">   
    <form id="editForm" action="{{ route('sewa.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" id="edit-id">
        <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel"><b>Edit Data Sewa</b></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Plat No</b></label>
              <input type="text" id="edit_plat_no" name="plat_no" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>NIK</b></label>
              <input type="text" id="edit_nik" name="nik" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Nama Karyawan</b></label>
              <input type="text" id="edit_nama_karyawan" name="nama_karyawan" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Lokasi</b></label>
              <input type="text" id="edit_lokasi" name="lokasi" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>CC</b></label>
              <input type="text" id="edit_cc" name="cc" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>CC Nama</b></label>
              <input type="text" id="edit_cc_nama" name="cc_nama" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Departemen</b></label>
              <input type="text" id="edit_departemen" name="departemen" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Vendor</b></label>
              <input type="text" id="edit_vendor" name="vendor" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Grade Title</b></label>
              <input type="text" id="edit_grade_title" name="grade_title" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>No Telepon</b></label>
              <input type="text" id="edit_no_tlp" name="no_tlp" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Merk</b></label>
              <input type="text" id="edit_merk" name="merk" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Tipe</b></label>
              <input type="text" id="edit_tipe" name="tipe" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Tahun</b></label>
              <input type="text" id="edit_tahun" name="tahun" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Jenis</b></label>
              <input type="text" id="edit_jenis" name="jenis" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Harga Sewa</b></label>
              <input type="text" id="edit_harga_sewa" name="harga_sewa" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Harga Sewa PPN</b></label>
              <input type="text" id="edit_harga_sewa_ppn" name="harga_sewa_ppn" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Masa Sewa Start</b></label>
              <input type="date" id="edit_masa_sewa_start" name="masa_sewa_start" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Masa Sewa End</b></label>
              <input type="date" id="edit_masa_sewa_end" name="masa_sewa_end" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>End Date H+45</b></label>
              <input type="date" id="edit_end_date_h_empatlima" name="end_date_h_empatlima" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Alert Masa Sewa</b></label>
              <input type="text" id="edit_alert_masa_sewa" name="alert_masa_sewa" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Lokasi Parkir</b></label>
              <input type="text" id="edit_lokasi_parkir" name="lokasi_parkir" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Kondisi Mobil</b></label>
              <input type="text" id="edit_kondisi" name="kondisi" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>PIC Vendor</b></label>
              <input type="text" id="edit_pic_vendor" name="pic_vendor" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Kontak Vendor</b></label>
              <input type="text" id="edit_kontak_vendor" name="kontak_vendor" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Foto Tanda Terima</b></label>
              <input type="file" id="edit_foto_tanda_terima" name="foto_tanda_terima" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Foto STNK</b></label>
              <input type="file" id="edit_foto_stnk" name="foto_stnk" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Status</b></label>
              <input type="text" id="edit_status" name="status" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Note To Do</b></label>
              <input type="text" id="edit_note_to_do" name="note_to_do" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Keterangan</b></label>
              <input type="text" id="edit_ket" name="ket" class="form-control">
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

<!-- MODAL CREATE -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="createForm" action="{{ route('sewa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel"><b>Create Data Sewa</b></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Plat No</b></label>
              <input type="text" name="plat_no" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label><b>NIK</b></label>
              <input type="text" name="nik" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Nama Karyawan</b></label>
              <input type="text" name="nama_karyawan" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Lokasi</b></label>
              <input type="text" name="lokasi" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>CC</b></label>
              <input type="text" id="edit_cc" name="cc" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>CC Nama</b></label>
              <input type="text" id="edit_cc_nama" name="cc_nama" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Departemen</b></label>
              <input type="text" id="edit_departemen" name="departemen" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Vendor</b></label>
              <input type="text" id="edit_vendor" name="vendor" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Grade Title</b></label>
              <input type="text" id="edit_grade_title" name="grade_title" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>No Telepon</b></label>
              <input type="text" id="edit_no_tlp" name="no_tlp" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Merk</b></label>
              <input type="text" id="edit_merk" name="merk" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Tipe</b></label>
              <input type="text" id="edit_tipe" name="tipe" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Tahun</b></label>
              <input type="text" id="edit_tahun" name="tahun" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Jenis</b></label>
              <input type="text" id="edit_jenis" name="jenis" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Harga Sewa</b></label>
              <input type="text" id="edit_harga_sewa" name="harga_sewa" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Harga Sewa PPN</b></label>
              <input type="text" id="edit_harga_sewa_ppn" name="harga_sewa_ppn" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Masa Sewa Start</b></label>
              <input type="date" id="edit_masa_sewa_start" name="masa_sewa_start" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Masa Sewa End</b></label>
              <input type="date" id="edit_masa_sewa_end" name="masa_sewa_end" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>End Date H+45</b></label>
              <input type="date" id="edit_end_date_h_empatlima" name="end_date_h_empatlima" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Alert Masa Sewa</b></label>
              <input type="text" id="edit_alert_masa_sewa" name="alert_masa_sewa" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
              <label><b>Kondisi Mobil</b></label>
              <input type="text" id="edit_kondisi" name="kondisi" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>PIC_Vendor</b></label>
              <input type="text" id="edit_pic_vendor" name="pic_vendor" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Kontak Vendor</b></label>
              <input type="text" id="edit_kontak_vendor" name="kontak_vendor" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Lokasi Parkir</b></label>
              <input type="text" id="edit_lokasi_parkir" name="lokasi_parkir" class="form-control">
            </div> 
          </div>
          <div class="row">
            <div class="col-md-3 mb-3">
                <label><b>Foto STNK</b></label>
                <input type="file" id="edit_foto_stnk" name="foto_stnk" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label><b>Foto Tanda Terima</b></label>
                <input type="file" id="edit_foto_tanda_terima" name="foto_tanda_terima" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Status</b></label>
              <input type="text" id="edit_status" name="status" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Note To Do</b></label>
              <input type="text" id="edit_note_to_do" name="note_to_do" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
              <label><b>Keterangan</b></label>
              <input type="text" id="edit_ket" name="ket" class="form-control">
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

<!-- MODAL PERPANJANG SEWA -->
<div class="modal fade" id="sewaModal" tabindex="-1" role="dialog" aria-labelledby="sewaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="sewaForm" action="{{ route('perpanjangsewa') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="sewa-id">
        <div class="modal-header">
          <h5 class="modal-title" id="sewaModalLabel"><b>Perpanjang Sewa</b></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="sewa_nama_karyawan"><b>Nama Karyawan</b></label>
              <input type="text" id="sewa_nama_karyawan" name="nama_karyawan" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="sewa_ownrisk"><b>Nilai Ownrisk (Harga)</b></label>
              <input type="text" id="sewa_ownrisk" name="ownrisk" class="form-control" oninput="formatRupiah(this)">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="sewa_masa_sewa_start"><b>Masa Sewa Start</b></label>
              <input type="date" id="sewa_masa_sewa_start" name="masa_sewa_start" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label for="sewa_masa_sewa_end"><b>Masa Sewa End</b></label>
              <input type="date" id="sewa_masa_sewa_end" name="masa_sewa_end" class="form-control">
            </div>
          </div>   
          <div class="mt-4">
            <h5 class="modal-title"><b>History Perpanjang Sewa</b></h5>
            <div class="table-responsive">
            <table id="history-sewa-table" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>Nilai Ownrisk (Harga)</th>
                                    <th>Sewa Start Date</th>
                                    <th>Sewa End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL PINDAH USER -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="userForm" action="{{ route('perpindahan.user') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="user-id">
        <div class="modal-header">
          <h5 class="modal-title" id="userModalLabel"><b>Perpindahan User</b></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="user_nama_karyawan"><b>Nama Karyawan Lama</b></label>
              <input type="text" id="user_nama_karyawan" name="nama_karyawan" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label for="user_nama_karyawan_baru"><b>Nama Karyawan Baru</b></label>
              <input type="text" id="user_nama_karyawan_baru" name="nama_karyawan_baru" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="user_tanggal_pindah_resign"><b>Tanggal Pindah/Resign</b></label>
              <input type="date" id="user_tanggal_pindah_resign" name="tanggal_pindah_resign" class="form-control" required>
            </div>
          </div>
          <div class="mt-4">
            <h5 class="modal-title"><b>History Perpindahan User</b></h5>
            <div class="table-responsive">
              <table id="history-user-table" class="table table-striped">
                <thead>
                  <tr>
                    <th>Nama Karyawan Lama</th>
                    <th>Nama Karyawan Baru</th>
                    <th>Tanggal Pindah/Resign</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- History data will be dynamically populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- MODAL IMPORT -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kendaraan.sewa.import') }}" method="POST" enctype="multipart/form-data">
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

<!-- MODAL DETAILS -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"><b>Detail Kendaraan Sewa</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
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
            <label class="fw-bold"><b>Departemen</b></label>
            <div id="departemen"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Grade Title</b></label>
            <div id="grade_title"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>No Telepon</b></label>
            <div id="no_tlp"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Lokasi</b></label>
            <div id="lokasi"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Status</b></label>
            <div id="status"></div>
        </div>
    </div>
    <!-- Second Column -->
    <div class="col-md-3">
        <div class="mb-3">
            <label class="fw-bold"><b>Merk</b></label>
            <div id="merk"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Tipe</b></label>
            <div id="tipe"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Jenis</b></label>
            <div id="jenis"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Tahun</b></label>
            <div id="tahun"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Kondisi Mobil</b></label>
            <div id="kondisi"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>CC</b></label>
            <div id="cc"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>CC Nama</b></label>
            <div id="cc_nama"></div>
        </div>
    </div>
    <!-- Third Column -->
    <div class="col-md-3">
        <div class="mb-3">
            <label class="fw-bold"><b>Vendor</b></label>
            <div id="vendor"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>PIC Vendor</b></label>
            <div id="pic_vendor"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Kontak Vendor</b></label>
            <div id="kontak_vendor"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Harga Sewa</b></label>
            <div id="harga_sewa"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Harga Sewa PPN</b></label>
            <div id="harga_sewa_ppn"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Masa Sewa Start</b></label>
            <div id="masa_sewa_start"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Masa Sewa End</b></label>
            <div id="masa_sewa_end"></div>
        </div>
    </div>
    <!-- Fourth Column -->
    <div class="col-md-3">    
        <div class="mb-3">
            <label class="fw-bold"><b>End Date H+45</b></label>
            <div id="end_date_h_empatlima"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Alert Masa Sewa</b></label>
            <div id="alert_masa_sewa"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Foto Tanda Terima</b></label>
            <div id="view_foto_tanda_terima">
                <a id="link_foto_tanda_terima" href="#" target="_blank">View Foto Tanda Terima</a>
            </div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Foto STNK</b></label>
            <div id="view_foto_stnk">
                <a id="link_foto_stnk" href="#" target="_blank">View Foto STNK</a>
            </div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Lokasi Parkir</b></label>
            <div id="lokasi_parkir"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Note To Do</b></label>
            <div id="note_to_do"></div>
        </div>
        <div class="mb-3">
            <label class="fw-bold"><b>Keterangan</b></label>
            <div id="ket"></div>
        </div>    
    </div>
</div>
@endsection

@section('script')
<script>
  var historySewaData = @json($historysewa);

  $('.btn-sewa-module').on('click', function() {
    var idSewa = $(this).data('id');
    var namaKaryawan = $(this).data('nama_karyawan');
    var ownrisk = $(this).data('ownrisk');
    var masaSewaStart = $(this).data('masa_sewa_start');
    var masaSewaEnd = $(this).data('masa_sewa_end');

    $('#sewa-id').val(idSewa);
    $('#sewa_nama_karyawan').val(namaKaryawan);
    $('#sewa_ownrisk').val(ownrisk != null && !isNaN(ownrisk) ? formatCurrency(ownrisk) : '-');
    $('#sewa_masa_sewa_start').val(masaSewaStart || '');
    $('#sewa_masa_sewa_end').val(masaSewaEnd || '');

    var table = $('#history-sewa-table').DataTable();
    table.clear();

    var filteredHistory = historySewaData.filter(function(history) {
      return history.id_sewa == idSewa;
    });

    $('#history-sewa-table tbody').empty();
    filteredHistory.forEach(function(history) {
      var ownriskValue = history.ownrisk ? history.ownrisk.toString().replace(/[^0-9]/g, '') : null;
      var formattedOwnrisk = ownriskValue ? 'Rp. ' + parseInt(ownriskValue).toLocaleString('id-ID') : '-';

      var formattedStart = history.masa_sewa_start 
          ? new Date(history.masa_sewa_start).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) 
          : '-';
      var formattedEnd = history.masa_sewa_end 
          ? new Date(history.masa_sewa_end).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }) 
          : '-';

      $('#history-sewa-table tbody').append(
          '<tr data-id="' + history.id_history_sewa + '">' + 
              '<td>' + history.nama_karyawan + '</td>' +
              '<td>' + formattedOwnrisk + '</td>' +
              '<td>' + formattedStart + '</td>' +
              '<td>' + formattedEnd + '</td>' +
              '<td>' +
                  '<form action="/deleteHistory/' + history.id_history_sewa + '" method="POST">' +
                      '@csrf' +
                      '@method("DELETE")' +
                      '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\')">' +
                          '<i class="bi bi-trash"></i>' +
                      '</button>' +
                  '</form>' +
              '</td>' + 
          '</tr>'
      );
  });

    $('#sewaModal').modal('show');
  });

  function formatCurrency(value) {
    value = value.toString().replace(/[^0-9]/g, '');
    return value ? 'Rp. ' + parseInt(value).toLocaleString('id-ID') : '';
  }

  $('#sewa_ownrisk').on('input', function() {
    var value = $(this).val();
    $(this).val(formatCurrency(value));
  });

  $(document).on('click', '.btn-delete-history', function(event) {
    event.preventDefault();

    var idHistorySewa = $(this).data('id-history-sewa');

    if (confirm("Are you sure you want to delete this history record?")) {
      $.ajax({
        url: '/deleteHistory/' + idHistorySewa,
        type: 'DELETE',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success) {
            $('tr[data-id="' + idHistorySewa + '"]').remove();
            alert("History record deleted successfully!");
          } else {
            alert(response.error);
          }
        },
        error: function(xhr) {
          alert("An error occurred while trying to delete the record: " + xhr.responseText);
        }
      });
    }
  });
</script>

<script>
var historyUser = @json($historyuser);

$('.btn-user-module').on('click', function() {
    var idSewa = $(this).data('id');
    var namaKaryawan = $(this).data('nama_karyawan');
    var namaKaryawanBaru = $(this).data('nama_karyawan_baru');
    var tanggalPindahResign = $(this).data('tanggal_pindah_resign');

    $('#user-id').val(idSewa);
    $('#user_nama_karyawan').val(namaKaryawan);
    $('#user_nama_karyawan_baru').val(namaKaryawanBaru);
    $('#user_tanggal_pindah_resign').val(tanggalPindahResign);

    var filteredHistory = historyUser.filter(function(history) {
        return history.id_sewa == idSewa; 
    });

    var table = $('#history-user-table').DataTable();
    table.clear();

    filteredHistory.forEach(function(history) {
        var formattedDate = history.tanggal_pindah_resign ? 
            new Date(history.tanggal_pindah_resign).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }) : 'Tanggal tidak tersedia';

        table.row.add([
            history.nama_karyawan,
            history.nama_karyawan_baru,
            formattedDate,
            '<form action="/delete-perpindahan-user/' + history.id_history_user + '" method="POST">' +
                '@csrf' +
                '@method("DELETE")' +
                '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\')">' +
                    '<i class="bi bi-trash"></i>' +
                '</button>' +
            '</form>'
        ]).draw(false); 
    });

    $('#userModal').modal('show');
});

$(document).on('click', '.btn-delete-history', function(event) {
    event.preventDefault();

    var idHistoryUser = $(this).data('id-history-user');  

    if (confirm("Are you sure you want to delete this history record?")) {
        $.ajax({
            url: '/delete-perpindahan-user/' + idHistoryUser, 
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('tr[data-id="' + idHistoryUser + '"]').remove(); 
                    alert("History record deleted successfully!");
                } else {
                    alert(response.error);  
                }
            },
            error: function(xhr) {
                alert("An error occurred while trying to delete the record: " + xhr.responseText);
            }
        });
    }
});
</script>


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

        function formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function formatRupiah(angka) {
            let angkaString = angka.toString().replace(/^Rp\.? /, ""); 
            return 'Rp. ' + angkaString.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
        }

        $('#dttable').on('click', '.btn-view-module', function() {
        const data = {
            plat_no: $(this).data('plat_no') || '-',
            nik: $(this).data('nik') || '-',
            nama_karyawan: $(this).data('nama_karyawan') || '-',
            lokasi: $(this).data('lokasi') || '-',
            cc: $(this).data('cc') || '-',
            cc_nama: $(this).data('cc_nama') || '-',
            departemen: $(this).data('departemen') || '-',
            vendor: $(this).data('vendor') || '-',
            grade_title: $(this).data('grade_title') || '-',
            no_tlp: $(this).data('no_tlp') || '-',
            merk: $(this).data('merk') || '-',
            tipe: $(this).data('tipe') || '-',
            tahun: $(this).data('tahun') || '-',
            jenis: $(this).data('jenis') || '-',
            harga_sewa: formatRupiah($(this).data('harga_sewa')) || '-',
            harga_sewa_ppn: formatRupiah($(this).data('harga_sewa_ppn')) || '-',
            masa_sewa_start: formatDate($(this).data('masa_sewa_start')),
            masa_sewa_end: formatDate($(this).data('masa_sewa_end')),
            end_date_h_empatlima: formatDate($(this).data('end_date_h_empatlima')),
            alert_masa_sewa: $(this).data('alert_masa_sewa') || '-',
            status: $(this).data('status') || '-',
            note_to_do: $(this).data('note_to_do') || '-',
            ket: $(this).data('ket') || '-',
            kondisi: $(this).data('kondisi') || '-',
            pic_vendor: $(this).data('pic_vendor') || '-',
            kontak_vendor: $(this).data('kontak_vendor') || '-',
            foto_tanda_terima: $(this).data('foto_tanda_terima') || '-',
            foto_stnk: $(this).data('foto_stnk') || '-',
            lokasi_parkir: $(this).data('lokasi_parkir') || '-',
        };

        // Populate modal fields with data
        $('#plat_no').text(data.plat_no);
        $('#nik').text(data.nik);
        $('#nama_karyawan').text(data.nama_karyawan);
        $('#lokasi').text(data.lokasi);
        $('#cc').text(data.cc);
        $('#cc_nama').text(data.cc_nama);
        $('#departemen').text(data.departemen);
        $('#vendor').text(data.vendor);
        $('#grade_title').text(data.grade_title);
        $('#no_tlp').text(data.no_tlp);
        $('#merk').text(data.merk);
        $('#tipe').text(data.tipe);
        $('#tahun').text(data.tahun);
        $('#jenis').text(data.jenis);
        $('#harga_sewa').text(data.harga_sewa);
        $('#harga_sewa_ppn').text(data.harga_sewa_ppn);
        $('#masa_sewa_start').text(data.masa_sewa_start);
        $('#masa_sewa_end').text(data.masa_sewa_end);
        $('#end_date_h_empatlima').text(data.end_date_h_empatlima);
        $('#alert_masa_sewa').text(data.alert_masa_sewa);
        $('#status').text(data.status);
        $('#note_to_do').text(data.note_to_do);
        $('#ket').text(data.ket);
        $('#kondisi').text(data.kondisi);
        $('#pic_vendor').text(data.pic_vendor);
        $('#kontak_vendor').text(data.kontak_vendor);
        $('#lokasi_parkir').text(data.lokasi_parkir);

        const basePath = '/storage/service/';

          if (data.foto_tanda_terima && data.foto_tanda_terima !== '-') {
              const filePathTandaTerima = basePath + encodeURIComponent(data.foto_tanda_terima);
              $('#link_foto_tanda_terima').attr('href', filePathTandaTerima).text('View Foto Tanda Terima').attr('target', '_blank');
          } else {
              $('#link_foto_tanda_terima').attr('href', '#').text('No file tersedia');
          }

          if (data.foto_stnk && data.foto_stnk !== '-') {
              const filePathSTNK = basePath + encodeURIComponent(data.foto_stnk);
              $('#link_foto_stnk').attr('href', filePathSTNK).text('View Foto STNK').attr('target', '_blank');
          } else {
              $('#link_foto_stnk').attr('href', '#').text('No file tersedia');
          }


        $('#viewModal').modal('show');
    });

        $('#dttable').on('click', '.btn-edit-module', function() {
            function formatDate(date) {
                if (!date) return '';
                const d = new Date(date);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            $('#edit-id').val($(this).data('id') || '');
            $('#edit_plat_no').val($(this).data('plat_no') || '');
            $('#edit_nik').val($(this).data('nik') || '');
            $('#edit_nama_karyawan').val($(this).data('nama_karyawan') || '');
            $('#edit_lokasi').val($(this).data('lokasi') || '');
            $('#edit_cc').val($(this).data('cc') || '');
            $('#edit_cc_nama').val($(this).data('cc_nama') || '');
            $('#edit_departemen').val($(this).data('departemen') || '');
            $('#edit_vendor').val($(this).data('vendor') || '');
            $('#edit_grade_title').val($(this).data('grade_title') || '');
            $('#edit_no_tlp').val($(this).data('no_tlp') || '');
            $('#edit_merk').val($(this).data('merk') || '');
            $('#edit_tipe').val($(this).data('tipe') || '');
            $('#edit_tahun').val($(this).data('tahun') || '');
            $('#edit_jenis').val($(this).data('jenis') || '');
            $('#edit_harga_sewa').val(formatRupiah($(this).data('harga_sewa')) || '');
            $('#edit_harga_sewa_ppn').val(formatRupiah($(this).data('harga_sewa_ppn')) || '');
            $('#edit_masa_sewa_start').val(formatDate($(this).data('masa_sewa_start')));
            $('#edit_masa_sewa_end').val(formatDate($(this).data('masa_sewa_end')));
            $('#edit_end_date_h_empatlima').val(formatDate($(this).data('end_date_h_empatlima')));
            $('#edit_alert_masa_sewa').val($(this).data('alert_masa_sewa') || '');
            $('#edit_status').val($(this).data('status') || '');
            $('#edit_note_to_do').val($(this).data('note_to_do') || '');
            $('#edit_ket').val($(this).data('ket') || '');
            $('#edit_kondisi').val($(this).data('kondisi') || '');
            $('#edit_pic_vendor').val($(this).data('pic_vendor') || '');
            $('#edit_kontak_vendor').val($(this).data('kontak_vendor') || '');
            $('#edit_foto_tanda_terima').val($(this).data('foto_tanda_terima') || '');
            $('#edit_foto_stnk').val($(this).data('foto_stnk') || '');
            $('#edit_lokasi_parkir').val($(this).data('lokasi_parkir') || '');

            $('#editModal').modal('show');
        });
       
        $('#viewModal, #editModal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $('#dttable').DataTable().columns.adjust().draw();
        });
    });
</script>
@endsection
