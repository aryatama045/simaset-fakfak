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
        /* Default */
        * {
            font-family: Times, Helvetica, sans-serif;
        }

        @media print{
            @page {
                size: landscape;
            }
        }

        #table1 {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        #table1 th {
            padding-top: 4px;
            padding-bottom: 4px;
            color: black;
            font-size: 12px;
        }

        #thbottom th {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            color: black;
            font-size: 10px !important;
        }

        #table1 td {
            font-size: 12px;
        }

        /* .font-medium {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 600;
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
        } */
        /* --- END Default --- */


        h1,h3,h5,h6{
            text-align:center;
            /* padding-right:200px; */
            margin:5px;
        }


        .row{
            /* margin-top: 50px; */
            width:100%;
        }

        #kop-surat{
            margin-top: 50px;
        }

        .keclogo{
            font-size:1.8rem;
        }

        .kablogo{
            font-size:1.8rem;
        }

        .alamatlogo{
            font-size:1em;
        }

        .kodeposlogo{
            font-size:1em;
        }

        #tls{
            text-align:right;
        }

        .alamat-tujuan{
            margin-left:50%;
        }

        .garis1{
            border-top:3px solid black;
            height: 2px;
            border-bottom:1px solid black;
            margin-top: 6px;
        }

        #logo{
            margin: auto;
            margin-left: 35%;
            margin-right: auto;
        }

        #tempat-tgl{
            margin-left:120px;
        }

        #ttd{
            margin-top:25px;
        }

        #tanggal-pengurus{
            text-align:center;
        }

        #pengurus{
            text-align:center;
        }

        #nama-pengurus{
            margin-top:60px;
            text-align:center;
        }
    </style>


</head>

<body onload="window.print()">

    <center>
        @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @else
        <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @endif
    </center>

    <center>
        <h1 class="font-medium">Laporan Barang Masuk</h1>
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
                <th rowspan="3" width="5%" > TANGGAL </th>
                <th rowspan="3" width="5%" > NAMA TOKO </th>
                <th rowspan="3" width="15%"> URAIAN </th>
                <th colspan="2"> DOKUMEN FAKTUR </th>
                <th rowspan="3"> NAMA BARANG</th>
                <th colspan="3"> BANYAK NYA </th>
                <th rowspan="3"> HARGA SATUAN </th>
                <th rowspan="3"> JUMLAH HARGA </th>
                <th colspan="2"> BUKTI PENERIMAAN </th>
                <th rowspan="3"> KET </th>
            </tr>

            <tr>
                <th rowspan="2">NOMOR</th>
                <th rowspan="2">TANGGAL</th>

                <th colspan="2"> B.A PENERIMAAN </th>

                <th>SISA</th>
                <th>BERTAMBAH</th>
                <th>BERKURANG</th>
                <th>SISA</th>
            </tr>

            <tr>
                <th>NOMOR</th>
                <th>TANGGAL</th>
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
                <td>{{Carbon::parse($d->bm_tanggal)->translatedFormat('d F Y')}}</td>
                <td>{{$d->bm_kode}}</td>
                <td>{{$d->barang_kode}}</td>
                <td>{{$d->supplier_nama}}</td>
                <td>{{$d->barang_nama}}</td>
                <td align="center">{{$d->bm_jumlah}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
