<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PP</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        h1,h2,h3,h4,h5,h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .brand-section {
           padding: 10px 40px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 20%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .title {
            float: center;
            text-align: center;
        }

        .body-section {
            padding: 16px;
            border: 2px solid #111;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        .table-bordered {
            box-shadow: 0px 0px 5px 0.5px;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #111;
        }

        .text-right {
            text-align: end;
        }

        .w-20 {
            width: 5%;
        }

        .float-right {
            float: right;
        }

        .titles {
            text-align: center;
        }

        .sign {
            text-align: center;
            height:100px;
            line-height:100px;
            vertical-align:top;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="body-section">
            <h1 class="heading">PT NIRAMAS UTAMA</h1>
            <div class="row">
                <div class="col-12">
                    <h4 class="heading titles">PERMINTAAN PEMBAYARAN</h4>
                </div>
            </div>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align: left; width:15%">BAGIAN</td>
                        <td style="text-align: left; width:2%">:</td>
                        <td style="text-align: left;">Human Capital Management</td>
                        <td style="text-align: left; width:15%">NAMA</td>
                        <td style="text-align: left; width:2%">:</td>
                        <td style="text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width:15%">TANGGAL</td>
                        <td style="text-align: left; width:2%">:</td>
                        <td style="text-align: left;">{{ $today }}</td>
                        <td style="text-align: left; width:15%">NO</td>
                        <td style="text-align: left; width:2%">:</td>
                        <td style="text-align: left;"></td>
                    </tr>
                </tbody>
            </table>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th class="w-20">NO</th>
                        <th>KETERANGAN</th>
                        <th>JUMLAH<br>Rp. / US $ / Yen / SGD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td style="text-align: left">Penyelesaian Petty Cash {{ $sdate }} - {{ $edate }}</td>
                        <td style="text-align: left;">Rp. {{ number_format($saldo, 0, ',', '.') }},-</td> 
                    </tr>
                    <tr>
                        <td>2</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align: left; width:15%"><b>Terbilang</b></td>
                        <td style="text-align: left; width:2%">:</td>
                        <td style="text-align: left;"><u>{{$saldo_terbilang}}</u></td>
                    </tr>
                    
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="text-align: left;">Diajukan Oleh :</td>
                        <td style="text-align: left;">Diketahui Oleh :</td>
                        <td style="text-align: left;">Disetujui Oleh :</td>
                        <td style="text-align: left;">Diterima Oleh :</td>
                    </tr>
                </tbody>
            </table>
            <table class="table-bordered">
                <tbody>
                    <tr class="sign">
                        <td style="text-align: center;">&nbsp;__________________</td>
                        <td style="text-align: center;">&nbsp;__________________</td>
                        <td style="text-align: center;">&nbsp;__________________</td>
                        <td style="text-align: center;">&nbsp;__________________</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
