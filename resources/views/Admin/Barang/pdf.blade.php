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


    <title>{{$title}}</title>

    <link rel="stylesheet" href="{{url('/assets/css/pdf_bs1.css')}}" />

    <style>
        /* Default */
        * {
            /* font-family: Times, Helvetica, sans-serif; */
            font-family: "Times New Roman", Times, serif, sans-serif !important;
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
                </div>
            </div>
        </header>

        <hr class="garis1"/>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1 class="keclogo"><strong> Data Barang </strong></h1>
            </div>
        </div>

        <div id="alamat" class="row">
            <div id="lampiran" class="col-md-6">
                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-3 col-form-label">SKPD : SEKRETARIAT DPRD</label>
                    <div class="col-sm-9">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-3 col-form-label">KABUPATEN : FAKFAK</label>
                    <div class="col-sm-9">

                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-3 col-form-label">KOTA : FAKFAK</label>
                    <div class="col-sm-9">

                    </div>
                </div>
            </div>
        </div>


        <table border="1" id="table1">
            <thead>
                <tr>
                    <th width="1%"> NO </th>
                    <th width="12%"> KODE </th>
                    <th width="15%"> NAMA BARANG</th>
                    <th width="3%"> JENIS</th>
                    <th width="3%"> KATEGORI</th>
                    <th width="3%"> SATUAN </th>
                    <th width="3%"> STOK </th>
                    <th width="5%"> HARGA </th>
                    <th width="10%"> CREATE BY </th>

                </tr>

                <tr id="thbottom">
                    @for($x=1; $x <= 9; $x++)
                    <th>{{$x}}</th>
                    @endfor
                </tr>
            </thead>

            <tbody>
                @php $no=1;  @endphp
                @foreach($data as $dt)
                    <!-- Header List -->
                    <tr>
                        <td align="center">
                            <b>{{$no++}}</b>
                        </td>
                        <td style="font-size:14px;text-align:left;"><b> {{ $dt->barang_kode}}</b></td>
                        <td>{{ $dt->barang_nama}}</td>
                        <td>{{ $dt->jenisbarang_nama}}</td>
                        <td>{{ $dt->kategori_nama}}</td>
                        <td>{{ $dt->satuan_nama}}</td>
                        <td>{{ $dt->barang_stok}}</td>
                        <td align="center">{{ number_format($dt->barang_harga,2) }}</td> <!-- Total Harga -->
                        <td>{{ $dt->make_by}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
