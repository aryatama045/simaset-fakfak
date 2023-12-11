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

    <link rel="stylesheet" href="{{url('/assets/css/pdf_bs1.css')}}" />

    <style>
        /* Default */
        * {
            font-family: "Times New Roman", Times, serif, sans-serif !important;
            /* font-family: 'Times', sans-serif !important; */
            font-size: 1.3rem;
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

        .form-group{
            margin-bottom: 0px;
        }

        #kop-surat{
            margin-top: 30px;
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
                    <h3 class="font-weight-bold"> {{ $web->header_1 }} </h3>
                    <h1 class="keclogo font-weight-bold"> {{ $web->header_2 }}  </h1>
                    <h6 class="alamatlogo"> {{ $web->alamat }} </h6>
                    <!-- <h5 class="kodeposlogo"><strong>BERGAS 50552</strong></h5> -->
                </div>
            </div>
        </header>

        <hr class="garis1"/>

        <div class="row" style="margin-bottom:35px;">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1 class="keclogo"><strong> Laporan Stok Barang
                    <?php if($tglawal != ''){ ?>
                        {{Carbon::parse($tglawal)->translatedFormat('d F Y')}} - {{Carbon::parse($tglakhir)->translatedFormat('d F Y')}}
                    <?php }else{ ?> - Seluruh Barang <?php } ?></strong></h1>
            </div>
        </div>

        <div id="alamat" class="row">
            <div id="lampiran" class="col-md-8 col align-self-start">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5">SKPD : SEKRETARIAT DPRD</label>
                    <div class="col-sm-7">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5">KABUPATEN : FAKFAK</label>
                    <div class="col-sm-7">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5">PROVINSI : PAPUA BARAT</label>
                    <div class="col-sm-7">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5">GUDANG : </label>
                    <div class="col-sm-7">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5">MERK / TYPE / JENIS / NAMA BARANG : </label>
                    <div class="col-sm-7">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5">SATUAN : </label>
                    <div class="col-sm-7">

                    </div>
                </div>
            </div>
            <div id="tgl-srt" class="col-md-4 col align-self-end">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-6">KARTU NO. : </label>
                    <div class="col-sm-6">

                    </div>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-6">SPESIFIKASI : </label>
                    <div class="col-sm-6">  </div>
                </div>
            </div>
        </div>

        <table border="1" id="table1">
            <thead>
                <tr>
                    <th rowspan="2" width="1%" > NO </th>
                    <th rowspan="2" width="5%" > TANGGAL </th>
                    <th rowspan="2" width="5%" > NO./TGL. SURAT DASAR PENERIMAAN / PENGELUARAN </th>
                    <th rowspan="2" width="15%"> URAIAN </th>
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
                @foreach($data as $t => $vt)

                    @if($t == NULL)
                        <tr>
                            <td colspan="12"> <strong>LAPORAN KESELURUHAN</strong></td>
                        </tr>
                    @endif

                    @foreach($vt as $k => $v)

                        <tr>
                            <td align="center">{{$no++}}</td>
                            <td>{{$t}}</td>
                            <td>{{$t}}</td>
                            <td style="text-align:left; font-size:14;"><strong>{{ $k }}</strong></td> <!-- Jenis Barang -->
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        @foreach($v as $d)

                            <?php
                                if($tglawal == ''){
                                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                                    ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                                    ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                                    ->sum('tbl_barangmasuk.bm_jumlah');
                                }else{
                                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                                    ->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')
                                    ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                                    ->whereBetween('bm_tanggal', [$tglawal, $tglakhir])
                                    ->sum('tbl_barangmasuk.bm_jumlah');
                                }

                                if ($tglawal != '') {
                                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                                    ->whereBetween('bk_tanggal', [$tglawal, $tglakhir])
                                    ->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)
                                    ->sum('tbl_barangkeluar.bk_jumlah');
                                } else {
                                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                                    ->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)
                                    ->sum('tbl_barangkeluar.bk_jumlah');
                                }

                                $totalStok = $d->barang_stok + ($jmlmasuk-$jmlkeluar);
                            ?>



                            <tr style="text-align:left;">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align:left; font-size:12;">{{$d->barang_nama}}</td>
                                <td align="center">{{$jmlmasuk}}</td>
                                <td align="center">{{$jmlkeluar}}</td>
                                <td align="center">{{$totalStok}}</td>
                                <td>{{ number_format($d->barang_harga,0,"",'.')}}</td>
                                <td><?php $bertambah = $jmlmasuk * $d->barang_harga; echo number_format($bertambah,0,"",'.'); ?> </td>
                                <td><?php $berkurang = $jmlkeluar * $d->barang_harga; echo number_format($berkurang,0,"",'.'); ?></td>
                                <td><?php $sisa = $bertambah - $berkurang; echo number_format($sisa,0,"",'.'); ?></td>
                                <td>
                                    @if($totalStok == 0)
                                        Habis
                                    @endif
                                </td>
                            </tr>

                        @endforeach

                    @endforeach

                @endforeach
            </tbody>

        </table>

        <footer>
            <div id="ttd" class="row" style="width: 100%;position: relative;">
                <div class="col-md-4" style="width: 30%;position: absolute;">
                    <p id="pengurus"> <strong>MENGETAHUI <br />
                        SEKRETARIS DPRD <br />
                        KABUPATEN FAKFAK</strong>
                    </p>
                    <div id="nama-pengurus"><strong><u>SUPRIJONO, S.Sos, MM</u></strong><br />
                        NIP : 19710608 199610 1 002
                    </div>
                </div>
                <div class="col-md-4" style="width: 10%;"></div>

                <div class="col-md-4" style="width: 30%;position: absolute;right: 0;">
                    <p id="tanggal-pengurus"> Fakfak, {{date('d F Y')}} </p>
                    <p id="pengurus"><strong>PENGURUS BARANG</strong></p>
                    <div id="nama-pengurus"><strong><u>RUSDI</u></strong><br />
                        NIP : 19780910 200801 1 014
                    </div>
                </div>
            </div>
        </footer>


        </div>


    </div>

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"  ></script>

</html>
