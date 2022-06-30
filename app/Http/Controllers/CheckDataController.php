<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckDataController extends Controller
{
    public function index(){
        $data = DB::table('nota_besar')->where("termin",3)->get();
  

        $data2 = [];

        //cek jumlah surat jalan
        $counter_sj = DB::table("transaksi")->where("status","suratjalan")->count();

        foreach($data as $datas){
            $now = Carbon::createFromFormat("m/d/Y", date("m/d/Y"));
            $min3 = Carbon::createFromFormat("m/d/Y", date("m/d/Y",strtotime($datas->jatuh_tempo)))->addDays(-3);
            if($now->gte($min3) and $datas->status == "menunggu" or $datas->status == "ready"){
                array_push($data2, $datas);
            }
        }   

        return json_encode(["data2" => $data2,"counter_sj"=>$counter_sj]);
    }

    public function experiment(){
        $data = DB::table('nota_besar')->where("termin",3)->get();

        $data2 = [];


        foreach($data as $datas){
            $now = Carbon::createFromFormat("m/d/Y", date("m/d/Y"));
            $min3 = Carbon::createFromFormat("m/d/Y", date("m/d/Y",strtotime($datas->jatuh_tempo)))->addDays(-3);
            if($now->gte($min3) and $datas->status == "menunggu" or $datas->status == "ready"){
                array_push($data2, $datas);
            }
        }

  










        $date1 = Carbon::createFromFormat('m/d/Y H:i:s', '12/01/2020 10:20:00');
        $date2 = Carbon::createFromFormat('m/d/Y H:i:s', '12/01/2020 10:20:00');
  
        $result = $date1->eq($date2);
      

        dd($data2);
        
    }
}
