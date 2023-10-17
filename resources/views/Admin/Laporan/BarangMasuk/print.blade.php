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
                        {{Carbon::parse($tglawal)->translatedFormat('d F Y')}} - {{Carbon::parse($tglakhir)->translatedFormat('d F Y')}}
                    <?php }else{ ?> - Seluruh Barang <?php } ?></strong></h1>
            </div>
        </div>


        <table border="1" id="table1">
            <thead>
                <tr>
                    <th rowspan="3" width="1%" > NO </th>
                    <th rowspan="3" width="5%" > TANGGAL </th>
                    <th rowspan="3" width="15%" > NAMA TOKO </th>
                    <th colspan="2"> DOKUMEN FAKTUR </th>
                    <th rowspan="3" width="20%"> NAMA BARANG</th>
                    <th rowspan="3" width="3%"> BANYAK NYA </th>
                    <th rowspan="3" width="3%"> HARGA SATUAN </th>
                    <th rowspan="3" width="3%"> JUMLAH HARGA </th>
                    <th colspan="2"> BUKTI PENERIMAAN </th>
                    <th rowspan="3"> KET </th>
                </tr>

                <tr>
                    <th rowspan="2" width="7%">NOMOR</th>
                    <th rowspan="2" width="5%">TANGGAL</th>

                    <th colspan="2"> B.A PENERIMAAN </th>

                </tr>

                <tr>
                    <th width="7%">NOMOR</th>
                    <th width="5%">TANGGAL</th>
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

        <footer>
            <div id="ttd" class="row">
                <div class="col-md-4">
                    <p id="pengurus"> <strong>MENGETAHUI <br />
                        SEKRETARIS DPRD <br />
                        KABUPATEN FAKFAK</strong>
                    </p>
                    <div id="nama-pengurus"><strong><u>SUPRIJONO, S.Sos, MM</u></strong><br />
                        NIP : 19710608 199610 1 002
                    </div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <p id="tanggal-pengurus"> Fakfak, {{date('d F Y')}} </p>
                    <p id="pengurus"><strong>PENGURUS BARANG</strong></p>
                    <div id="nama-pengurus"><strong><u>RUSDI</u></strong><br />
                        NIP : 19780910 200801 1 014
                    </div>
                </div>
            </div>
        </footer>

    </div>


</body>

</html>
