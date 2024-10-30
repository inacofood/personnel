@extends('layouts.main')

@section('content')
<section class="section" id="home">
<div class="d-flex justify-content-end">
<form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-end">
    @csrf
    <div class="form-group mx-2">
    <select name="bulan" id="bulan" class="form-control">
        <option value="">-- Semua Bulan --</option>
        @foreach(range(1, 12) as $month)
            <option value="{{ $month }}" {{ session('bulan', now()->month) == $month ? 'selected' : '' }}>
                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mx-2">
    <select name="tahun" id="tahun" class="form-control">
        <option value="">-- Semua Tahun --</option>
        @foreach(range(date('Y'), 2020) as $year)
            <option value="{{ $year }}" {{ session('tahun', date('Y')) == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
</div>


    <div class="form-group mx-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

</div>

    <div class="container2 text-center" style="padding-top:20px">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row">
                    <!-- Total Leave -->
                    <div class="col-md-3">
                    <div class="total-box bg-primary text-white" style="border-radius: 10px; height: 100px;">
                        <h5 style="color:white; padding-top:10px"><b>Total Leave</b></h5>
                        <form action="{{ route('detail.count.dashboard') }}" method="GET">
                            @csrf
                            <input type="hidden" name="status" value="LEAVE">
                            <input type="hidden" name="bulan" value="{{ request('bulan') ?? now()->month }}">
                            <input type="hidden" name="tahun" value="{{ request('tahun') ?? now()->year }}">
                            <button type="submit" style="font-size:28px; font-weight:bold; padding-bottom:10px; border:none; background:none; cursor:pointer; color:white">
                                {{
                                    ($totals->total_sakit ?? 0) +
                                    ($totals->total_sakit_tanpa_sd ?? 0) +
                                    ($totals->total_cuti_melahirkan ?? 0) +
                                    ($totals->total_cuti_tahunan ?? 0) +
                                    ($totals->total_cuti ?? 0) +
                                    ($totals->total_izin ?? 0) +
                                    ($totals->total_anak_btis ?? 0) +
                                    ($totals->total_istri_melahirkan ?? 0) +
                                    ($totals->total_menikah ?? 0) +
                                    ($totals->total_ot_mtua_klg_mgl ?? 0) +
                                    ($totals->total_wfh ?? 0) +
                                    ($totals->total_paruh_waktu ?? 0)
                                }}
                            </button>
                        </form>
                    </div>
                </div>

                 <!-- Total Dinas Luar -->
                 <div class="col-md-3">
                    <div class="total-box bg-secondary text-white" style="border-radius: 10px; height: 100px;">
                        <h5 style="color:white; padding-top:10px"><b>Total Dinas Luar</b></h5>
                        <form action="{{ route('detail.dinasluar.dashboard') }}" method="GET">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ request('bulan') ?? now()->month }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') ?? now()->year }}">
                    <button type="submit" style="font-size:28px; font-weight:bold; padding-bottom:10px; border:none; background:none; cursor:pointer; color:white">
                        {{ $totals->total_dinas_luar ?? 'N/A' }}
                    </button>
                </form>
                    </div>
                </div>

                <!-- Total Telat -->
                <div class="col-md-3">
                    <div class="total-box bg-danger text-white" style="border-radius: 10px; height: 100px;">
                        <h5 style="color:white; padding-top:10px"><b>Total Late</b></h5>
                        <form action="{{ route('detail.late.dashboard') }}" method="GET">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ request('bulan') ?? now()->month }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') ?? now()->year }}">
                    <button type="submit" style="font-size:28px; font-weight:bold; padding-bottom:10px; border:none; background:none; cursor:pointer; color:white">
                        {{ $telatAwalData[0]['y'] ?? 'N/A' }}
                    </button>
                </form>
                    </div>
                </div>

                <!-- Total Pulang Awal -->
                <div class="col-md-3">
                    <div class="total-box bg-warning text-white" style="border-radius: 10px; height: 100px;">
                        <h5 style="color:white; padding-top:10px"><b>Total Pulang Awal</b></h5>
                        <form action="{{ route('detail.awal.dashboard') }}" method="GET">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ request('bulan') ?? now()->month }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') ?? now()->year }}">
                    <button type="submit" style="font-size:28px; font-weight:bold; padding-bottom:10px; border:none; background:none; cursor:pointer; color:white">
                        {{ $telatAwalData[1]['y'] ?? 'N/A' }}
                    </button>
                </form>
                </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Donut chart and top latecomers -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div id="donut-container" style="width:100%; height:400px; padding-top: 30px"></div>
            </div>

            <!-- Add a new column for the second chart -->
            <div class="col-md-6">
                <div id="donut-container-2" style="width:100%; height:400px;  padding-top: 30px"></div>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Kolom untuk leave terbanyak -->
            <div class="col-md-6">
                <table class="table table-bordered table-hover">
                    <thead class="bg-success text-white">
                        <tr>
                            <th colspan="3" class="text-center">5 Nama Leave Terbanyak Bulan Ini</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori Leave</th>
                            <th>Jumlah Leave</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapLeave as $leave)
                            <tr>
                                <td>{{ $leave['nama'] }}</td>
                                <td>{{ $leave['kategori_leave'] }}</td>
                                <td>{{ $leave['total_leave'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Kolom untuk telat terbanyak -->
            <div class="col-md-6">
                <table class="table table-bordered table-hover">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th colspan="2" class="text-center">5 Nama Telat Terbanyak Bulan Ini</th>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah Telat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapKehadiran as $kehadiran)
                            <tr>
                                <td>{{ $kehadiran['nama'] }}</td>
                                <td>{{ $kehadiran['total_telat'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Leave Category Table -->
        <div class="row">
            <div class="col-md-12 mt-4">
                <table class="table table-bordered table-hover">
                    <thead class="bg-info text-white">
                        <tr>
                            <th colspan="14" class="text-center">Kategori Leave</th>
                        </tr>
                        <tr>
                            <th>Sakit</th>
                            <th>Sakit Tanpa SD</th>
                            <th>Cuti Melahirkan</th>
                            <th>Cuti Tahunan</th>
                            <th>Cuti</th>
                            <th>Izin</th>
                            <th>Anak BTIS/Sunat</th>
                            <th>Istri Melahirkan</th>
                            <th>Menikah</th>
                            <th>OT/MTUA/KLG MGL</th>
                            <th>WFH</th>
                            <th>Paruh Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $totals->total_sakit ?? 0 }}</td>
                            <td>{{ $totals->total_sakit_tanpa_sd ?? 0 }}</td>
                            <td>{{ $totals->total_cuti_melahirkan ?? 0 }}</td>
                            <td>{{ $totals->total_cuti_tahunan ?? 0 }}</td>
                            <td>{{ $totals->total_cuti ?? 0 }}</td>
                            <td>{{ $totals->total_izin ?? 0 }}</td>
                            <td>{{ $totals->total_anak_btis ?? 0 }}</td>
                            <td>{{ $totals->total_istri_melahirkan ?? 0 }}</td>
                            <td>{{ $totals->total_menikah ?? 0 }}</td>
                            <td>{{ $totals->total_ot_mtua_klg_mgl ?? 0 }}</td>
                            <td>{{ $totals->total_wfh ?? 0 }}</td>
                            <td>{{ $totals->total_paruh_waktu ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>

<script>
    $(document).ready(function() {
        var data = {!! json_encode($data ?? []) !!};

        Highcharts.chart('donut-container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Leave Performance'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Kehadiran',
                colorByPoint: true,
                data: data
            }]

        });

        Highcharts.chart('donut-container-2', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Leave, Late, and Early Departures'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Total',
                colorByPoint: true,
                data: [
                    { name: 'Total Leave', y: {{ ($totals->total_sakit ?? 0) + ($totals->total_sakit_tanpa_sd ?? 0) + ($totals->total_cuti_melahirkan ?? 0) + ($totals->total_cuti_tahunan ?? 0) + ($totals->total_cuti ?? 0) + ($totals->total_izin ?? 0) + ($totals->total_anak_btis ?? 0) + ($totals->total_istri_melahirkan ?? 0) + ($totals->total_menikah ?? 0) + ($totals->total_ot_mtua_klg_mgl ?? 0) + ($totals->total_wfh ?? 0) + ($totals->total_paruh_waktu ?? 0) }}, sliced: true },
                    { name: 'Total Late', y: {{ $telatAwalData[0]['y'] ?? 0 }} },
                    { name: 'Total Pulang Awal', y: {{ $telatAwalData[1]['y'] ?? 0 }} }
                ]
            }]
        });
    });
</script>

@endsection
