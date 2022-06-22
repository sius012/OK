<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class MenuSuratJalan extends Controller
{
    public function index(Request $req){
        $data = [];
      
        
        $get = DB::table("transaksi")->where("status","=","suratjalan");
            
        if($req->filled('no_nota')){
            $get->where('no_nota',$req->no_nota)->orWhere('nama_pelanggan',"LIKE","%".$req->no_nota."%");
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

        return view("menusuratjalan", compact('data'));

        
    





 
    }

    public function hapussj(Request $req){
        $id = $req->id_trans;
        DB::table("transaksi")->where("kode_trans", $id)->delete();
        DB::table("detail_transaksi")->where("kode_trans", $id)->delete();
    }

    public function cetaksj(Request $req){
        $id = $req->id_pre;
        $data = DB::table('transaksi')->where('kode_trans', $id)->get();
        $data2 = DB::table('detail_transaksi')->join("new_produks","new_produks.kode_produk","=","detail_transaksi.kode_produk")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->where('kode_trans', $id)->get();
        $pdf = PDF::loadview('suratjalan2', ["data" => $data[0],"data2"=>$data2]);
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."MB". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);
        return $base64;
    }
}



