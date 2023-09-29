<?php namespace App\Pdf;
use Aryatama045\Fpdf\Fpdf\Fpdf;

class Nota extends Fpdf
{

    private $grand_total;
    private $grand_beli;
    private $grand_kirim;
    private $grand_harga;
    private $total;
    private $halaman;
    private $total_halaman;

    public function __construct($data)
    {
        $this->data     = $data;
        $this->header   = $this->data['header'];
        $this->detail   = $this->data['detail'];
        $this->general_setting = $this->data['general_setting'];


        $this->halaman=0;
        parent::__construct('P', 'mm', 'A4');
        $this->SetA4();
        $this->SetTitle('Nota Pesan - '.$this->header[0]->no_dok, true); //.$this->header[0]->no_po
        $this->SetAuthor('None', true);
        $this->SetMargins(15, 7, 15, 12);
        $this->AddPage('P');
        $this->Body();
        $this->AliasNbPages();
    }


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
        $this->Ln(1);
        $this->setFont('Times','BU',14);
        $this->cell(0,10,'NOTA PESANAN',0,0,'C');
        $this->Ln(6);
        $this->setFont('Times','',10);
        $this->cell(0,10,'NOMOR : '.$this->header[0]->no_dok,0,0,'C');



        $this->Ln(16);
        $this->setFont('Times','',11);
        $this->cell(25,2,'Kepada Yth :',0,0,'L');
        // $this->cell(100,1,' : ',0,0,'L');
        // $this->cell(25,1,'No. Po',0,0,'L');
        // $this->cell(0,1,': ',0,0,'L');

        $this->Ln(5);
        $this->cell(25,2,'Pimpinan ' .$this->header[0]->supplier,0,0,'L');
        // $this->cell(100,1,': ',0,0,'L');
        // $this->cell(25,1,'Supplier.',0,0,'L');
        // $this->cell(15,1,': ',0,0,'L');
        $this->Ln(5);

        $this->cell(25,2,$this->header[0]->supplier_keterangan,0,0,'L');
        // $this->cell(100,1,': ',0,0,'L');
        // $this->cell(25,1,'Company',0,0,'L');
        // $this->cell(100,1,': ',0,0,'L');
        $this->Ln(14);

        $this->cell(25,2,'Dengan Hormat,',0,0,'L');
        // $this->cell(100,1,': ',0,0,'L');
        // $this->cell(25,1,'Company',0,0,'L');
        // $this->cell(100,1,': ',0,0,'L');
        $this->Ln(5);

        // $this->cell(20,1,'',0,0,'L');
        // $this->cell(100,1,' ',0,0,'L');
        // $this->cell(25,1,'Doc Date',0,0,'L');
        // $this->cell(15,1,': '.date('d-m-Y'),0,0,'L');
        // $this->Ln(4);

    }


        $this->HeaderList();
    }

    function HeaderList(){
        // $this->Ln(4);
        // $this->Line(11,$this->GetY(),195,$this->GetY());
        // $this->Ln(5);
        // $this->cell(10,1,'NO.',0,0,'C');
        // $this->cell(45,1,'SKU',0,0,'L');
        // $this->cell(60,1,'Nama Barang',0,0,'L');
        // $this->cell(25,1,'Qty',0,0,'C');
        // $this->cell(20,1,'Unit',0,0,'C');
        // $this->cell(20,1,'Unit Price',0,0,'C');
        // $this->Ln(5);
        // $this->Line(11,$this->GetY(),195,$this->GetY());
        // $this->Ln(4);
    }

    function Body(){

        $this->Ln(1);
        $isi_surat = $this->header[0]->pb_keterangan;

        $this->MultiCell(175,6,$isi_surat, 0);

        $this->Ln(6);
        $baris = 1;
        $row=1;


        // dd($detail);

        // for($x=0; $x < 40; $x++){
            foreach ($this->detail as $value) {
                if($this->halaman==1){
                    if($baris==36){
                        // $this->FooterSubTotal();
                        $this->AddPage();
                        $baris = 1;
                    }
                }elseif($this->halaman==$this->total_halaman){
                    if($baris==46){
                        // $this->FooterSubTotal();
                        $this->AddPage();
                        $baris = 1;
                    }
                }else{
                    if($baris==42){
                        // $this->FooterSubTotal();
                        $this->AddPage();
                        $baris = 1;
                    }
                }
                $this->cell(15,1,'',0,0,'L');
                $this->cell(5,1,$row++.'.',0,0,'L');
                // $this->cell(45,1,$value->barang_kode,0,0,'L');
                $this->cell(20,1,$value->barang_nama,0,0,'L');
                $this->cell(25,1,number_format($value->jumlah,0,"",'.'),0,0,'C');
                $this->cell(20,1,$value->satuan_nama,0,0,'L');
                // $this->cell(20,1,number_format($value->pb_harga,0,"",'.'),0,0,'C');
                // $this->grand_kirim += $value->qty;
                // $this->grand_harga += $value->harga;
                $this->Ln(5);
                $baris++;
            }
        // }

        $this->Ln(6);
        $isi_footer = $this->header[0]->pb_footer;

        $this->MultiCell(175,6,$isi_footer, 0);

        $this->FooterTotal();
        // $this->grand_harga = 0;
        // $this->grand_kirim = 0;
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
        $this->cell(18,1,' : '.$this->header[0]->pb_tanggal,0,0,'L');
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
        $this->cell(45,1,$this->header[0]->nama_lengkap,0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,$this->header[0]->jabatan,0,0,'C');
        $this->Ln(4);
        $this->setFont('Times','B',11);
        $this->cell(115,1,'',0,0,'L');
        $this->cell(45,1,'NIP '.$this->header[0]->nip,0,0,'C');



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
