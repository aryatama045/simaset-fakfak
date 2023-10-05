<!DOCTYPE html>
<html lang="en">

<?php

use App\Models\Admin\BarangModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\BarangmasukModel;
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
        /* --- END Default --- */


        h1,h3,h5,h6{
            text-align:center;
            padding-right:200px;
        }
        .row{
            margin-top: 20px;
        }

        .keclogo{
            font-size:24px;
            font-size:3vw;
        }

        .kablogo{
            font-size:2vw;
        }

        .alamatlogo{
            font-size:1.5vw;
        }

        .kodeposlogo{
            font-size:1.7vw;
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
        }

        #logo{
            margin: auto;
            margin-left: 50%;
            margin-right: auto;
        }

        #tempat-tgl{
            margin-left:120px;
        }

        #camat{
            text-align:center;
        }

        #nama-camat{
            margin-top:100px;
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
        <h1 class="font-medium">Laporan Stok Barang</h1>
        @if($tglawal == '')
        <h4 class="font-medium">Semua Tanggal</h4>
        @else
        <h4 class="font-medium">{{Carbon::parse($tglawal)->translatedFormat('d F Y')}} - {{Carbon::parse($tglakhir)->translatedFormat('d F Y')}}</h4>
        @endif
    </center>

    <header>
        <div class="row">
            <div id="img" class="col-md-3">
                <!-- <img id="logo" src="https://getasanbersinar.files.wordpress.com/2016/02/logo-kabupaten-semarang-jawa-tengah.png" width="140" height="160" /> -->

                @if($web->web_logo == '' || $web->web_logo == 'default.png')
                <img id="logo" src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
                @else
                <img id="logo" src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
                @endif
            </div>
            <div id="text-header" class="col-md-9">
                <h3 class="kablogo">PEMERINTAH KABUPATEN SEMARANG</h3>
                <h1 class="keclogo"><strong>KECAMATAN BERGAS</strong></h1>
                <h6 class="alamatlogo">Jl. Soekarno-Hatta, No. 68, Telepon/Faximile (0298) 523024</h6>
                <h5 class="kodeposlogo"><strong>BERGAS 50552</strong></h5>
            </div>
        </div>
    </header>

    <div class="container">
        <hr class="garis1"/>


        <table border="1" id="table1">
            <thead>

                <tr>
                        <th align="center" width="1%" rowspan="2"> NO </th>
                        <th colspan="2"> BARANG</th>
                        <th colspan="3"> TRANSAKSI</th>
                        <th rowspan="2">TOTAL</th>
                </tr>

                <tr>
                    <th>KODE BARANG</th>
                    <th>BARANG</th>
                    <th>STOK AWAL</th>
                    <th>JML MASUK</th>
                    <th>JML KELUAR</th>

                </tr>


            </thead>
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php $datas = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id')
                    ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id')
                    ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id')
                    ->leftJoin('tbl_barangmasuk', 'tbl_barangmasuk.barang_kode', '=', 'tbl_barang.barang_kode')
                    ->whereBetween('bm_tanggal', [$tglawal, $tglakhir])
                    ->orderBy('barang_id', 'DESC')->get();
                ?>


                @php $no=1; @endphp
                @foreach($datas as $d)
                    <?php
                        if($tglawal == ''){
                            $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)->sum('tbl_barangmasuk.bm_jumlah');
                        }else{
                            $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                            ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                            ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                            ->whereBetween('bm_tanggal', [$tglawal, $tglakhir])
                            ->sum('tbl_barangmasuk.bm_jumlah');
                        }

                        if ($tglawal != '') {
                            $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->whereBetween('bk_tanggal', [$tglawal, $tglakhir])->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                        } else {
                            $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)->sum('tbl_barangkeluar.bk_jumlah');
                        }

                        $totalStok = $d->barang_stok + ($jmlmasuk-$jmlkeluar);
                    ?>


                    <tr>
                        <td align="center">{{$no++}}</td>
                        <td>{{$d->barang_kode}}</td>
                        <td>{{$d->barang_nama}}</td>
                        <td align="center">{{$d->barang_stok}}</td>
                        <td align="center">{{$jmlmasuk}}</td>
                        <td align="center">{{$jmlkeluar}}</td>
                        <td align="center">{{$totalStok}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>

</html>
