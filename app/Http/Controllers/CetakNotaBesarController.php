<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class CetakNotaBesarController extends Controller
{
    public function index(Request $req){
        $tglstart = $req->md;
        $tglend = $req->sd;
        // $notabesar = [];

        // $datanb = DB::table("nota_besar")->groupBy("no_nota")->where("no_nota","not LIKE","%JP%")
        // ->select("ttd","telepon","no_nota","total","up","gm","id_transaksi",'created_at','us')->whereBetween(DB::raw('substr(created_at,1,10)'),[$tglstart,$tglend])
        // ->get()->toArray();

        

        // foreach($datanb as $i => $dnb ){
        //     $notabesar[$i]["maindata"] = $dnb;
        //     $termin = DB::table("nota_besar")->where("status","!=","cashback")->where("no_nota",$dnb->no_nota)->select("termin","us","brp","created_at","updated_at","status")->get()->toArray();
        //     foreach($termin as $j => $termins){
        //         $notabesar[$i]["termins"][$j] = $termins;
        //     }
        //     $opsi = DB::table("nb_detail")->where("id_nb",$dnb->id_transaksi)->get();
        //     foreach($opsi as $k => $opsis ){
        //     $notabesar[$i]["opsi"][$k]= $opsis;
        //     }
        // }

        $nb = $this->getNotaBesar(["start"=>$tglstart,"end"=>$tglend],"range");
   
        $pdf = PDF::loadview('laporan.notabesar', ["notabesar" => $nb,'start'=>$tglstart,'end'=>$tglend]);
        $path = public_path('pdf/Laporan Nota Besar');
            $fileName =  date('mdy').'-'."Laporan Nota Besar". '.' . 'pdf' ;

        $storagepath = storage_path("pdf/$fileName");
        $pdf->setPaper("a4","potrait");
        return $pdf->download($fileName);
        
    }

    public function getNotaBesar($arrdate, $rangeordwm){
        

        $notabesar = [];

        $datanb = DB::table("nota_besar")->groupBy("no_nota")
        ->select("ttd","telepon","no_nota","total","up","gm","id_transaksi",'created_at','us');
        if($rangeordwm == "range"){
            $tglstart = $arrdate["start"];
            $tglend = $arrdate["end"];
            $datanb->whereBetween(DB::raw('substr(created_at,1,10)'),[$tglstart,$tglend]);
        }else{
            switch ($arrdate) {
                case 'day':
                   $datanb->whereRaw('Date(created_at) = CURDATE()');
                    break;

                 case 'week':
                    $datanb->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;

                 case 'month':
                    $datanb->whereMonth("created_at",Carbon::now()->month);
                    break;
                
                default:
                    # code...
                    break;
            }
          
        }

       

        foreach($datanb->get()->toArray() as $i => $dnb ){
            $notabesar[$i]["maindata"] = $dnb;
            $termin = DB::table("nota_besar")->where("status","!=","cashback")->where("no_nota",$dnb->no_nota)->select("termin","us","brp","created_at","updated_at","status")->get()->toArray();
            foreach($termin as $j => $termins){
                $notabesar[$i]["termins"][$j] = $termins;
            }
            $opsi = DB::table("nb_detail")->where("id_nb",$dnb->id_transaksi)->get();
            foreach($opsi as $k => $opsis ){
            $notabesar[$i]["opsi"][$k]= $opsis;
            }
        }

        return $notabesar;
    }
}
