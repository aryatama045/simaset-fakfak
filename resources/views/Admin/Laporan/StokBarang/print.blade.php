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

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:400,300|Raleway:300,400,900,700italic,700,300,600">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"  />

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

        #camat{
            text-align:center;
        }

        #nama-camat{
            margin-top:60px;
            text-align:center;
        }
    </style>

</head>

<body onload="window.print()">

    <!-- <center>
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
    </center> -->

    <div>
        <div class="container">
        <header>
            <div id="kop-surat" class="row">
                <div class="col-md-2 col-sm-2 col-xs-2">
                    <!-- <img id="logo" src="https://getasanbersinar.files.wordpress.com/2016/02/logo-kabupaten-semarang-jawa-tengah.png" width="140" height="160" /> -->

                    @if($web->web_logo == '' || $web->web_logo == 'default.png')
                    <img id="logo" src="{{url('/assets/default/web/default.png')}}" width="85" height="90" alt="">
                    @else
                    <img id="logo" src="{{url('/assets/default/web/default.png')}}" width="85" height="90" alt="">
                    @endif
                </div>
                <div class="col-md-10 col-sm-10 col-xs-10">
                    <h3 class="kablogo"> <strong>{{ $web->header_1 }}</strong></h3>
                    <h1 class="keclogo"><strong> {{ $web->header_2 }} </strong></h1>
                    <h6 class="alamatlogo"> {{ $web->alamat }} </h6>
                    <!-- <h5 class="kodeposlogo"><strong>BERGAS 50552</strong></h5> -->
                </div>
            </div>
        </header>

        <hr class="garis1"/>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1 class="keclogo"><strong> Laporan Stok Barang
                    <?php if($tglawal != ''){ ?>
                    {{$tglawal}} - {{$tglakhir}} <?php } ?></strong></h1>
            </div>
        </div>

        <table border="1" id="table1">
            <thead>
                <tr>
                    <th rowspan="2"width="1%" > NO </th>
                    <th rowspan="2"width="5%" > TANGGAL </th>
                    <th rowspan="2"width="5%" > NO./TGL. SURAT DASAR PENERIMAAN / PENGELUARAN </th>
                    <th rowspan="2"> URAIAN </th>
                    <th colspan="3"> BARANG-BARANG </th>
                    <th rowspan="2">HARGA SATUAN</th>
                    <th colspan="3"> JUMLAH HARGA BARANG YANG DITERIMA / DIKELUARKAN </th>
                    <th rowspan="2">KET</th>
                </tr>

                <tr>
                    <th>MASUK</th>
                    <th>KELUAR</th>
                    <th>SISA</th>
                    <th>BERTAMBAH</th>
                    <th>BERKURANG</th>
                    <th>SISA</th>
                </tr>

                <tr id="thbottom">
                    @for($x=1; $x <= 12; $x++)
                    <th>{{$x}}</th>
                    @endfor
                </tr>

            </thead>

            <tbody>

                @php $no=1; @endphp
                @foreach($data as $k => $v)


                    <tr>
                        <td align="center">{{$no++}}</td>
                        <td></td>
                        <td></td>
                        <td>{{ $k }}</td> <!-- Jenis Barang -->
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    {{dd($data)}}

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
                        <td></td>
                        <td>{{$d->bm_tanggal}}</td>
                        <td>{{$d->bm_tanggal}}</td>
                        <td align="center">{{$d->barang_stok}}</td>
                        <td align="center">{{$jmlmasuk}}</td>
                        <td align="center">{{$jmlkeluar}}</td>
                        <td align="center">{{$totalStok}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>


                @endforeach
            </tbody>

        </table>

        <footer>
            <div id="ttd" class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <p id="camat"><strong>CAMAT BERGAS</strong></p>
                    <div id="nama-camat"><strong><u>TRI MARTONO, SH, MM</u></strong><br />
                        Pembina Tk. I<br />
                        NIP. 196703221995031001
                    </div>
                </div>
            </div>
        </footer>

        </div>


    </div>

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"  ></script>

</html>
