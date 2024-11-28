@extends('layouts.main')

<style>
    table {
        width: 100%;
        border-collapse: collapse; 
    }
    th, td {
        padding: 10px;
        text-align: left;
        vertical-align: middle;
    th {
        background-color: #f2f2f2;
    }
    td {
        border-bottom: 1px solid #ddd;
    }
    .modal-custom {
        max-width: 600px; 
    }

    .modal-content {
        padding: 15px;
        border-radius: 8px;
    }

    .modal-body label {
        font-size: 14px;
        color: #333;
    }

    .modal-body div {
        font-size: 14px;
        color: #555;
    }

    .modal-body img {
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>

@section('content')
<div class="card">
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-4 d-flex justify-content-between align-items-center">
    Report Data Visitor
</div>

</h5>
</h5>
    </button></h5>
        <div class="d-flex align-items-end gap-4 mb-4">
        <!-- Filter Jenis -->
        <div class="mb-3" style="flex: 1;">
            <label for="jenisFilter" class="form-label">Jenis</label>
            <select id="jenisFilter" class="form-select">
                <option value="">-- Pilih Jenis --</option>
                <option value="All">All</option>
                <option value="VIP">VIP</option>
                <option value="VENDOR">VENDOR</option>
                <option value="EKSPEDISI">EKSPEDISI</option>
                <option value="PELAMAR">PELAMAR</option>
                <option value="GENERAL">GENERAL</option>
            </select>
        </div>

        <div class="mb-3" style="flex: 1;">
            <label for="statusFilter" class="form-label">Status</label>
            <select id="statusFilter" class="form-select">
                <option value="">-- Pilih Status --</option>
                <option value="All">All</option>
                <option value="In">In</option>
                <option value="Out">Out</option>
                <option value="Complete">Complete</option>
            </select>
        </div>

        <!-- Export Button -->
        <div class="mb-3">
            <a class="btn btn-primary export-button" href="{{ route('export.visitor') }}">Export</a>
        </div>
    </div>

    <table class="display table-head-bg-primary" id="dttable5">
    <thead>
        <tr>
            <th class="content" style="display: none">ID</th>
            <th class="content">Nama Tamu</th>
            <th class="content">Bertemu dengan</th>
            <th class="content">Jenis</th>
            <th class="content">Asal</th>
            <th class="content">Status</th>
            <th class="content">Masuk</th>
            <th class="content">Keluar</th>
            <th class="content">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($visitor as $data)
        <tr>
            <td style="display: none"></td>
            <td class="content">{{ $data->nama_tamu !== '#N/A' ? ($data->nama_tamu ?? '-') : '-' }}</td>
            <td class="content">{{ $data->bertemu_dengan !== '#N/A' ? ($data->bertemu_dengan ?? '-') : '-' }}</td>
            <td class="content">{{ $data->jenis !== '#N/A' ? ($data->jenis ?? '-') : '-' }}</td>
            <td class="content">{{ $data->asal !== '#N/A' ? ($data->asal ?? '-') : '-' }}</td>
            <td class="content">{{ $data->status !== '#N/A' ? ($data->status ?? '-') : '-' }}</td>
            <td class="content">
                @if ($data->masuk !== '#N/A' && $data->masuk)
                    {{ \Illuminate\Support\Str::of($data->masuk)->before(' ') }} <br>
                    {{ \Illuminate\Support\Str::of($data->masuk)->after(' ') }}
                @else
                    -
                @endif
            </td>
            <td class="content">
                @if ($data->keluar !== '#N/A' && $data->keluar)
                    {{ \Illuminate\Support\Str::of($data->keluar)->before(' ') }} <br>
                    {{ \Illuminate\Support\Str::of($data->keluar)->after(' ') }}
                @else
                    -
                @endif
            </td>
            <td class="content">
            <button class="btn btn-info btn-sm btn-view-module"
                        data-jenis="{{ $data->jenis }}" 
                        data-nama_tamu="{{ $data->nama_tamu }}" 
                        data-perusahaan="{{ $data->perusahaan }}" 
                        data-alamat="{{ $data->alamat }}" 
                        data-jumlah="{{ $data->jumlah }}" 
                        data-no_pol="{{ $data->no_pol }}" 
                        data-bertemu_dengan="{{ $data->bertemu_dengan }}" 
                        data-tujuan="{{ $data->tujuan }}" 
                        data-securityName="{{ $data->securityName }}" 
                        data-keterangan="{{ $data->keterangan }}" 
                        data-masuk="{{ $data->masuk }}" 
                        data-keluar="{{ $data->keluar }}" 
                        data-status="{{ $data->status }}" 
                        data-alasan="{{ $data->alasan }}" 
                        data-flag="{{ $data->flag }}" 
                        data-image="{{ $data->image }}"   
                        data-toggle="modal" 
                        data-target="#viewModal"
                        title="Details">
                        <i class="fas fa-eye"></i>
                    </button>
            </td>
        </tr>
    @endforeach
</tbody>
</table>
</div>
</div>

<!-- MODAL DETAILS VISITOR -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel"><b>Detail Visitor</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Kolom Pertama -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold">Jenis Tamu</label>
                            <div id="jenis">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Nama Tamu</label>
                            <div id="nama_tamu">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">No. Pol</label>
                            <div id="no_pol">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Status</label>
                            <div id="status">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Tujuan</label>
                            <div id="tujuan">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Foto Pengunjung</label>
                            <div>
                            <a id="lihatFoto" href="javascript:void(0)" class="btn btn-primary" onclick="lihatFoto();">
                                Lihat Foto
                            </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kedua -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold">Tanggal Masuk</label>
                            <div id="tanggal_masuk">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Jam Masuk</label>
                            <div id="jam_masuk">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Tanggal Keluar</label>
                            <div id="tanggal_keluar">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Jam Keluar</label>
                            <div id="jam_keluar">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Bertemu dengan</label>
                            <div id="bertemu_dengan">-</div>
                        </div>
                    </div>

                    <!-- Kolom Ketiga -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="fw-bold">Berasal dari</label>
                            <div id="perusahaan">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Security In</label>
                            <div id="securityName">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Jumlah Tamu</label>
                            <div id="jumlah">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Alamat</label>
                            <div id="alamat">-</div>
                        </div>
                        <div class="mb-12">
                            <label class="fw-bold">Keterangan</label>
                            <div id="keterangan">-</div>
                        </div>
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
    $(document).ready(function () {

        var successMessage = "{{ session('success') }}";
        var errorMessage = "{{ session('error') }}";

        if (successMessage) {
            toastr.success(successMessage);
        }
        if (errorMessage) {
            toastr.error(errorMessage);
        }

        var table = $('#dttable5').DataTable({
            paging: true,
            searching: true,
            responsive: true,
            columnDefs: [
                { targets: [0], visible: false }, 
                { orderable: false, targets: -1 } 
            ]
        });


        $('#jenisFilter, #statusFilter').on('change', function () {
            var jenisValue = $('#jenisFilter').val();
            var statusValue = $('#statusFilter').val();

            table.column(3).search(jenisValue === "All" ? "" : jenisValue)
                .column(5).search(statusValue === "All" ? "" : statusValue)
                .draw();
        });

        $('.btn-view-module').on('click', function () {
            const data = {
                jenis: $(this).data('jenis') || '-',
                nama_tamu: $(this).data('nama_tamu') || '-',
                perusahaan: $(this).data('perusahaan') || '-',
                alamat: $(this).data('alamat') || '-',
                jumlah: $(this).data('jumlah') || '-',
                no_pol: $(this).data('no_pol') || '-',
                bertemu_dengan: $(this).data('bertemu_dengan') || '-',
                tujuan: $(this).data('tujuan') || '-',
                securityName: $(this).data('securityname') || '-',
                keterangan: $(this).data('keterangan') || '-',
                masuk: $(this).data('masuk') || '-',
                keluar: $(this).data('keluar') || '-',
                status: $(this).data('status') || '-',
                alasan: $(this).data('alasan') || '-',
                image: $(this).data('image') || '#',
            };

            $('#jenis').text(data.jenis);
            $('#nama_tamu').text(data.nama_tamu);
            $('#perusahaan').text(data.perusahaan);
            $('#alamat').text(data.alamat);
            $('#jumlah').text(data.jumlah);
            $('#no_pol').text(data.no_pol);
            $('#bertemu_dengan').text(data.bertemu_dengan);
            $('#tujuan').text(data.tujuan);
            $('#securityName').text(data.securityName);
            $('#keterangan').text(data.keterangan);
            $('#tanggal_masuk').text(data.masuk.split(' ')[0] || '-');
            $('#jam_masuk').text(data.masuk.split(' ')[1] || '-');
            $('#tanggal_keluar').text(data.keluar.split(' ')[0] || '-');
            $('#jam_keluar').text(data.keluar.split(' ')[1] || '-');
            $('#status').text(data.status);
            $('#alasan').text(data.alasan);
            $('#lihatFoto').attr('href', data.image);

            $('#viewModal').modal('show');
        });

        $('#viewModal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        $(".tanggal").flatpickr({
            allowInput: true,
            dateFormat: "d-m-Y",
        });

        $('#export').on('click', function () {
            $('#exportModal').modal('show');
        });
        
        const exportButton = document.querySelector('.export-button');
        const jenisFilter = document.getElementById('jenisFilter');
        const statusFilter = document.getElementById('statusFilter');

        $('#jenisFilter, #statusFilter').on('change', function () {
        
        updateExportUrl();
        });

        function updateExportUrl() {
            const baseUrl = "{{ route('export.visitor') }}";
            const jenis = $('#jenisFilter').val() || 'All';
            const status = $('#statusFilter').val() || 'All';

            const exportUrl = `${baseUrl}?jenis=${jenis}&status=${status}`;
            $('.export-button').attr('href', exportUrl);
        }

        jenisFilter.addEventListener('change', updateExportUrl);
        statusFilter.addEventListener('change', updateExportUrl);

    });
    </script>
@endsection




