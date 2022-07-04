<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Events\SendGlobalNotification;

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
Route::get('/', function(){
    return view('welcome');
});

Auth::routes();
// Route Untuk Omah Kunci



Route::get('/accountsetting', 'AccountSetting@index');
Route::post('/accountsetting/update', 'AccountSetting@update')->name('accountupdate');

Route::get('/redirecting', 'RedirectController@index');

Route::post('/cari', 'KasirController@cari')->name('cari');

Route::middleware(["role:kasir|manager"])->group(function(){
    Route::get("/selesaikanpreorder/{idpre}","PreorderController@lunasi");
    Route::post("/cetaksj2","MenuSuratJalan@cetaksj");
    Route::post("/hapussuratjalan2","MenuSuratJalan@hapussj");
    Route::post("/printtandaterima", "TransaksiController@printtt");


    Route::get("/itdash",function(){
        return view("laporan.stok");
    });
    Route::get("/normalize","ITController@normalizetrans");


    Route::post("/hapusdraf",'TransaksiController@hapusdraft');
    Route::get("/menusuratjalan","MenuSuratJalan@index");
    Route::post("/getdetailofreturn", 'KasirController@loadreturinfo');
    Route::post('/loadpreorder','PreorderController@load');
    Route::post('/checkdata', 'CheckDataController@index');
    route::post('/doreturn', 'ReturController@retur')->name('doreturn');
    Route::post('/tampilreturn', 'TransaksiController@tampilreturn');
    Route::post("/printnotakecilbc", 'KasirController@printnotakecil');
    Route::post('/removesection','Kasir2Controller@remover');
    Route::get('/kasir', 'Kasir2Controller@index')
->name("kasir");
    Route::post("/kirimsj","PreorderController@kirimsj")->name("kirimsj");
    Route::post("/kirimsj2","PreorderController@sj2")->name("kirimsj2");
    Route::post('/downloadtrans','transaksiController@downloadtransaksi')->name('download_trans');
    
    Route::post('/tambahItem', 'KasirController@tambahTransaksiDetail');
    Route::post('/bayarcicilannotakecil', 'KasirController@bcnk');
    Route::post('/infopreorder', 'RiwayatPre@showdetail');
    Route::post('/selesaitransaksi', 'KasirController@selesai')->name('selesai');
    Route::get('/selesai', 'KasirController@reset');
    Route::get('/selesaitrans', 'KasirController@forgoting');
    Route::post('/loader', 'KasirController@loader');
    Route::get('/resettransaction', 'KasirController@resetTransaction');
    Route::get('/removedetail', 'KasirController@removedetail');
    Route::post('/tambahpre','PreorderController@tambahpreorder');
    Route::post('/selesaipreorder','KasirController@selesaipreorder');
    Route::get('/hapusdraft/{id}', 'transaksiController@hapus')->name('hapusdraft');
    Route::get('/transaksi', 'transaksiController@index');
    Route::post('/cetaknotakecil', 'KasirController@cetaknotakecil');
    Route::get('/notabesar', 'PreorderController@index');
    Route::post('/tambahpreorder', 'PreorderController@tambahtransaksi');

    Route::post('/loadsingletrans', 'transaksiController@showDetail');

    Route::post('/loaddatanb', 'PreorderController@loaddata');
    Route::post("/searchnotapreorder", "PreorderController@search");
    Route::post("/getnb", "PreorderController@getnb");
    Route::post("/bayarpreorder", "PreorderController@bayarpreorder");
    Route::post("/cetaknotabesar", "PreorderController@cetaknotabesar");
    Route::post("/cetaksjnb", "PreorderController@cetaksuratjalan");
    Route::post("/caritranspreorder", "TransaksiPreorder@index")->name("caritranspreorder");
    
    Route::get('/transaksipreorder', 'TransaksiPreorder@index');
    Route::post('/resettrans', 'PreorderController@resettrans');
    Route::get('/prosesbayar/{id}/', "PreorderController@index")->name("prosesbayar");
    Route::get('/showdetail/{no_nota}', 'TransaksiPreorder@index')->name('showdetail');
    Route::get('/caritransaksi', 'TransaksiController@index')->name('caritrans');
    Route::post('/tambahpreorder2', 'KasirController@tambahpreorder');
    Route::post('/cetakpreorder', 'KasirController@cetakpreorder');
    Route::get('/hapusnotabesar/{no_nota}', 'TransaksiPreorder@hapusnotabesar')->name('hapusnotabesar');
    Route::get('preorderpage', 'RiwayatPre@index');
    Route::get('/caripreorder', 'RiwayatPre@index')->name('caripreorder');
    Route::get('/hapuspreorder/{id}', 'RiwayatPre@hapus')->name('hapuspre');
    
});


Route::middleware(["role:admin gudang|manager"])->group(function(){
    Route::post('/searchpro', 'DetailStokController@searcher');
    Route::post('/tambahstok', 'StokController@tambahstok');
    Route::post('/loadsinglestok', 'StokController@loadsinglestok');
    Route::post('/editstok', 'StokController@editstok');
    Route::post('/hapusstok', 'StokController@hapusstok');
    Route::post('/printstoktrack', 'DetailStokController@printstoktrack');
   
    Route::post('/tambahrs', 'DetailStokController@returnsuplier');
    Route::get('/detailstok', 'DetailStokController@index');
    Route::post('/loaddatadetailstok', 'DetailStokController@loaddatadetailstok');
    Route::post('/tambahdetailstok', 'DetailStokController@tambahdetailstok');
  
    Route::post('/loaddatastok', 'StokController@loaddatastok');
    Route::post('/printcurrentstok', 'StokController@printcurrent');
    Route::post('/printcurrentstokgudang', 'StokController@printcurrentgudang');
    Route::post('/filterdetailstok', 'DetailStokController@loaddatadetailstok');
   
});

Route::middleware(["role:manager"])->group(function(){
   
    Route::get('/detailstok/{kode}/{by}', 'DetailStokController@index')->name("dsc");
    Route::get('/produk', 'ProdukController@index')->name('produk');
    Route::post('/tambahbarang', 'ProdukController@tambahbarang');
    Route::post('/hapusbarang', 'ProdukController@hapusbarang');
    Route::post('/loadproduk', 'ProdukController@loadProduk');
    Route::post('/getprodukinfo', 'ProdukController@loadSingleProduk');
    Route::post('/updateproduk', 'ProdukController@updatebarang');
    Route::get('/hapusproduk/{kode}', 'ProdukController@hapusproduk')->name("hapusproduk");
    Route::get('/searchproduct', 'ProdukController@search')->name("searchproduct");
    Route::post('/tambahmerek', 'ProdukController@tambahmerek');
    Route::post('/savebuffer', 'ProdukController@savebuffer');
    Route::post('/getmerekinfo', 'ProdukController@getmerekinfo');
    Route::post('/ubahmerek', 'ProdukController@ubahmerek');
    Route::post('/hapusmerek', 'ProdukController@hapusmerek');
    Route::post('/editproduks/{id}', "ProdukController@updatebarang")->name("editproduk");
    Route::get('/editproduk', "ProdukController@showdetail");
    Route::post('/tambahkategori', 'ProdukController@tambahkategori');
    
    Route::get('/dsm', 'DSMController@index');
    Route::post('/loaddsm', 'DSMController@loaddatadetailstok');
    Route::post('/verifiying', 'DSMController@verifiying');
    Route::post('/rejecting', 'DSMController@rejecting');
    Route::post('/printbarcode', 'ProdukController@printbarcode');
    Route::get('/manajemen_akun', 'AkunController@index')->name('ma');
    Route::post('/updateakun/{id}', 'AkunController@updateakun')->name('updateakun');
    Route::get('/hapusakun/{id}', 'AkunController@hapusakun')->name('hapusakun');
    
    Route::get('/dashboard', "HomeController@index")->name('home');
    Route::get('/importexcel', function(){
        return view('exceltodb');
    });
    Route::post('/injectitem', 'SeederJoyEvo@inject');
    Route::get('/paket', 'PaketController@index')->name('paket');
    Route::post('/tambahpaket','PaketController@tambahpaket');
    Route::post('/ubahpaket','PaketController@ubahpaket');
    Route::get('/hapuspaket/{id}', 'PaketController@hapuspaket')->name('hapuspaket');
    Route::get('/editpaket/{id}', 'PaketController@editpaket')->name('editpaket');
    Route::post('/downloaddatauser', 'transaksiController@downloaduser');
    Route::post('/tambahakun', 'AkunController@tambahakun');
    Route::post('/emailmatch','AkunController@em');
 });

  Route::get('/stok', 'StokController@index')->name('stok');

Route::get('/viewbarcode', function(){
    return view('cetakbarcode');
});
