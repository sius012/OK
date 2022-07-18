<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;
use PDF;



class StokController extends Controller
{
    public function index(Request $req){
        $tiper = "";
        $ctr = "";
        $merekr = "";

        $kategori = DB::table('tipes')->orderBy('nama_tipe','asc')->get();
        $kodetype = DB::table('kode_types')->orderBy('nama_kodetype','asc')->get();
        $merek = DB::table('mereks')->orderBy('nama_merek','asc')->get();
        $produk = DB::table('stok')->join('new_produks','new_produks.kode_produk','=','stok.kode_produk')
        ->join('mereks','new_produks.id_merek','mereks.id_merek')
        ->join('tipes','new_produks.id_tipe','tipes.id_tipe')
        ->join('kode_types','new_produks.id_ct','kode_types.id_kodetype');
        if($req->filled("tipe")){
            $produk->where('new_produks.id_tipe',$req->tipe);
            $tiper = $req->tipe;
        }
        if($req->filled("kodetipe")){
            $produk->where('new_produks.id_ct',$req->kodetipe);
                $ctr = $req->kodetipe;
        }
        if($req->filled("merek")){
            $produk->where('new_produks.id_merek',$req->merek);
                $merekr = $req->merek;
        }
        if($req->filled("jumlahstok")){
            if($req->jumlahstok == "ps"){
                $produk->orderBy("jumlah","asc");
            }else if($req->jumlahstok == "pb"){
                $produk->orderBy("jumlah","desc");
            }else{
                $produk->where("jumlah","<=",0)->orderBy("jumlah","asc");
            }
        }

        return view("stok", [
            "tiper" => $tiper,
            "ctr" => $ctr,
            "merekr" => $merekr,
            "tipe" => $kategori,"produk"=>$produk->get(), "data" => $produk->get(), "merek" => $merek, "kodetype" => $kodetype]);
    }


    public function loadsinglestok(Request $req){
        $id = $req->input('id');
        $data = DB::table('stok')->where('id',$id)->get();
        return json_encode($data);
    }

    public function tambahstok(Request $req){
        $can = true;
        $data = $req->input('data');
        $counter = DB::table('stok')->where('kode_produk',$data['kode_produk'])->count();
        if($counter > 0){
            $can = false;
        }else{
            DB::table('stok')->insert($data);
        }
        return json_encode(['can' => $can]);
        
    }

    public function editstok(Request $req){
        $data = $req->input('data');
        DB::table('stok')->where('kode_produk', $data['kode_produk'])->update($data);
    }

    public function hapusstok(Request $req){
        $data = $req->input('kode_stok');
        DB::table('stok')->where('id', $data)->delete();
    }


    public function printcurrent(Request $req){
        $kategori = DB::table('tipes')->get();
        $kodetype = DB::table('kode_types')->get();
        $merek = DB::table('mereks')->get();
        $produk = DB::table('stok')->join('new_produks','new_produks.kode_produk','=','stok.kode_produk')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->groupBy("new_produks.id_tipe");
        if($req->filled("id_tipe")){
            $produk->where('new_produks.id_tipe',$req->id_tipe);
        }
        if($req->filled("id_kodetype")){
            $produk->where('new_produks.id_ct',$req->id_kodetype);
        }
        if($req->filled("id_merek")){
            $produk->where('new_produks.id_merek',$req->id_merek);
        }


        $req2 = $produk->get();

        $arraying = [];

        foreach($req2 as $i => $produks){

            $dato = DB::table('stok')->join('new_produks','new_produks.kode_produk','=','stok.kode_produk')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->where("new_produks.id_tipe",$produks->id_tipe)->get();
            $arraying[$i] = $dato;
            
        }
        


        
        $pdf = PDF::loadview('stokprint', ["data" => $arraying]);
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."Data Stok". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);
    	return response()->json(["filename" => $base64]);
    }

    public function loaddatastok($aksi=null){
        $jmlall = DB::table('new_produks')->count();
        $jmlstok = DB::table('new_produks')->join('stok','stok.kode_produk','=','new_produks.kode_produk')->count();

        $stoknessarray = [];
        $stoknessproduk = DB::table('new_produks')->join('stok','stok.kode_produk','=','new_produks.kode_produk')->get();

        foreach($stoknessproduk as $sp){
            array_push($stoknessarray, $sp->kode_produk);
        }


        $stoklessproduk =  DB::table('new_produks')->whereNotIn('kode_produk',$stoknessarray)->get();

       return $aksi != null ? $stoklessproduk : json_encode(['jumlah produk' => $jmlall,'tersedia dikatalog'=> $jmlstok,'stokless' => $stoklessproduk]);
    }

    public function updateallstok(){
        $data = $this->loaddatastok('ambilsaja');
        $jumlah = 0;
        foreach($data as $datas){
            DB::table('stok')->insert(['kode_produk'=>$datas->kode_produk,'jumlah'=>0]);
            $jumlah += 1;
        }

        return json_encode(['jumlah' => $jumlah]);
    }


    public function printcurrentgudang(Request $req){
        $kategori = DB::table('tipes')->get();
        $kodetype = DB::table('kode_types')->get();
        $merek = DB::table('mereks')->get();
        $produk = DB::table('stok')->join('new_produks','new_produks.kode_produk','=','stok.kode_produk')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->groupBy("new_produks.id_tipe");
        if($req->filled("id_tipe")){
            $produk->where('new_produks.id_tipe',$req->id_tipe);
        }
        if($req->filled("id_kodetype")){
            $produk->where('new_produks.id_ct',$req->id_kodetype);
        }
        if($req->filled("id_merek")){
            $produk->where('new_produks.id_merek',$req->id_merek);
        }
        if($req->filled("nama")){
            $produk->where('new_produks.nama_produk',"LIKE","%".$req->nama."%")->orWhere("new_produks.kode_produk","LIKE","%".$req->nama."%");
        }


        $req2 = $produk->get();

        $arraying = [];

        foreach($req2 as $i => $produks){

            $dato = DB::table('stok')->join('new_produks','new_produks.kode_produk','=','stok.kode_produk')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->where("new_produks.id_tipe",$produks->id_tipe)->get();
            $arraying[$i] = $dato;
            
        }
        


        
        $pdf = PDF::loadview('stokadmin', ["data" => $arraying]);
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."Data Stok". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($storagepath);
    	return response()->json(["filename" => $base64]);
    }
}
