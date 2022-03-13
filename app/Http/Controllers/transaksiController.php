<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelangganExport;
use function GuzzleHttp\json_encode;

class transaksiController extends Controller
{
    
    public function kembali(Request $req){
        $kode = $req->input('kode');

        $datos = [];
        foreach($kode as $kodes){
         $jml = DB::table('detail_transaksi')->where('kode_trans',$req->id_trans)->where('kode_produk',$kodes)->get()[0]->jumlah;
         $status = DB::table('detail_transaksi')->where('kode_trans',$req->id_trans)->where('kode_produk',$kodes)->pluck('status')->first();
      
         if($status != 'return'){
            $jmlstok =  DB::table('stok')->where('kode_produk',$kodes)->pluck('jumlah')->first();
            DB::table('detail_transaksi')->where('kode_trans',$req->id_trans)->where('kode_produk',$kodes)->update(['status'=>'return']);
            DB::table('stok')->where('kode_produk',$kodes)->update(['jumlah'=>$jmlstok + $jml]);
         }
        
        }
        
        return back();
        
    }
    public function index(Request $req){
        $data = [];
      
        
        $get = DB::table("transaksi");

        if($req->filled('no_nota')){
            $get->where('no_nota',$req->no_nota);
        }

        if($req->filled('status')){
            $get->where('status',$req->status);
        }

        if($req->filled('waktu')){
            if($req->waktu == 'terbaru'){
                $get->orderBy('created_at','desc');
            }else{
                $get->orderBy('created_at','asc');
            }
        }else{  
            $get->orderBy('created_at','desc');
        }
        
        


        

        foreach($get->get() as $d){
            $row = (array) $d;
          


        
            array_push($data, $row);
  
            
            
           
        }
    //  dd($data);

        return view("transaksi", compact('data'));

        
    }

    public function tampilreturn(Request $req){
        $id=$req->id_trans;
        $datatrans = DB::table('transaksi')->join('detail_transaksi','detail_transaksi.kode_trans','=','transaksi.kode_trans')->join('new_produks','detail_transaksi.kode_produk','=','new_produks.kode_produk')->join('mereks','mereks.id_merek','=','new_produks.id_merek')->where('transaksi.kode_trans', $id)->get();
     
        return json_encode($datatrans);

        
    }


    public function showDetail(Request $req){
        $id = $req->input("id");
        $cicilandata = [];
        $trans =  DB::table("detail_transaksi")->join('produk', 'produk.kode_produk', '=', 'detail_transaksi.kode_produk')->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')->where("kode_trans", $id)->get();
        $detail = DB::table("transaksi")->where("kode_trans", $id)->get();
        $cicilan = DB::table("cicilan")->where("kode_transaksi", $id)->get();
        foreach($cicilan as $cicilans){
            $row = [];
            array_push($row, (array) $cicilans);
            $idkasir = $cicilans->id_kasir;
            if($idkasir != null){
                $kasir = DB::table('users')->where('id', $idkasir)->pluck("name");
                array_push($row, $kasir[0]);
            }
            
            array_push($cicilandata, $row);
            
        }


        return json_encode(["trans" => $trans, "detail" => $detail, 'cicilan'=>$cicilandata]);
    }

    public function hapus($id){
        $checking = DB::table('transaksi')->where('kode_trans',$id)->pluck("status")->first();
        if($checking == "draf"){
            DB::table('transaksi')->where('kode_trans',$id)->delete();
            DB::table('detail_transaksi')->where('kode_trans',$id)->delete();
        }
    

        return back();
    }

    public function downloaduser(Request $req){
        $md = $req->md;
        $sd = $req->sd;
        $tlp = isset($req->telepon) ? $req->telepon : null;
        $almt = isset($req->alamat) ? $req->alamat : null;

        return Excel::download(new PelangganExport($md,$sd,$tlp,$almt),'Pelanggan.xlsx');
    }
}
