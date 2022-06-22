<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\Tools;

use Auth;

class ReturController extends Controller
{
    //function baru untuk return
    public function retur(Request $req){

        //mengambil data dari javascript tadi
        $arriddtrans = $req->iddtrans;
        $arrjmlreturn = $req->jmlreturn;


        //mengambil data transaksi lengkap berdasarkan id detail transaksi
        $idtrans = DB::table("detail_transaksi")->where("id", $arriddtrans[0])->get()[0]->kode_trans;
        $btrans = DB::table('transaksi')->where('kode_trans',$idtrans)->select("no_nota","nama_pelanggan","telepon","alamat","diskon","prefix")->get()[0];



        
      
        //perbarui subtotal pembayaran dan mengembalikan diskon
         $query2 =  DB::table("transaksi")->where("kode_trans",$idtrans)->get()[0];
         $subtotals = $query2->subtotal;
         $nominal_produk  = 0;



        //ngecek jumlah jenis produk yg diretur;
        $counter = count($arriddtrans);



       
        if($counter > 0){
           

            //menambahkan transaksi yg berjenis return berdasarkan transaksi diatas
            $id_kasir = Auth::user()->id;  
            $counter = DB::table('transaksi')->where("status","return")->whereDate('created_at', Carbon::today())->count();
            $no_nota = "R".date("ymd").str_pad($counter+2, 3, '0', STR_PAD_LEFT);

            $id = DB::table('transaksi')->insertGetId(['no_nota'=>$no_nota, 'status'=>'return','nama_pelanggan'=>$btrans->nama_pelanggan,'telepon'=>$btrans->telepon,"alamat"=>$btrans->alamat, 'id_kasir' =>$id_kasir,'keterangan'=>$btrans->no_nota]);
            

            $pengurangansubtotal = 0;
            // tambah transaksi detail, mengupdate transaksi yg sudah ada dan memperbarui stok;
            foreach($arriddtrans as $index => $iddtrans){
                //menambah_nominal_produk
                $jumlahget = DB::table("detail_transaksi")->where("id", $iddtrans)->first();
                $nominal_produk += Tools::doDisc($jumlahget->jumlah,$jumlahget->harga_produk,$jumlahget->potongan,$jumlahget->prefix);
                
                //tambah transaksi detail baru
                 $getdata = DB::table('detail_transaksi')->where('id', $iddtrans)->first();
                 DB::table('detail_transaksi')->insert(['kode_trans'=>$id, "kode_produk" => $getdata->kode_produk,"jumlah"=>$arrjmlreturn[$index],"potongan"=>$getdata->potongan,"harga_produk"=>$getdata->harga_produk,"prefix"=>$getdata->prefix,"status"=>"return"]);

                //mengupdate yg sudah ada
                 


                 //memperbarui stok
                 if($getdata->status != "return"){
                    DB::table('stok')->where('kode_produk',$getdata->kode_produk)->update(['jumlah'=> DB::raw("jumlah + ".$arrjmlreturn[$index])]);
                 }
            }

            //perbarui subtotal pembayaran
           
           
           

        }

        
        
    }


    public function kembali(Request $req){
        $id_kasir = Auth::user()->id;   
        $kode = $req->input('kode');
        $idtrans  = $req->id_trans;
        $btrans = DB::table('transaksi')->where('kode_trans',$idtrans)->select("no_nota","nama_pelanggan","telepon","alamat")->get()[0];


        $datos = [];



        $counter = DB::table('transaksi')->whereDate('created_at', Carbon::today())->count();
        $no_nota = ['no_nota' => date("ymd").str_pad($counter+2, 3, '0', STR_PAD_LEFT)];

        if(count($kode) > 0){
            $id = DB::table('transaksi')->insertGetId(['status'=>'return','nama_pelanggan'=>$btrans->nama_pelanggan,'telepon'=>$btrans->telepon,"alamat"=>$btrans->alamat, 'id_kasir' =>$id_kasir,'keterangan'=>$btrans->no_nota]);
            foreach($kode as $kodes){
             $jml = DB::table('detail_transaksi')->where('kode_trans',$idtrans)->where('kode_produk',$kodes)->get()[0]->jumlah;
            
             $status = DB::table('detail_transaksi')->where('kode_trans',$idtrans)->where('kode_produk',$kodes)->pluck('status')->first();
          
             if($status != 'return'){
                $jmlstok =  DB::table('stok')->where('kode_produk',$kodes)->pluck('jumlah')->first();
                DB::table('detail_transaksi')->where('kode_trans',$req->id_trans)->where('kode_produk',$kodes)->update(['status'=>'return']);
                DB::table('stok')->where('kode_produk',$kodes)->update(['jumlah'=>$jmlstok + $jml]);
             }
    
             $getdata = DB::table('detail_transaksi')->where('kode_trans',$idtrans)->where('kode_produk',$kodes)->get()[0];
             
    
    
             DB::table('detail_transaksi')->insert(['kode_trans'=>$id, "kode_produk" => $kodes,"jumlah"=>$arrjmlreturn[$index],"potongan"=>$getdata->potongan,"harga_produk"=>$getdata->harga_produk,"prefix"=>$getdata->prefix,"status"=>"return"]);
            
            }
        }
       
        
        return back();
        
    }
}
