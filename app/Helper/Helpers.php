<?php

function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	echo $hasil_rupiah;
}

function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit));
}

function limit_words2($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,5,$word_limit));
}

function limit_words3($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,10,$word_limit));
}

function limit_words4($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,15,$word_limit));
}

function tgl_indo($tanggal){
    $bulan = array (
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    // variabel pecahkan 0 = tahun
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tanggal
    return $pecahkan[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
}


function hari_ini(){
    $hari = date ("D");

    switch($hari){
        case 'Sun':
            $hari_ini = "Minggu";
        break;

        case 'Mon':
            $hari_ini = "Senin";
        break;

        case 'Tue':
            $hari_ini = "Selasa";
        break;

        case 'Wed':
            $hari_ini = "Rabu";
        break;

        case 'Thu':
            $hari_ini = "Kamis";
        break;

        case 'Fri':
            $hari_ini = "Jumat";
        break;

        case 'Sat':
            $hari_ini = "Sabtu";
        break;

        default:
            $hari_ini = "Tidak di ketahui";
        break;
    }

    return "<b>" . $hari_ini . "</b>";

}

function hari_tanggal_tahun($waktu)
{
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $spell_tanggal = date('j', strtotime($waktu));
    $tanggal = $this->terbilang($spell_tanggal);


    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $spell_tahun = date('Y', strtotime($waktu));
    $tahun = $this->terbilang($spell_tahun);
    $jam = date( 'H:i:s', strtotime($waktu));

    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam"; Rabu, 13 Maret 2019

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari Tanggal $tanggal Bulan $bulan Tahun $tahun";

}
