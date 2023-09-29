<?php namespace App\Pdf;

use Aryatama045\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Str;

class Berita extends Fpdf
{


    private $grand_total;
    private $grand_beli;
    private $grand_kirim;
    private $grand_harga;
    private $total;
    private $halaman;
    private $total_halaman;

    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';



    public function __construct($data)
    {

        $this->data     = $data;
        $this->header   = $this->data['header'];
        $this->detail   = $this->data['detail'];
        $this->general_setting = $this->data['general_setting'];


        $this->halaman=0;
        parent::__construct('P', 'mm', 'A4');
        $this->SetA4();
        $this->SetTitle('Surat Perintah Kerja - '.$this->header[0]->spk_kode, true); //.$this->header[0]->no_po
        $this->SetAuthor('None', true);
        $this->SetMargins(15, 7, 15, 12);
        $this->AddPage('P');
        $this->Body();
        $this->AliasNbPages();
    }



    #------ LIMIT JENIS  ---------------------------------------------------

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

    #------ END ---------------------------------------------------


    #------ Konversi Angka Ke Huruf  ---------------------------------------------------
        function penyebut($nilai) {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " ". $huruf[$nilai];
            } else if ($nilai <20) {
                $temp = $this->penyebut($nilai - 10). " belas";
            } else if ($nilai < 100) {
                $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . $this->penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " seribu" . $this->penyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
            } else if ($nilai < 1000000000000) {
                $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
            } else if ($nilai < 1000000000000000) {
                $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
            }     
            return $temp;
        }

        function terbilang($nilai) {
            if($nilai<0) {
                $hasil = "minus ". trim($this->penyebut($nilai));
            } else {
                $hasil = trim($this->penyebut($nilai));
            }
            return $hasil;
        }
    #------ Konversi Angka Ke Huruf  ---------------------------------------------------


    function Header()
    {

        //Lebar A4 = 190 + margin 10
        $this->total_halaman =  ceil(count($this->detail)/48); //ceil(count($this->detail)
        $this->halaman++;

        if($this->halaman == 1){

            $this->Ln(8);
            $parth_image = public_path() .'/uploads/image/';
            $this->Image($parth_image .$this->general_setting->web_logo, 24, 5, 20, 25);
            $this->setFont('Times','B',12);
            $this->cell(195,1,$this->general_setting->header_1,0,0, 'C');

            $this->Ln(5);
            $this->setFont('Times','B',12);
            $this->cell(195,1,$this->general_setting->header_2,0,0, 'C');

            $this->Ln(4);
            $this->setFont('Times','',9);
            $this->cell(195,2,$this->general_setting->alamat,0,0,'C');
            $this->cell(0,2,'Page '.$this->halaman." of ".$this->total_halaman,0,0,'R');

            $this->Ln(6);
            $this->Line(11,$this->GetY(),195,$this->GetY());
            $this->Ln(0.1);
            $this->Line(11,$this->GetY(),195,$this->GetY());


            $this->Ln(6);
            $this->Line(11,$this->GetY(),195,$this->GetY());
            $this->Ln(0.1);
            $this->Line(11,$this->GetY(),195,$this->GetY());
            $this->Ln(1);
            $this->setFont('Times','BU',14);
            $this->cell(0,10,'BERITA ACARA PEMERIKSAAN BARANG PERSEDIAAN / STOCK OPNAME',0,0,'C');
            $this->Ln(6);
            $this->setFont('Times','',10);
            $this->cell(0,10,'NOMOR : '.$this->header[0]->berita_kode,0,0,'C');


            $this->Line(11,$this->GetY(),195,$this->GetY());
            $this->Ln(4);


        }else{
            $this->Ln(12);
        }



        $this->HeaderList();
    }


    function HeaderList(){
        if($this->halaman == 1){
            $this->setFont('Times','',11);
            $this->MultiCell(175,6,$this->header[0]->spk_header, 0);

            $this->Ln(4);
            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'Nama ',0,0,'L');
            $this->cell(5,1,' : ',0,0,'L');
            $this->cell(45,1,$this->header[0]->p1_nama,0,0,'L');
            $this->Ln(6);

            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'NIP ',0,0,'L');
            $this->cell(5,1,' : ',0,0,'L');
            $this->cell(45,1,$this->header[0]->p1_nip,0,0,'L');
            $this->Ln(6);

            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'Jabatan ',0,0,'L');
            $this->cell(5,2,' : ',0,0,'L');
            $this->Multicell(70,4,$this->header[0]->p1_jabatan,0);
            $this->Ln(6);

            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'Alamat ',0,0,'L');
            $this->cell(5,1,' : ',0,0,'L');
            $this->cell(45,1,$this->header[0]->p1_alamat,0,0,'L');
            $this->Ln(8);

            $this->setFont('Times','',11);
            $this->cell(31,1,'Selanjutnya sebagai ',0,0,'L');
            $this->setFont('Times','B',11);
            $this->cell(25,1,' PIHAK  PERTAMA ',0,0,'L');
            $this->Ln(14);


            $this->setFont('Times','',11);
            $this->cell(150,1,'Memberi Perintah untuk melaksanakan Pekerjaan Kepada  :',0,0,'L');

            $this->Ln(6);
            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'Nama ',0,0,'L');
            $this->cell(5,1,' : ',0,0,'L');
            $this->cell(45,1,$this->header[0]->sp_nama,0,0,'L');
            $this->Ln(6);

            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'Pekerjaan ',0,0,'L');
            $this->cell(5,1,' : ',0,0,'L');
            $this->cell(45,1,$this->header[0]->sp_jabatan.' '. $this->header[0]->sp_perusahaan,0,0,'L');
            $this->Ln(6);

            $this->setFont('Times','',11);
            $this->cell(15,2,'',0,0,'L');
            $this->cell(45,2,'Alamat',0,0,'L');
            $this->cell(5,2,' : ',0,0,'L');
            $this->Multicell(85,4,$this->header[0]->sp_alamat,0);
            $this->Ln(4);

            $this->setFont('Times','',11);
            $this->cell(43,1,'Selanjutnya disebut sebagai ',0,0,'L');
            $this->setFont('Times','B',11);
            $this->cell(25,1,' PIHAK  KEDUA',0,0,'L');
            $this->Ln(14);


            $this->setFont('Times','B',11);
            $this->cell(37,1,' PIHAK  PERTAMA',0,0,'L');
            $this->setFont('Times','',11);
            $this->cell(48,1,'memberikan pekerjaan kepada ',0,0,'L');
            $this->setFont('Times','B',11);
            $this->cell(30,1,' PIHAK  KEDUA',0,0,'L');
            $this->setFont('Times','',11);
            $this->cell(7,1,' dan ',0,0,'L');
            $this->setFont('Times','B',11);
            $this->cell(30,1,' PIHAK  KEDUA',0,0,'L');
            $this->setFont('Times','',11);
            $this->cell(10,1,' menyatakan ',0,0,'L');
            $this->Ln(6);

            $this->setFont('Times','',11);
            $this->cell(175,1,' telah menerima pekerjaan tersebut, dengan ketentuan sebagai berikut : ',0,0,'L');
            $this->Ln(8);
        }

    }

    function Body(){

        // $this->cell(70,1,$this->terbilang('2023'),0,0,'C');
        // $this->Ln(4);

        $baris  = 1;
        $row    = 1;

        // for($x=0; $x < 2; $x++){

            foreach ($this->detail as $k => $value) {

                $this->setFont('Times','',11);
                $this->cell(15,2,'',0,0,'L');
                $this->cell(45,2,$value->jenis_pekerjaan,0,0,'L');
                $this->cell(5,2,' : ',0,0,'L');
                $this->Multicell(110,4,$value->detail_pekerjaan,0);
                $this->Ln(4);
                if($this->halaman==1){
                    if($baris==4){
                        $this->AddPage();
                        $baris = 1;
                    }
                }

                $baris++;
            }
        // }


        $this->Ln(4);
        $this->Multicell(175,5,$this->header[0]->spk_footer,0);
        $this->Ln(8);
        $this->tandaTangan();
        $this->Ln(8);
        $this->mengetahui();



    }


    function tandaTangan(){
        $this->Ln(6);
        $this->setFont('Times','B',11);
        $this->cell(20,1,'',0,0,'L');
        $this->cell(45,1,'PIHAK KEDUA (II)',0,0,'C');
        $this->cell(50,1,'',0,0,'L');
        $this->cell(45,1,'PIHAK PERTAMA (I)',0,0,'C');

        $this->Ln(4);
        $this->cell(20,1,'',0,0,'L');
        $this->setFont('Times','B',11);
        $this->cell(45,1,$this->header[0]->sp_perusahaan,0,0,'C');
        $this->cell(50,1,'',0,0,'L');
        $this->cell(45,1,'PEJABAT PEMBUAT KOMITMEN',0,0,'C');

        $this->Ln(4);
        $this->cell(20,1,'',0,0,'L');
        $this->cell(45,1,'',0,0,'C');

        $this->Ln(25);
        $this->setFont('Times','BU',11);
        $this->cell(20,1,'',0,0,'L');
        $this->cell(45,1,$this->header[0]->sp_nama,0,0,'C');
        $this->cell(50,1,'',0,0,'L');
        $this->cell(45,1,$this->header[0]->p1_nama,0,0,'C');

        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(20,1,'',0,0,'L');
        $this->cell(45,1,$this->header[0]->sp_jabatan,0,0,'C');
        $this->cell(50,1,'',0,0,'L');
        $this->cell(45,1,'NIP '.$this->header[0]->p1_nip,0,0,'C');
        $this->Ln(4);
    }

    function mengetahui(){
        $this->Ln(10);
        $this->setFont('Times','B',11);
        $this->cell(64,1,'',0,0,'L');
        $this->cell(45,1,'Mengetahui :',0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(64,1,'',0,0,'L');
        $this->cell(45,1,'KEPALA BADAN PENGELOLAAN KEUANGAN',0,0,'C');
        $this->Ln(4);
        $this->cell(64,1,'',0,0,'L');
        $this->setFont('Times','B',11);
        $this->cell(45,1,'DAN ASET DAERAH KABUPATEN FAKFAK',0,0,'C');
        $this->Ln(4);

        $this->Ln(25);
        $this->setFont('Times','BU',11);
        $this->cell(64,1,'',0,0,'L');
        $this->cell(45,1,$this->header[0]->m_nama,0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(64,1,'',0,0,'L');
        $this->cell(45,1,$this->header[0]->m_jabatan,0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(64,1,'',0,0,'L');
        $this->cell(45,1,'NIP '.$this->header[0]->m_nip,0,0,'C');
    }

    function FooterTotal(){
        // Go to 1.5 cm from bottom
        $this->SetY(-120);
        $this->Ln(2);
        // $this->Line(11,$this->GetY(),199,$this->GetY());
        // $this->Ln(2);
        // $this->cell(18,1,'*Note : ',0,0,'L');
        // $this->cell(95,1,'Total',0,0,'R');
        // $this->cell(20,1,number_format('4555',0,"",'.'),0,0,'R');
        // $this->cell(20,1,'',0,0,'R');
        $this->cell(115,1,'',0,0,'L');
        $this->cell(25,1,'Dibuat di ',0,0,'L');
        $this->cell(18,1,' : Fakfak',0,0,'L');
        $this->Ln(6);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(25,1,'Pada Tanggal ',0,0,'L');
        $this->cell(18,1,' : 18 April 2023',0,0,'L');
        $this->Ln(6);
        $this->setFont('Times','B',11);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,'PEJABAT PEMBUAT KOMITMEN',0,0,'C');
        $this->Ln(4);
        $this->cell(115,1,'',0,0,'L');
        $this->setFont('Times','',11);
        $this->cell(45,1,'BADAN PENGELOLA KEUANGAN DAN',0,0,'C');
        $this->Ln(4);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,'ASET DAERAH KAB. FAKFAK',0,0,'C');

        $this->Ln(25);
        $this->setFont('Times','BU',11);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,'TAJUDIN LA JAHALIA, S.IP, M.Si.',0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,'Pembina Utama Muda (IV/c)',0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,'NIP 19680418 199203 1 008',0,0,'C');



    }

    function FooterSubTotal(){
        // Go to 1.5 cm from bottom
        // $this->SetY(-75);
        $this->Ln(4);
        $this->Line(11,$this->GetY(),195,$this->GetY());
        $this->Ln(4);
        $this->cell(100,1,'',0,0,'L');
        $this->cell(20,1,'SUB TOTAL',0,0,'L');
        $this->cell(10,1,number_format($this->grand_kirim,0,"",'.'),0,0,'R');
        $this->cell(25,1,'',0,0,'L');
        $this->cell(25,1,number_format($this->grand_harga,0,"",'.'),0,0,'R');
        $this->Ln(4);
        $this->Line(11,$this->GetY(),195,$this->GetY());
    }
}
