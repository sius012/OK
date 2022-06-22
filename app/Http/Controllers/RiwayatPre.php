<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPre extends Controller
{
    public function index(Request $req){
        $data = DB::table('transaksi')->where("status","preorder")  ;
        if($req->has('nama')){
           $data->where('nama_pelanggan','LIKE','%'.$req->nama."%")->orWhere('no_nota','LIKE','%'.$req->nama."%");
        }
        

        return view("preorderPage",['data'=>$data->orderBy("created_at","desc")->get()]);
    }

    public function hapus($id){
        DB::table('preorder')->where('id', $id)->delete();
        return back();
    }

    public function showdetail(Request $req){
        $id=$req->id;

        $data = DB::table("transaksi")->join("detail_transaksi","detail_transaksi.kode_trans","transaksi.kode_trans")->join("new_produks","new_produks.kode_produk","=","detail_transaksi.kode_produk")
        ->join("mereks","mereks.id_merek","=","new_produks.id_merek")->select("transaksi.*","detail_transaksi.*","new_produks.nama_produk","mereks.nama_merek")->where('transaksi.kode_trans',$id)->get();

        return json_encode($data);
    }
}
