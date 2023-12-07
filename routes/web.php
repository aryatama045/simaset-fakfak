<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\BarangkeluarController;
use App\Http\Controllers\Admin\BarangmasukController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JenisBarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PbController;
use App\Http\Controllers\Admin\SpkController;
use App\Http\Controllers\Admin\LapBarangKeluarController;
use App\Http\Controllers\Admin\LapBarangMasukController;
use App\Http\Controllers\Admin\LapStokBarangController;
use App\Http\Controllers\Admin\LapHabisPakaiController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MerkController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Master\AksesController;
use App\Http\Controllers\Master\AppreanceController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/link', function () {
// $target = '/home/u1579603/public_html/newapp/simaset/storage/app/public';
// $shortcut = '/home/u1579603/public_html/storage';
// symlink($target, $shortcut);
// });

// login admin
Route::middleware(['preventBackHistory'])->group(function () {
    Route::get('/admin/login', [LoginController::class, 'index'])->middleware('useractive');
    Route::post('/admin/proseslogin', [LoginController::class, 'proseslogin'])->middleware('useractive');
    Route::get('/admin/logout', [LoginController::class, 'logout']);
});

// admin
Route::group(['middleware' => 'userlogin'], function () {

    // Profile
    Route::get('/admin/profile/{user}', [UserController::class, 'profile']);
    Route::post('/admin/updatePassword/{user}', [UserController::class, 'updatePassword']);
    Route::post('/admin/updateProfile/{user}', [UserController::class, 'updateProfile']);
    Route::get('/admin/appreance/', [AppreanceController::class, 'index']);
    Route::post('/admin/appreance/{setting}', [AppreanceController::class, 'update']);

    Route::middleware(['checkRoleUser:/dashboard,menu'])->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/admin', [DashboardController::class, 'index']);
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    });

    Route::middleware(['checkRoleUser:/jenisbarang,submenu'])->group(function () {
        // Jenis Barang
        Route::get('/admin/jenisbarang', [JenisBarangController::class, 'index']);
        Route::get('/admin/jenisbarang/show/', [JenisBarangController::class, 'show'])->name('jenisbarang.getjenisbarang');
        Route::post('/admin/jenisbarang/proses_tambah/', [JenisBarangController::class, 'proses_tambah'])->name('jenisbarang.store');
        Route::post('/admin/jenisbarang/proses_ubah/{jenisbarang}', [JenisBarangController::class, 'proses_ubah']);
        Route::post('/admin/jenisbarang/proses_hapus/{jenisbarang}', [JenisBarangController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/kategori,submenu'])->group(function () {
        // Jenis Barang
        Route::get('/admin/kategori', [KategoriController::class, 'index']);
        Route::get('/admin/kategori/show/', [KategoriController::class, 'show'])->name('kategori.getkategori');
        Route::post('/admin/kategori/proses_tambah/', [KategoriController::class, 'proses_tambah'])->name('kategori.store');
        Route::post('/admin/kategori/proses_ubah/{kategori}', [KategoriController::class, 'proses_ubah']);
        Route::post('/admin/kategori/proses_hapus/{kategori}', [KategoriController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/satuan,submenu'])->group(function () {
        // Satuan
        Route::resource('/admin/satuan', \App\Http\Controllers\Admin\SatuanController::class);
        Route::get('/admin/satuan/show/', [SatuanController::class, 'show'])->name('satuan.getsatuan');
        Route::post('/admin/satuan/proses_tambah/', [SatuanController::class, 'proses_tambah'])->name('satuan.store');
        Route::post('/admin/satuan/proses_ubah/{satuan}', [SatuanController::class, 'proses_ubah']);
        Route::post('/admin/satuan/proses_hapus/{satuan}', [SatuanController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/pegawai,submenu'])->group(function () {
        // Supplier
        Route::resource('/admin/pegawai', \App\Http\Controllers\Admin\PegawaiController::class);
        Route::get('/admin/pegawai/show/', [PegawaiController::class, 'show'])->name('pegawai.getpegawai');
        Route::post('/admin/pegawai/proses_tambah/', [PegawaiController::class, 'proses_tambah'])->name('pegawai.store');
        Route::post('/admin/pegawai/proses_ubah/{pegawai}', [PegawaiController::class, 'proses_ubah']);
        Route::post('/admin/pegawai/proses_hapus/{pegawai}', [PegawaiController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/supplier,submenu'])->group(function () {
        // Supplier
        Route::resource('/admin/supplier', \App\Http\Controllers\Admin\SupplierController::class);
        Route::get('/admin/supplier/show/', [SupplierController::class, 'show'])->name('supplier.getsupplier');
        Route::post('/admin/supplier/proses_tambah/', [SupplierController::class, 'proses_tambah'])->name('supplier.store');
        Route::post('/admin/supplier/proses_ubah/{supplier}', [SupplierController::class, 'proses_ubah']);
        Route::post('/admin/supplier/proses_hapus/{supplier}', [SupplierController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/merk,submenu'])->group(function () {
        // Merk
        Route::resource('/admin/merk', \App\Http\Controllers\Admin\MerkController::class);
        Route::get('/admin/merk/show/', [MerkController::class, 'show'])->name('merk.getmerk');
        Route::post('/admin/merk/proses_tambah/', [MerkController::class, 'proses_tambah'])->name('merk.store');
        Route::post('/admin/merk/proses_ubah/{merk}', [MerkController::class, 'proses_ubah']);
        Route::post('/admin/merk/proses_hapus/{merk}', [MerkController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/barang,submenu'])->group(function () {
        // Barang
        Route::resource('/admin/barang', \App\Http\Controllers\Admin\BarangController::class);
        Route::get('/admin/datahistory/', [BarangController::class, 'datahistory']);

        Route::get('/admin/barang/gethistory/', [BarangController::class, 'gethistory'])->name('barang.gethistory');
        Route::get('/admin/barang/show/', [BarangController::class, 'show'])->name('barang.getbarang');
        Route::post('/admin/barang/proses_tambah/', [BarangController::class, 'proses_tambah'])->name('barang.store');
        Route::post('/admin/barang/bulk_delete/', [BarangController::class, 'bulk_delete'])->name('barang.bulk_delete');
        Route::post('/admin/barang/proses_ubah/{barang}', [BarangController::class, 'proses_ubah']);
        Route::post('/admin/barang/proses_hapus/{barang}', [BarangController::class, 'proses_hapus']);
        Route::post('/admin/barang/import_barang/', [BarangController::class, 'import_barang'])->name('barang.import');

        Route::post('/admin/barang/export_barang/', [BarangController::class, 'export_barang']);

        Route::controller(BarangController::class)->group(function () {
            Route::prefix('/admin/barang')->group(function () {
                Route::get('datahistory', 'showhistory')->name('barang.showHistory');
            });
        });
    });

    Route::middleware(['checkRoleUser:/customer,menu'])->group(function () {
        // Customer
        Route::resource('/admin/customer', \App\Http\Controllers\Admin\CustomerController::class);
        Route::get('/admin/customer/show/', [CustomerController::class, 'show'])->name('customer.getcustomer');
        Route::post('/admin/customer/proses_tambah/', [CustomerController::class, 'proses_tambah'])->name('customer.store');
        Route::post('/admin/customer/proses_ubah/{customer}', [CustomerController::class, 'proses_ubah']);
        Route::post('/admin/customer/proses_hapus/{customer}', [CustomerController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/pb,submenu'])->group(function () {
        // Pengadaan
        Route::resource('/admin/pb', \App\Http\Controllers\Admin\PbController::class);
        Route::get('admin/pb/add', [PbController::class, 'add'])->name('pb.add');

        Route::get('products/print_barcode','ProductController@printBarcode')->name('product.printBarcode');
        Route::get('/admin/pb/show/', [PbController::class, 'show'])->name('pb.getpb');
        Route::post('/admin/pb/proses_tambah/', [PbController::class, 'proses_tambah'])->name('pb.store');
        Route::post('/admin/pb/proses_ubah/{barangmasuk}', [PbController::class, 'proses_ubah']);
        Route::post('/admin/pb/proses_hapus/{barangmasuk}', [PbController::class, 'proses_hapus']);
        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);
        Route::get('/admin/barang/listbarang/{param}', [BarangController::class, 'listbarang']);

        Route::get('/admin/pb/genInvoice/{id}', [PbController::class, 'genInvoice'])->name('sale.invoice');

        Route::post('/admin/pb/pengadaan_export/', [PbController::class, 'pengadaan_export']);
    });

    Route::middleware(['checkRoleUser:/spk,submenu'])->group(function () {
        // Spk
        Route::resource('/admin/spk', \App\Http\Controllers\Admin\SpkController::class);
        Route::get('admin/spk/add', [SpkController::class, 'add'])->name('spk.add');

        Route::get('/admin/spk/show/', [SpkController::class, 'show'])->name('spk.getspk');
        Route::post('/admin/spk/proses_tambah/', [SpkController::class, 'proses_tambah'])->name('spk.store');
        Route::post('/admin/spk/proses_hapus/{barangmasuk}', [SpkController::class, 'proses_hapus']);
        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);
        Route::get('/admin/barang/listbarang/{param}', [BarangController::class, 'listbarang']);

        Route::get('/admin/spk/genInvoice/{id}', [SpkController::class, 'genInvoice'])->name('sale.invoice');

        Route::post('/admin/spk/spk_export/', [SpkController::class, 'spk_export']);
    });

    Route::middleware(['checkRoleUser:/berita,submenu'])->group(function () {
        // Berita
        Route::resource('/admin/berita', \App\Http\Controllers\Admin\BeritaController::class);
        Route::get('admin/berita/add', [BeritaController::class, 'add'])->name('berita.add');

        Route::get('/admin/berita/show/', [BeritaController::class, 'show'])->name('berita.getberita');
        Route::post('/admin/berita/proses_tambah/', [BeritaController::class, 'proses_tambah'])->name('berita.store');
        Route::post('/admin/berita/proses_hapus/{berita}', [BeritaController::class, 'proses_hapus']);

        Route::get('/admin/berita/berita_edit/{berita}', [BeritaController::class, 'berita_edit']);
        Route::post('/admin/berita/proses_edit/{berita}', [BeritaController::class, 'proses_edit']);

        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);
        Route::get('/admin/berita/listbarang/{param}', [BeritaController::class, 'listbarang']);

        Route::get('/admin/berita/genInvoice/{id}', [BeritaController::class, 'genInvoice'])->name('sale.invoice');

        Route::post('/admin/berita/berita_export/', [BeritaController::class, 'berita_export']);
    });

    Route::middleware(['checkRoleUser:/barang-masuk,submenu'])->group(function () {
        // Barang Masuk
        Route::resource('/admin/barang-masuk', \App\Http\Controllers\Admin\BarangmasukController::class);
        Route::get('/admin/barang-masuk/show/', [BarangmasukController::class, 'show'])->name('barang-masuk.getbarang-masuk');
        Route::post('/admin/barang-masuk/proses_tambah/', [BarangmasukController::class, 'proses_tambah'])->name('barang-masuk.store');
        Route::post('/admin/barang-masuk/proses_ubah/{barangmasuk}', [BarangmasukController::class, 'proses_ubah']);
        Route::post('/admin/barang-masuk/proses_hapus/{barangmasuk}', [BarangmasukController::class, 'proses_hapus']);
        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);
        Route::get('/admin/barang/listbarang/{param}', [BarangController::class, 'listbarang']);

        Route::get('/admin/barang-masuk/listpengadaan/{param}', [BarangmasukController::class, 'listpengadaan']);
        Route::get('/admin/barang-masuk/copydocument/{id}', [BarangmasukController::class, 'copydocument']);
        Route::post('/admin/barang-masuk/proses_copy/{id}', [BarangmasukController::class, 'proses_copy'])->name('barang-masuk.copy');
    });

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {
        // Barang Keluar
        Route::resource('/admin/barang-keluar', \App\Http\Controllers\Admin\BarangkeluarController::class);
        Route::get('/admin/barang-keluar/show/', [BarangkeluarController::class, 'show'])->name('barang-keluar.getbarang-keluar');
        Route::post('/admin/barang-keluar/proses_tambah/', [BarangkeluarController::class, 'proses_tambah'])->name('barang-keluar.store');
        Route::post('/admin/barang-keluar/proses_ubah/{barangkeluar}', [BarangkeluarController::class, 'proses_ubah']);
        Route::post('/admin/barang-keluar/proses_hapus/{barangkeluar}', [BarangkeluarController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {
        // Laporan Barang Masuk
        Route::resource('/admin/lap-barang-masuk', \App\Http\Controllers\Admin\LapBarangMasukController::class);
        Route::get('/admin/lapbarangmasuk/print/', [LapBarangMasukController::class, 'print'])->name('lap-bm.print');
        Route::get('/admin/lapbarangmasuk/pdf/', [LapBarangMasukController::class, 'pdf'])->name('lap-bm.pdf');
        Route::get('/admin/lap-barang-masuk/show/', [LapBarangMasukController::class, 'show'])->name('lap-bm.getlap-bm');
    });

    Route::middleware(['checkRoleUser:/lap-barang-keluar,submenu'])->group(function () {
        // Laporan Barang Keluar
        Route::resource('/admin/lap-barang-keluar', \App\Http\Controllers\Admin\LapBarangKeluarController::class);
        Route::get('/admin/lapbarangkeluar/print/', [LapBarangKeluarController::class, 'print'])->name('lap-bk.print');
        Route::get('/admin/lapbarangkeluar/pdf/', [LapBarangKeluarController::class, 'pdf'])->name('lap-bk.pdf');
        Route::get('/admin/lap-barang-keluar/show/', [LapBarangKeluarController::class, 'show'])->name('lap-bk.getlap-bk');
    });

    Route::middleware(['checkRoleUser:/lap-stok-barang,submenu'])->group(function () {
        // Laporan Stok Barang
        Route::resource('/admin/lap-stok-barang', \App\Http\Controllers\Admin\LapStokBarangController::class);
        Route::get('/admin/lapstokbarang/print/', [LapStokBarangController::class, 'print'])->name('lap-sb.print');
        Route::get('/admin/lapstokbarang/pdf/', [LapStokBarangController::class, 'pdf'])->name('lap-sb.pdf');
        Route::get('/admin/lap-stok-barang/show/', [LapStokBarangController::class, 'show'])->name('lap-sb.getlap-sb');
    });

    Route::middleware(['checkRoleUser:/lap-habis-pakai,submenu'])->group(function () {
        // Laporan Habis Pakai
        Route::resource('/admin/lap-habis-pakai', \App\Http\Controllers\Admin\LapHabisPakaiController::class);
        Route::get('/admin/laphabispakai/print/', [LapHabisPakaiController::class, 'print'])->name('lap-hp.print');
        Route::get('/admin/laphabispakai/pdf/', [LapHabisPakaiController::class, 'pdf'])->name('lap-hp.pdf');
        Route::get('/admin/lap-habis-pakai/show/', [LapHabisPakaiController::class, 'show'])->name('lap-hp.getlap-hp');
    });

    Route::middleware(['checkRoleUser:1,othermenu'])->group(function () {

        Route::middleware(['checkRoleUser:2,othermenu'])->group(function () {
            // Menu
            Route::resource('/admin/menu', \App\Http\Controllers\Master\MenuController::class);
            Route::post('/admin/menu/hapus', [MenuController::class, 'hapus']);
            Route::get('/admin/menu/sortup/{sort}', [MenuController::class, 'sortup']);
            Route::get('/admin/menu/sortdown/{sort}', [MenuController::class, 'sortdown']);
        });

        Route::middleware(['checkRoleUser:3,othermenu'])->group(function () {
            // Role
            Route::resource('/admin/role', \App\Http\Controllers\Master\RoleController::class);
            Route::get('/admin/role/show/', [RoleController::class, 'show'])->name('role.getrole');
            Route::post('/admin/role/hapus', [RoleController::class, 'hapus']);
        });

        Route::middleware(['checkRoleUser:4,othermenu'])->group(function () {
            // List User
            Route::resource('/admin/user', \App\Http\Controllers\Master\UserController::class);
            Route::get('/admin/user/show/', [UserController::class, 'show'])->name('user.getuser');
            Route::post('/admin/user/hapus', [UserController::class, 'hapus']);
        });

        Route::middleware(['checkRoleUser:5,othermenu'])->group(function () {
            // Akses
            Route::get('/admin/akses/{role}', [AksesController::class, 'index']);
            Route::get('/admin/akses/addAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'addAkses']);
            Route::get('/admin/akses/removeAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'removeAkses']);
            Route::get('/admin/akses/setAll/{role}', [AksesController::class, 'setAllAkses']);
            Route::get('/admin/akses/unsetAll/{role}', [AksesController::class, 'unsetAllAkses']);
        });

        Route::middleware(['checkRoleUser:6,othermenu'])->group(function () {
            // Web
            Route::resource('/admin/web', \App\Http\Controllers\Master\WebController::class);
        });
    });
});
