<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class CodeFixer extends Controller
{
    public function index(){
        $dataProduk = DB::table("new_produks")->get();
        $datakode = [];

       
        foreach($dataProduk as $i => $datas){
            $datakode[$i]["kode lama"] = $datas->kode_produk;

            //get new code
            $tipe = $datas->id_tipe;
            $kodetipe = $datas->id_ct;
            $merek = $datas->id_merek;
            $no_urut = substr($datas->kode_produk, -3);

            $datakode[$i]["urutan"] = $no_urut;

            $datakode[$i]["kode baru"] = str_pad($tipe,2,0,STR_PAD_LEFT).str_pad($kodetipe,2,0,STR_PAD_LEFT).str_pad($merek,3,0,STR_PAD_LEFT).str_pad($no_urut,3,0,STR_PAD_LEFT);



        }


        return view("codefixer", ["datakode"=>$datakode]);
    }

    public function updater(){
        $dataProduk = DB::table("new_produks")->get();
        $datakode = [];

       
        foreach($dataProduk as $i => $datas){
            $datakode[$i]["kode lama"] = $datas->kode_produk;

            //get new code
            $tipe = $datas->id_tipe;
            $kodetipe = $datas->id_ct;
            $merek = $datas->id_merek;
            $no_urut = substr($datas->kode_produk, -3);

            $datakode[$i]["urutan"] = $no_urut;

            $datakode[$i]["kode baru"] = str_pad($tipe,2,0,STR_PAD_LEFT).str_pad($kodetipe,2,0,STR_PAD_LEFT).str_pad($merek,3,0,STR_PAD_LEFT).str_pad($no_urut,3,0,STR_PAD_LEFT);

            //updater
            DB::table("new_produks")->where("kode_produk", $datakode[$i]["kode lama"])->update(["kode_produk"=> str_pad($tipe,2,0,STR_PAD_LEFT).str_pad($kodetipe,3,0,STR_PAD_LEFT).str_pad($merek,3,0,STR_PAD_LEFT).str_pad($no_urut,3,0,STR_PAD_LEFT)]);
            DB::table("detail_transaksi")->where("kode_produk", $datakode[$i]["kode lama"])->update(["kode_produk"=> str_pad($tipe,2,0,STR_PAD_LEFT).str_pad($kodetipe,3,0,STR_PAD_LEFT).str_pad($merek,3,0,STR_PAD_LEFT).str_pad($no_urut,3,0,STR_PAD_LEFT)]);
            DB::table("stok")->where("kode_produk", $datakode[$i]["kode lama"])->update(["kode_produk"=>str_pad($tipe,2,0,STR_PAD_LEFT).str_pad($kodetipe,3,0,STR_PAD_LEFT).str_pad($merek,3,0,STR_PAD_LEFT).str_pad($no_urut,3,0,STR_PAD_LEFT)]);
            DB::table("detail_stok")->where("kode_produk",  $datakode[$i]["kode lama"])->update(["kode_produk"=> str_pad($tipe,2,0,STR_PAD_LEFT).str_pad($kodetipe,3,0,STR_PAD_LEFT).str_pad($merek,3,0,STR_PAD_LEFT).str_pad($no_urut,3,0,STR_PAD_LEFT)]);

            
        }


    }

    public function fixds(Request $req){
        DB::table('detail_stok')->where("keterangan","Transaksi Nota Kecil")->orWhere("Keterangan","LIKE","%Retur Transaksi%")->delete(); 
        $id_kasir = Auth::user()->id;
        $get =  DB::table('detail_transaksi')->get();

        foreach($get as $datas){
            $status = $datas->status;
            $stat2 = "keluar";
            $ket =  "Transaksi Nota Kecil";

            if($datas->jumlah > 0){

            if($status == "terjual"){
                
               
            }else if($status == "return"){
                $getnota = DB::table("transaksi")->where("kode_trans",$datas->kode_trans)->pluck("no_nota")->first();
                $ket = "Retur Transaksi ".$getnota;
                $stat2 = "masuk";
            }
            DB::table("detail_stok")->insert(["kode_produk"=>$datas->kode_produk,"jumlah"=>$datas->jumlah,"status"=>$stat2,"keterangan"=>$ket,"created_at"=>$datas->created_at,"id_ag"=>$id_kasir]);
        }

        return redirect()->back();            
          
        }

    
        
    }
}
