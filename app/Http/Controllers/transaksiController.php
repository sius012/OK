<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;
use App\Exports\PelangganExport;
use App\Exports\TransaksiExport;
use function GuzzleHttp\json_encode;
use App\Http\Controllers\Tools;
use PDF;

use Auth;

use Illuminate\Support\Facades\Storage;
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

    public function hapusdraft(){
        DB::table("transaksi")->where("status","draf")->delete();
        DB::table("detail_transaksi")->where("status","draft")->delete();
    }

    public function printtt(Request $req){
        $id = $req->id;
        $data = DB::table('transaksi')->where('kode_trans', $id)->get();
        $data2 = DB::table('detail_transaksi')->join("new_produks","new_produks.kode_produk","=","detail_transaksi.kode_produk")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->where('kode_trans', $id)->get();
        $pdf = PDF::loadview('tandaterima', ["data" => $data[0],"data2"=>$data2]);
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."PREORDER". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);
        return $base64;
    }

    public function index(Request $req){
        $data = [];
      
        
        $get = DB::table("transaksi")->where("status","!=","preorder")->where("status","!=","suratjalan");
            
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
    //dd($data);

        return view("transaksi", compact('data'));

        
    }

    public function tampilreturn(Request $req){
        $id=$req->id_trans;
        $no_nota =  DB::table("transaksi")->where("kode_trans",$id)->pluck("no_nota")->first();
        $hasretur = DB::table("transaksi")->where("keterangan",$no_nota)->where("status","!=","cashback")->count();
        $checker = DB::table("transaksi")->where("status","cashback")->where("keterangan",$no_nota)->count() > 0 ? 1 : 0;
        $id_cb = DB::table("transaksi")->where("status","cashback")->where("keterangan",$no_nota)->pluck("kode_trans")->first();
        $datatrans = DB::table('transaksi')->join('detail_transaksi','detail_transaksi.kode_trans','=','transaksi.kode_trans')->join('new_produks','detail_transaksi.kode_produk','=','new_produks.kode_produk')->join('mereks','mereks.id_merek','=','new_produks.id_merek')->join('kode_types','kode_types.id_kodetype','=','new_produks.id_ct')->where('transaksi.kode_trans', $id)->select("transaksi.*","transaksi.status as status_trans","detail_transaksi.*","new_produks.*","mereks.*","kode_types.*","transaksi.tlh_bayar")->get();
        $jml = DB::table('transaksi')->join('detail_transaksi','detail_transaksi.kode_trans','=','transaksi.kode_trans')->join('new_produks','detail_transaksi.kode_produk','=','new_produks.kode_produk')->join('mereks','mereks.id_merek','=','new_produks.id_merek')->join('kode_types','kode_types.id_kodetype','=','new_produks.id_ct')->where('transaksi.kode_trans', $id)->count();
        return json_encode(["datatrans"=>$datatrans,"hasretur"=>$hasretur, "jml"=>$jml, "hascb"=>$checker,"no_nota"=>$no_nota,"id_cb"=>$id_cb]);

        
    }

    public function cashback(Request $req){
        $id_kasir = Auth::user()->id;   
        $no_nota = $req->no_nota;
        $nominal_cb = $req->nominal;

        $data = DB::table("transaksi")->where("no_nota",$no_nota)->first();

        $id = DB::table("transaksi")->insertGetId(["no_nota"=>"CB".$no_nota,"nama_pelanggan"=>$data->nama_pelanggan,"telepon"=>$data->telepon,"alamat"=>$data->alamat,"id_kasir"=>$id_kasir,"subtotal"=>$nominal_cb,"status"=>"cashback","keterangan" =>$no_nota]);

        return json_encode(["id"=>$id]);

    }

    public function cetakcashbacknk(Request $req){
        $id = $req->id_trans;

        $data = DB::table("transaksi")->where("kode_trans",$id)->first();

        $pdf = PDF::loadview('cashbacknk', ["data" => $data]);
        
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'.$data->no_nota. '.' . 'pdf' . "cbnk" ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);
        return json_encode(["file"=>$base64]);
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


        return json_encode(["trans" => $trans, "detail" => $detail, 'cicilan'=>$cicilandata, ]);
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

    public function downloadtransaksi(Request $req){
        $tglstart = $req->md;
        $tglend = $req->sd;

        $has = 0;

        if($req->input('cua')){
            $has = 1;
        }

        $datas = DB::table("transaksi")->where("status","!=","draf")->where("status","!=","preorder")->where("status","!=","suratjalan")->whereBetween(DB::raw('substr(created_at,1,10)'),[$tglstart,$tglend]);
        $jmltrans =  $datas->count();
        $data = [];
        $excel = 0;
        if($req->input('excel')){
            $excel = 1;
        }

        foreach($datas->get() as $i => $dts){
          
            $quer = DB::table('transaksi')
                                ->rightJoin('detail_transaksi',"transaksi.kode_trans","=","detail_transaksi.kode_trans")
                                ->rightJoin('new_produks', 'detail_transaksi.kode_produk', '=', 'new_produks.kode_produk')
                                ->rightJoin('kode_types','kode_types.id_kodetype','new_produks.id_ct')
                                ->rightJoin('mereks','mereks.id_merek','new_produks.id_merek')
                                ->where('transaksi.kode_trans', $dts->kode_trans)->where("transaksi.status","!=","preorder")->where("transaksi.status","!=","suratjalan")
                                ->select("transaksi.*","mereks.*","kode_types.*","new_produks.*","detail_transaksi.*","transaksi.created_at as tanggal_trans;");
            $data[$i] = $quer->get()->toArray();
        
            $data[$i]['jmltrans'] = $quer->count();
            $data[$i]['datas'] = $dts;
            $data[$i]['potongan rupiah'] = 0;
            $data[$i]['potongan retur'] = $dts->tlh_bayar;
            $data[$i]["cashback"] = $dts->status == "cashback" ? $dts->subtotal  : 0;
            if($dts->prefix != "rupiah" and $dts->status != "cashback" and $dts->diskon > 0 and $dts->subtotal >0){
                $oriPrice = $dts->subtotal / ((100 - $dts->diskon)/100);
                $data[$i]['potongan rupiah'] = $oriPrice - $dts->subtotal;
                
            }else{
                $data[$i]['potongan rupiah'] = $dts->diskon;
            }
        }
       
        if($excel == 0){
            $pdf = PDF::loadview('laporan.transaksi', ["datas" => $data,"has"=>$has,'start'=>$tglstart,'end'=>$tglend]);
        $path = public_path('pdf/Laporan Nota Kecil');
            $fileName =  date('mdy').'-'."Laporan Nota Kecil". '.' . 'pdf' ;

        $storagepath = storage_path("pdf/$fileName");
        $pdf->setPaper("a4","potrait");
        return $pdf->download($fileName);
        }else{
             return Excel::download(new TransaksiExport($data,$has),"Laporan Nota Kecil.xls");
        }
        

    }
}
