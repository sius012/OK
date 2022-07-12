<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_encode;

use PDF;




class ProdukController extends Controller
{

    public function loadSingleProduk(Request $req){
        $produk = DB::table('produk')->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')->join('merek', 'produk.merk', '=','merek.nomer')->where('kode_produk', $req->input('kode_produk'))->first();
        return json_encode($produk);
    }

    public function getmerekinfo(Request $req){
        $datamerek = DB::table('merek')->where('nomer', $req->input('nomer'))->get();
        return json_encode($datamerek);
    }

    public function loadProduk(){
        $produk = DB::table('produk')->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')->get();
        return json_encode($produk);
    }
    public function index(Request $req){
        $kategori = DB::table('tipes')->orderBy("nama_tipe","asc")->get();
        $kodetype = DB::table('kode_types')->orderBy("nama_kodetype","asc")->get();
        $merek = DB::table('mereks')->orderBy("nama_merek","asc")->get();
        $satuan = DB::table('new_produks')->groupBy('satuan')->get();
        $produk = DB::table('new_produks')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->join('stok','stok.kode_produk','=','new_produks.kode_produk');

        if($req->filled("tipe")){
            $produk->where('new_produks.id_tipe',$req->tipe);
        }
        if($req->filled("kodetipe")){
            $produk->where('new_produks.id_ct',$req->kodetipe);
        }
        if($req->filled("merek")){
            $produk->where('new_produks.id_merek',$req->merek);
        }
        if($req->filled("nama")){
            $produk->where('new_produks.nama_produk',"LIKE","%".$req->nama."%")->orWhere('new_produks.kode_produk',"LIKE","%".$req->nama."%");
        }

        return view("produk", ["tipe" => $kategori, "produk" => $produk->orderBy('new_produks.updated_at','desc')->get(), "merek" => $merek, "kodetype" => $kodetype,
            'satuan' => $satuan,
            'tipekw' =>$req->tipe,
            'ctkw' =>$req->kodetipe,
            'merekkw' =>$req->merek,


    ]);
    }

    public function search(Request $req){
        $kategori = DB::table('produk')->groupBy("id_kategori")->get();
        $kodetype = DB::table('produk')->groupBy("id_ct")->get();
        $merek = DB::table('produk')->groupBy("merk")->get();
       
      
        $kw = $req->kw;
        $type = $req->tp;
        $ct = $req->ct;
        $merk = $req->merk;

     
            $produk = DB::table('produk')->where("kode_produk", "LIKE","%".$kw."%")->orWhere("id_kategori", "LIKE","%".$kw."%")->orWhere("id_ct","LIKE","%".$kw."%")->orWhere("merk","LIKE","%".$kw."%")->get();
            return view("produk", ["kat" => $kategori, "produk" => $produk, "merek" => $merek, "kodetype" => $kodetype, "keyword" => $kw, "tipe" => $type, "id_ct" => $ct,"mereknya" => $merk]);
        
    
        
        
    }

    public function tambahbarang(Request $req){
        $nama_produk = $req->input('nama_produk');
        $id_merek = $req->input('merek_produk');
        $id_tipe = $req->input('tipe_produk');
        $harga_produk = $req->input('harga_produk');
        $satuan_produk = $req->input('satuan_produk');
        $id_codetype = $req->input('kodetype');
        $diskon = $req->input('diskon');
        $td = $req->input('discontype');

        if(strpos($id_tipe,'[custom]')){
            $id_tipeo = DB::table("tipes")->max("id_tipe");
            
            $id_tipe = DB::table("tipes")->insertGetId(["id_tipe" => $id_tipeo+1, "nama_tipe"=>str_replace("[custom]","",$id_tipe)]);
         
            
        }
        if(strpos($id_merek,'[custom]')){
            $id_mereko = DB::table("mereks")->max("id_merek");
            $id_merek = DB::table("mereks")->insertGetId(["id_merek" => $id_mereko+1 ,"nama_merek"=>str_replace("[custom]","",$id_merek)]);
    
        }
        if(strpos($id_codetype,'[custom]')){
            $id_codetypeo = DB::table("kode_types")->max("id_kodetype");
            $id_codetype = DB::table("kode_types")->insertGetId(["id_kodetype" => $id_codetypeo+1, "nama_kodetype"=>str_replace("[custom]","",$id_codetype)]);
      
            
        }



        $count = DB::table("new_produks")->where("id_tipe", $id_tipe)->where("id_ct", $id_codetype)->where("id_merek",$id_merek)->count();


        $kode_produk = str_pad($id_tipe,2,0,STR_PAD_LEFT).$id_tipe.str_pad($id_codetype,3,0,STR_PAD_LEFT).str_pad($id_merek,3,0,STR_PAD_LEFT).str_pad($count+1,3,0,STR_PAD_LEFT);
        DB::table('detail_stok')->insert(['id_ag'=>auth()->user()->id,'kode_produk'=>$kode_produk,'jumlah'=>$req->stok,'status'=>'masuk','keterangan'=>'stock awal','created_at'=>date('Y-m-d H:i:s')]);

        
         DB::table('stok')->insert(['kode_produk'=> $kode_produk,'jumlah'=>$req->stok]);
        
         DB::table('new_produks')->insert(["id_tipe" => $id_tipe,"kode_produk"=>$kode_produk,"id_ct"=>$id_codetype,"nama_produk"=>$nama_produk,"id_merek" => $id_merek, "harga" => $harga_produk, 'satuan' => $satuan_produk,"diskon"=>$diskon,'diskon_tipe'=>$td]);

         $getProduk = DB::table('produk')->join('kategori', 'kategori.id_kategori', '=', 'produk.id_kategori')->get();
         return json_encode(["produk" => $getProduk,'kp'=>$kode_produk,'kode'=>$kode_produk,"merek"=>$id_merek,"tipekode"=>$id_codetype,"tipe"=>$id_tipe,"kode"=>$kode_produk,'nama_produk'=>$nama_produk]);
    }

    public function hapusproduk($kode){
       $dato =  DB::table('new_produks')->where('kode_produk', $kode)->first();

        $jmltipe = DB::table("new_produks")->where("id_tipe", $dato->id_tipe)->count();
        $jmlkodetipe = DB::table("new_produks")->where("id_ct", $dato->id_ct)->count();
        $jmlmerek = DB::table("new_produks")->where("id_merek", $dato->id_merek)->count();

        if($jmltipe < 2){
            DB::table("tipes")->where("id_tipe", $dato->id_tipe);
        }
        if($jmlkodetipe < 2){
            DB::table("kode_types")->where("id_kodetype", $dato->id_kodetype);
        }
        if($jmlmerek < 2){
            DB::table("mereks")->where("id_merek", $dato->id_merek);
        }

        DB::table('new_produks')->where('kode_produk', $kode)->delete();
        return redirect()->route('produk');
        
    }

    public function updatebarang(Request $req,$id){
        
        $data = $req->input();

        $data2 = $data;

        unset($data["_token"]);
         unset($data["id_tipe"]);
          unset($data["id_ct"]);
           unset($data["id_merek"]);
        $namaproduk = $req->nama_produk;
        $data["harga"] = (int) str_replace([",","."],"",$req->harga);
        $id_tipe = $req->id_type;
        $id_ct = $req->id_ct;
        $id_merek= $req->id_merek;

        

   
        DB::table('new_produks')->where('kode_produk',$id)->
                            update($data);
                           return redirect('/produk?nama=&tipe='.$data2['id_tipe'].'&kodetipe='.$data2['id_ct'].'&merek='.$data2['id_merek']);
       
        }

    public function tambahkategori(Request $req){
        $data = $req->input('kat');

        DB::table('kategori')->insert($data);
    }

    public function tambahmerek(Request $req){
        $data = $req->input('data');
        DB::table('merek')->insert($data);

        $getmerek =  DB::table('merek')->get();
        return json_encode(['merek' => $getmerek]);
    }

    public function ubahmerek(Request $req){
        $data = $req->input('data');
        DB::table('merek')->where('nomer', $data['nomer'])->update(['jumlah' => $data['jumlah']]);
        $getmerek =  DB::table('merek')->get();
        return json_encode(['merek' => $getmerek]);
    }
    public function hapusmerek(Request $req){
        $data = $req->input('nomer');
        DB::table('merek')->where('nomer', $data)->delete();
        $getmerek =  DB::table('merek')->get();
        return json_encode(['merek' => $getmerek]);
    }

    public function showdetail(Request $req){
        $kategori = DB::table('tipes')->get();
        $kodetype = DB::table('kode_types')->get();
        $merek = DB::table('mereks')->get();
        $satuan = DB::table('new_produks')->groupBy('satuan')->get();
        $produk = DB::table('new_produks')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->join("stok","stok.kode_produk","new_produks.kode_produk")->get();
        
        $kode = $req->input('kodebarcode');
        $data = DB::table("new_produks")->where('new_produks.kode_produk',$kode)->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->join("stok","stok.kode_produk","new_produks.kode_produk")->first();
       // dd($data);
        return view("produk", ["tipe" => $kategori, "produk" => $produk, "merek" => $merek, "kodetype" => $kodetype, "data"=>$data,'satuan'=>$satuan]);

        
    }

    public function update($id){
        dd($id);
    }

    public function printbarcode(Request $req){
        $listdata= [];

        $getter = DB::table('new_produks')->where('kode_produk', $req->kode_produk)->join("kode_types","kode_types.id_kodetype","new_produks.id_ct")->join("mereks","mereks.id_merek","new_produks.id_merek")->get()[0];
        
        for($i = 0;$i< $req->jml;$i++){
            array_push($listdata,$getter);
        }

        $pdf = PDF::loadview('cetakbarcode', ["data" => $listdata]);
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."cetakbarcode". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $thepath = storage_path("pdf/$fileName");
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($thepath);
    	return response()->json(["filename" => $base64]); 
        
    }


    public function printbarcodepage(Request $req){
        $produk = DB::table('new_produks')->join('mereks','new_produks.id_merek','mereks.id_merek')->join('tipes','new_produks.id_tipe','tipes.id_tipe')->join('kode_types','new_produks.id_ct','kode_types.id_kodetype')->join('stok','stok.kode_produk','=','new_produks.kode_produk');

        if($req->filled("tipe")){
            $produk->where('new_produks.id_tipe',$req->tipe);
        }
        if($req->filled("kodetipe")){
            $produk->where('new_produks.id_ct',$req->kodetipe);
        }
        if($req->filled("merek")){
            $produk->where('new_produks.id_merek',$req->merek);
        }
        if($req->filled("nama")){
            $produk->where('new_produks.nama_produk',"LIKE","%".$req->nama."%")->orWhere('new_produks.kode_produk',"LIKE","%".$req->nama."%");
        }
        $listdata= [];

        
        foreach($produk->get() as $produks){
            for($i=0;$i<$produks->jumlah;$i++){
                array_push($listdata,$produks);
            }
        }

        if($listdata>300){
            return json_encode(["limitation"=>1,"jml"=>count($listdata)]); 
        }

        $pdf = PDF::loadview('cetakbarcode', ["data" => $listdata]);
        $path = public_path('pdf/');
            $fileName =  date('mdy').'-'."cetakbarcode". '.' . 'pdf' ;
            $pdf->save(storage_path("pdf/$fileName"));
        $thepath = storage_path("pdf/$fileName");
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink($thepath);
    	return json_encode(["filename"=>$base64,"jml"=>count($listdata)]); 
        
    }    








    public function savebuffer(Request $req){
        $datas = 
        [
        "kb" => $req->kb,
        "np"  => $req->np,
        "hp"  => $req->hp,
        "dsc"  => $req->dsc,
        "tpd"  => $req->tpd,
        "stn"  => $req->stn,
        ];

        $req->session()->put('buffer',$datas);
        return json_encode($datas);
    }


}
