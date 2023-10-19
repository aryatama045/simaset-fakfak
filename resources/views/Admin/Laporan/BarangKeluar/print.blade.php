<!DOCTYPE html>
<html lang="en">

<?php

use Carbon\Carbon;
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{$web->web_deskripsi}}">
    <meta name="author" content="{{$web->web_nama}}">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- FAVICON -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    @else
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    @endif

    <title>{{$title}}</title>

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        #table1 {
            border-collapse: collapse;
            width: 100%;
            margin-top: 32px;
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #table1 th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: black;
            font-size: 12px;
        }

        #table1 td {
            font-size: 11px;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 600;
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
        }
    </style>

</head>

<body onload="window.print()">

    <center>
        @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @else
        <img src="{{asset('storage/web/' . $web->web_logo)}}" width="80px" alt="">
        @endif
    </center>

    <center>
        <h1 class="font-medium">Laporan Barang Keluar</h1>
        @if($tglawal == '')
        <h4 class="font-medium">Semua Tanggal</h4>
        @else
        <h4 class="font-medium">{{Carbon::parse($tglawal)->translatedFormat('d F Y')}} - {{Carbon::parse($tglakhir)->translatedFormat('d F Y')}}</h4>
        @endif
    </center>


    <table border="1" id="table1">
        <thead>
            <tr>
                <th rowspan="3" width="1%" > NO </th>
                <th rowspan="3" width="7%" > TANGGAL </th>
                <th rowspan="3" width="15%" > NAMA TOKO </th>
                <th colspan="2"> DOKUMEN FAKTUR </th>
                <th rowspan="3" width="30%"> NAMA BARANG</th>
                <th rowspan="3" width="3%"> BANYAK NYA </th>
                <th rowspan="3" width="3%"> HARGA SATUAN </th>
                <th rowspan="3" width="3%"> JUMLAH HARGA </th>
                <th colspan="2"> BUKTI PENERIMAAN </th>
                <th rowspan="3"> KET </th>
            </tr>

            <tr>
                <th rowspan="2" width="7%">NOMOR</th>
                <th rowspan="2" width="7%">TANGGAL</th>

                <th colspan="2"> B.A PENERIMAAN </th>

            </tr>

            <tr>
                <th width="7%">NOMOR</th>
                <th width="7%">TANGGAL</th>
            </tr>

            <tr id="thbottom">
                @for($x=1; $x <= 12; $x++)
                <th>{{$x}}</th>
                @endfor
            </tr>
        </thead>


        <tbody>
            @php $no=1; @endphp
            @foreach($data as $d)
            <tr>
                <td align="center">{{$no++}}</td>
                <td>{{Carbon::parse($d->bk_tanggal)->translatedFormat('d F Y')}}</td>
                <td>{{$d->bk_kode}}</td>
                <td>{{$d->barang_kode}}</td>
                <td>{{$d->barang_nama}}</td>
                <td align="center">{{$d->bk_jumlah}}</td>
                <td>{{$d->bk_tujuan}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
