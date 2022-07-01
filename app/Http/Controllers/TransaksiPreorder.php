<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use Carbon\Carbon;

class TransaksiPreorder extends Controller
{
    public function index(Request $req,$no_nota=null){
        $data = [];
        
        $get = DB::table("nota_besar")->groupBy("no_nota")->orderBy("created_at",'desc')->get()->toArray();
        if($req->has("no_nota")){
            if($req->no_nota != ""){
                $get = DB::table("nota_besar")->where("no_nota", $req->no_nota)->groupBy("no_nota")->get()->toArray();
            }
           
        }

        foreach($get as $d){
            $row = (array) $d;
          
                 $opsi = DB::table("nota_besar")->where("no_nota", $d->no_nota)->select("us", "brp", "total", "updated_at", "status","id_transaksi")->where('termin', ">", $d->termin)->get()->toArray();
                 $jatuhtempo = Carbon::createFromFormat("Y.m.d", date("Y.m.d",strtotime($d->created_at)));
                 $row["min3jatuhtempo"] = $jatuhtempo->addDays(17)->format("d M Y");
                 array_push($row, (array) $opsi);
                 array_push($data, $row);
            
        }

        //dd($data);
        if($no_nota != null){
            $data_detail = DB::table('nota_besar')->where('no_nota',$no_nota)->get();
            $counter = DB::table('nota_besar')->where('no_nota',$no_nota)->count();
            $opsi = null;
            if($counter < 2){
              
            }else{
                $opsi = DB::table('nb_detail')->where('id_nb', $data_detail[0]->id_transaksi)->get();
            }


            return view("transaksipreorder", ['data'=>$data,'page'=>'kasir', 'info' => $data_detail,'opsi' => $opsi]);
        }else{
          return view("transaksipreorder", ['data'=>$data,'page'=>'kasir']);
        }
        
        
       
     
        
    }

    public function showDetail(Request $req){
        $id = $req->input("id");

        $trans =  DB::table("detail_transaksi")->join('produk', 'produk.kode_produk', '=', 'detail_transaksi.kode_produk')->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')->where("kode_trans", $id)->get();
        $detail = DB::table("transaksi")->where("kode_trans", $id)->get();

        return json_encode(["trans" => $trans, "detail" => $detail]);
    }

    public function hapusnotabesar($no_nota){
        $id = DB::table('nota_besar')->where('no_nota', $no_nota)->get();

        foreach($id as $ids){
            DB::table('nb_detail')->where('id_nb',$ids->id_transaksi)->delete();
        }
        DB::table('nota_besar')->where('no_nota', $no_nota)->delete();

        return back();

    }
}
