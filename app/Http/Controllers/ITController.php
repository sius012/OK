<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Tools;

class ITController extends Controller
{
    public function index(){
        //MENGAMBIL DATA TRANSAKSI NOTA KECIL
        $datatrans = DB::table("transaksi")->join("detail_transaksi","detail_transaksi.kode_trans","transaksi.kode_trans")->where("transaksi.status","!=","draf")->where("transaksi.status","lunas")->select("transaksi.*","detail_transaksi.*","transaksi.prefix as prefix_trans","detail_transaksi.prefix as prefix_item")->get();
        return view("itdash",["datatrans"=>$datatrans]);
    }

    public function normalizetrans(){
        $onlytrans = DB::table("transaksi")->where("transaksi.status","!=","draf")->where("status","lunas")->get();
        

        foreach($onlytrans as $datos){
            $subtotal = 0;
            $datad = DB::table("detail_transaksi")->where("kode_trans",$datos->kode_trans)->get();
            foreach($datad as $datads){
                $subtotal += Tools::doDisc($datads->jumlah,$datads->harga_produk,$datads->potongan,$datads->prefix);
            }

            $normalized = Tools::doDisc(1,$subtotal,$datos->diskon,$datos->prefix);

            echo $datos->kode_trans." ".$normalized."<br>";
            DB::table("transaksi")->where("kode_trans",$datos->kode_trans)->update(["subtotal"=>$normalized]);
        }


    }
}
