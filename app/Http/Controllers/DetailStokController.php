<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Tools;
use App\Http\Controllers\CetakNotaBesarController;

use Auth;
use PDF;
use Session;




class DetailStokController extends Controller
{
    public function loaddatadetailstok(Request $req){
        $datab = DB::table('detail_stok')->join('new_produks', 'new_produks.kode_produk','detail_stok.kode_produk')->join("kode_types","kode_types.id_kodetype","new_produks.id_ct")->join('mereks', 'new_produks.id_merek','mereks.id_merek')->join('users','users.id','=','detail_stok.id_ag')->select('detail_stok.*','new_produks.nama_produk','new_produks.satuan','users.name',"mereks.nama_merek","kode_types.nama_kodetype");
        $data = DB::table('detail_stok')->join('new_produks', 'new_produks.kode_produk','detail_stok.kode_produk')->join('mereks', 'new_produks.id_merek','mereks.id_merek')->join('users','users.id','=','detail_stok.id_ag')->select('detail_stok.*','new_produks.nama_produk','new_produks.satuan','users.name');


        $getsr = DB::table('retursup')->join("users","users.id","=","retursup.id_ag")->orderBy("tanggal","desc")->get();
        


        if($req->filled('md') and $req->filled('sd')){
            $date_start = Carbon::parse($req->md)->format('Y-m-d');
            $date_end = Carbon::parse($req->sd)->format('Y-m-d');
            $datab->whereBetween(DB::raw('substr(detail_stok.created_at,1,10)'), [$date_start,$date_end]);
            $data->whereBetween(DB::raw('substr(detail_stok.created_at,1,10)'), [$date_start,$date_end]);
          
        }

        if($req->filled('tipe')){
            $datab->where('status',$req->tipe);
            $data->where('status',$req->tipe);
        }

        if($req->filled('produk')){
            $datab->where('detail_stok.kode_produk',$req->produk);
            $data->where('detail_stok.kode_produk',$req->produk);
        }


        
   


        $get1 = [];

        foreach($getsr  as $i=>$gs){
            $get1[$i] = (array) $gs;
            $produkcount = 0;
            $listproduk = explode(",",substr($gs->produk,0,-1));
            $listjumlah = explode(",",substr($gs->jumlah,0,-1));
            
            foreach($listproduk as $j => $ls){
                $dato = DB::table("new_produks")->join("mereks","mereks.id_merek","new_produks.id_merek")->join("kode_types","kode_types.id_kodetype","new_produks.id_ct")->join("tipes","tipes.id_tipe","new_produks.id_tipe")->where("kode_produk",$ls)->first();
                $get1[$i]["produk".$j] =  $dato;

                $produkcount++;
                $get1[$i]["jumlah".$j] = $listjumlah[$j];
            }
            
            $get1[$i]["jumlahproduk"]=$produkcount;
            
        }


        return json_encode(["data1"=>$datab->orderBy('created_at','DESC')->get()
       ,"data2"=> $get1]);
    }
    public function index($kode=null,$by=null,$np=null,$hp=null,$dsc=null,$tp=null,$stn=null){
       
    
        $produk = DB::table('new_produks')->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("tipes","tipes.id_tipe","=","new_produks.id_tipe")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->get();

        
        $data = DB::table('detail_stok')->join('new_produks', 'new_produks.kode_produk','detail_stok.kode_produk')->join('mereks', 'new_produks.id_merek','mereks.id_merek')->join('users','users.id','=','detail_stok.id_ag')->select('detail_stok.*','new_produks.nama_produk','new_produks.satuan','users.name')->get();


        $getsr = DB::table('retursup')->get();

        
        
        $get1 = [];

        foreach($getsr  as $i=>$gs){
            $get1[$i] = (array) $gs;
            $produkcount = 0;
            $listproduk = explode(",",substr($gs->produk,0,-1));
            $listjumlah = explode(",",substr($gs->jumlah,0,-1));
            
            foreach($listproduk as $j => $ls){
                $dato = DB::table("new_produks")->join("mereks","mereks.id_merek","new_produks.id_merek")->where("kode_produk",$ls)->first();
                $get1[$i]["produk".$j] =  $dato;

                $produkcount++;
                $get1[$i]["jumlah".$j] = $listjumlah[$j];
            
            }
            $get1[$i]["jumlahproduk"]=$produkcount;
            
        }

      // dd(url()->previous());
      $tipe = DB::table('new_produks')->where('kode_produk',$kode)->pluck('id_tipe')->first();
      $kodetipe = DB::table('new_produks')->where('kode_produk',$kode)->pluck('id_ct')->first();
      $merek = DB::table('new_produks')->where('kode_produk',$kode)->pluck('id_merek')->first();
        if($kode!=null){
            $url;
            if($by == "bypm"){
                $url = url("/produk?tipe=".$tipe."&kodetipe=".$kodetipe."&merek=".$merek);
            }else{
                $url = url()->previous();
            }
          
           // dd($dsc);
            return view('detailstok', ['url' =>$url,'produk' => $produk,'detail_stok' => $data,'fastedit'=>'yea','kodeproduk'=>$kode,'tipe'=>$tipe,'kodetipe'=>$kodetipe,'merek'=>$merek,
            'np'=>$np,
            'hp'=>$hp,
            'dsc'=>$dsc,
            'tp'=>$tp,
            'by'=>$by,
            'stn'=>$stn,
        ]);

    
         
        }else{
            return view('detailstok', ['produk' => $produk,'detail_stok' => $data,]);
        }
        
        
    }

    public function tambahdetailstok(Request $req){
        $data = $req->input('data');
        $data2 = $data;
        $data['id_ag'] = Auth::user()->id;

        unset($data['by']);
        unset($data['np']);
        unset($data['hp']);
        unset($data['dsc']);
        unset($data['tp']);
        unset($data['satuan']);
       
        if($data['status'] == 'masuk'){
             DB::table('detail_stok')->insert($data);
            $jml = DB::table('stok')->where('kode_produk', $data['kode_produk'])->pluck('jumlah');
            $jml = $jml[0];
            DB::table('stok')->where('kode_produk', $data['kode_produk'])->update(['jumlah'=> (int) $jml + (int) $data['jumlah']]);
        }else if($data['status'] == 'keluar'){
            $jml = DB::table('stok')->where('kode_produk', $data['kode_produk'])->pluck('jumlah');
            $jml = $jml[0];
            if($jml-(int)$data['jumlah'] < 0){
                return response()->json(['error'=>'pengurangan mencapai angka minus!']);
            }else{
                DB::table('detail_stok')->insert($data);
                DB::table('stok')->where('kode_produk', $data['kode_produk'])->update(['jumlah'=> (int) $jml - (int) $data['jumlah']]);
            }
            
        }   


        if(isset($data2['by'])){
            if($data2['by'] =='bypm'){
                $get = $req->session()->get('buffer');
                DB::table('new_produks')->where('kode_produk',$get['kb'])->update(['satuan' => $get['stn'],'nama_produk'=>$get['np'],'harga'=>$get['hp'],'diskon'=>$get['dsc'],'diskon_tipe'=>$get['tpd']]);
            }
          
        }
    }

    public function searcher(Request $req){
        $kw = $req->kw;

        $data = DB::table('new_produks')->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("tipes","tipes.id_tipe","=","new_produks.id_tipe")->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")->where("kode_produk","LIKE","%".$kw."%")->get();;

        return json_encode($data);


        
    }

    public function printstoktrack(Request $req){
        $notabesar = [];

     

        

           //Pemanggilan Jin Nota besar
           $nb = new CetakNotaBesarController();
     
           $notabesar = $nb->getNotaBesar("day","dwm");

        $dato = DB::table("detail_stok")->join("new_produks","detail_stok.kode_produk","=","new_produks.kode_produk")->join("mereks","mereks.id_merek","=","new_produks.id_merek")
        ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
        ->join("users","users.id","=","detail_stok.id_ag")->where("status2","!=","retur")->where("status2","!=","transaksi");
        $dato_trans = DB::table("detail_transaksi")->join("new_produks","detail_transaksi.kode_produk","=","new_produks.kode_produk")->join("mereks","mereks.id_merek","=","new_produks.id_merek")
        ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
        ->where("status","!=","return")->orWhere('status','!=','draft');

        $keluar1 = DB::table("detail_stok")->join("new_produks","detail_stok.kode_produk","=","new_produks.kode_produk")
        ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
        ->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("users","users.id","=","detail_stok.id_ag")
        ->where("status","keluar")
        ->where("status2","!=","retur")->where("status2","!=","transaksi")
        ->where("keterangan","not LIKE","%transaksi%")
        ->select("*","detail_stok.created_at as tgl");
        $keluar1trans = DB::table("detail_transaksi")->join('transaksi','transaksi.kode_trans','=','detail_transaksi.kode_trans')->join("new_produks","detail_transaksi.kode_produk","=","new_produks.kode_produk")
        ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
        ->join("mereks","mereks.id_merek","=","new_produks.id_merek")->select('new_produks.nama_produk','new_produks.kode_produk', 'detail_transaksi.*','mereks.nama_merek',"kode_types.nama_kodetype","transaksi.subtotal","transaksi.diskon","transaksi.prefix as transfix","transaksi.nama_pelanggan")->where('transaksi.status','!=','draft')->where('transaksi.status','!=','return')->whereIn('transaksi.status',['lunas','belum lunas']);

        $masuk1 = DB::table("detail_stok")->join("new_produks","detail_stok.kode_produk","=","new_produks.kode_produk")
        ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
        ->where("status","masuk")->join("mereks","mereks.id_merek","=","new_produks.id_merek")->join("users","users.id","=","detail_stok.id_ag")->where("status2","!=","retur")->where("status2","!=","transaksi")
        ->select("*","detail_stok.created_at as tgl");
        ;
        $masuk1trans = DB::table("detail_transaksi")->join("transaksi","transaksi.kode_trans","=","detail_transaksi.kode_trans")->join("new_produks","detail_transaksi.kode_produk","=","new_produks.kode_produk")
   
        ->join("mereks","mereks.id_merek","=","new_produks.id_merek")
        ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
        ->where("detail_transaksi.status","return")->where("transaksi.status","=","return")->select('new_produks.nama_produk','new_produks.kode_produk', 'detail_transaksi.*','mereks.nama_merek','kode_types.nama_kodetype',"transaksi.prefix as transfix","transaksi.diskon");

        $cashbacknk = DB::table("transaksi")->where("status","cashback");

        $retur = [];

        if($req->filled('produk')){
            $dato_trans->where('new_produks.kode_produk',$req->produk);
            $keluar1->where('new_produks.kode_produk',$req->produk);
            $keluar1trans->where('new_produks.kode_produk',$req->produk);
            $masuk1trans->where('new_produks.kode_produk',$req->produk);
            $masuk1->where('new_produks.kode_produk',$req->produk);
        }
        
      

        $getsr = DB::table('retursup')->join("users","users.id","=","retursup.id_ag");
        
        
        if($req->berdasarkan == "tanggal"){
            $notabesar = $nb->getNotaBesar(["start"=>$req->tanggal,"end"=>$req->tanggalakhir],"range");
            $date_start = Carbon::parse($req->tanggal)->format('Y-m-d');
            $date_end = Carbon::parse($req->tanggalakhir)->format('Y-m-d');
            $dato->whereBetween(DB::raw('substr(detail_stok.created_at,1,10)'), [$date_start,$date_end]);
            $dato_trans->whereBetween(DB::raw('substr(detail_transaksi.created_at,1,10)'), [$date_start,$date_end]);

           $keluar1->whereBetween(DB::raw('substr(detail_stok.created_at,1,10)'), [$date_start,$date_end]);
            $keluar1trans->whereBetween(DB::raw('substr(detail_transaksi.created_at,1,10)'), [$date_start,$date_end]);

             $masuk1->whereBetween(DB::raw('substr(detail_stok.created_at,1,10)'), [$date_start,$date_end]);
            $masuk1trans->whereBetween(DB::raw('substr(detail_transaksi.created_at,1,10)'), [$date_start,$date_end]);

            $getsr->whereBetween(DB::raw('substr(tanggal,1,10)'), [$date_start,$date_end]);
            $cashbacknk->whereBetween(DB::raw('substr(created_at,1,10)'), [$date_start,$date_end]);
            
        }else if($req->berdasarkan == "hmb"){
            if($req->hmb == "harian"){
                $notabesar = $nb->getNotaBesar("day","dwm");
                $dato->where(DB::raw('substr(detail_stok.created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
                $dato_trans->where(DB::raw('substr(detail_transaksi.created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
    
                $keluar1->where(DB::raw('substr(detail_stok.created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
                $keluar1trans->where(DB::raw('substr(detail_transaksi.created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
    
                $masuk1->where(DB::raw('substr(detail_stok.created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
                $masuk1trans->where(DB::raw('substr(detail_transaksi.created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
    
                $getsr->where(DB::raw('substr(tanggal,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
                $cashbacknk->where(DB::raw('substr(created_at,1,10)'),Carbon::parse($req->tanggal)->format('Y-m-d'));
            }else if($req->hmb == "mingguan"){
                $notabesar = $nb->getNotaBesar("week","dwm");
                $dato->whereBetween('detail_stok,created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                );
                $dato_trans->whereBetween('detail_transaksi.created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                );
    
                $keluar1->whereBetween('detail_stok.created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                 );
                $keluar1trans->whereBetween('detail_transaksi.created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                );
    
                $masuk1->whereBetween('detail_stok.created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                );
                $masuk1trans->whereBetween('detail_transaksi.created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                );
    
                $getsr->whereBetween('tanggal', 
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
              );
              $cashbacknk->whereBetween('created_at', 
              [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
            );
       
                $notabesar = $nb->getNotaBesar("month","dwm");
                $dato->whereMonth('detail_stok.created_at', Carbon::now()->month);
                $dato_trans->whereMonth('detail_transaksi.created_at', Carbon::now()->month);
    
                $keluar1->whereMonth('detail_stok.created_at', Carbon::now()->month);
                $keluar1trans->whereMonth('detail_transaksi.created_at', Carbon::now()->month);
    
                $masuk1->whereMonth('detail_stok.created_at', Carbon::now()->month);
                $masuk1trans->whereMonth('detail_transaksi.created_at', Carbon::now()->month);
    
                $getsr->whereMonth('tanggal', Carbon::now()->month);
            }
        }
        

        foreach($getsr->get()  as $i=>$gs){
            $produkcount = 0;
            $listproduk = explode(",",substr($gs->produk,0,-1));
            $listjumlah = explode(",",substr($gs->jumlah,0,-1));
            
            foreach($listproduk as $j => $ls){
                $dato = DB::table("new_produks")->join("mereks","mereks.id_merek","new_produks.id_merek")
                ->join("kode_types","kode_types.id_kodetype","=","new_produks.id_ct")
                ->where("kode_produk",$ls)->first();
                array_push($retur,["tanggal"=>$gs->tanggal,"keterangan"=>$gs->keterangan,"Nama Admin"=>$gs->name,"kode_produk"=>$dato->kode_produk,"nama_produk"=>$dato->nama_produk,"nama_kodetype"=>$dato->nama_kodetype,"nama_merek"=>$dato->nama_merek,"jumlah"=>$listjumlah[$j],"tanggal"=>$gs->tanggal]);


               
            }
            
            
        }

        $myArr = [];

        $listpotongan = [];
        $listpotonganR = [];
        
        foreach($keluar1trans->get() as $ks){
            $listpotongan[$ks->kode_trans] = $ks->transfix == "rupiah" ? $ks->diskon : $ks->subtotal -  Tools::doDisc(1,$ks->subtotal,$ks->diskon,$ks->transfix);
        }

        foreach($masuk1trans->get() as $ks){
            $listpotonganR[$ks->kode_trans] = $ks->transfix == "rupiah" ? $ks->diskon : $ks->subtotal -  Tools::doDisc(1,$ks->subtotal,$ks->diskon,$ks->transfix);
        }

        if($req->keluar == "true"){
            $myArr["k1"] = $keluar1trans->get();
            $myArr["k1potongan"] = array_sum($listpotongan);
            $myArr["k2"] = $keluar1->get();
            
        }
        if($req->masuk == "true"){
            $myArr["m1"] = $masuk1trans->get();
            $myArr["m1potongan"] = array_sum($listpotonganR);
            $myArr["m2"] = $masuk1->get();
        }
        if($req->suplier == "true"){
            $myArr["suplier"] = $retur;
        }

        $myArr['tanggal'] = $req->berdasarkan == 'tanggal' ? date("d M Y",strtotime($req->tanggal))." - ".date("d M Y",strtotime($req->tanggalakhir)) : "Hari Minggu dan Bulan";
        $myArr['gudang'] = $req->gudang;
        $myArr['barangNB'] = $notabesar;
        $myArr['cashbacknk'] = $cashbacknk->sum("subtotal");
        
       
        $pdf = PDF::loadview('trackstokprint', $myArr);
        $path = public_path('pdf/');
        $fileName =  date('mdy').'-'."Stok Harian". '.' . 'pdf' ;
        $pdf->save(storage_path("pdf/$fileName"));
        $storagepath = storage_path("pdf/$fileName");
        $base64 = chunk_split(base64_encode(file_get_contents($storagepath)));
        unlink(storage_path("pdf/$fileName"));

    	return json_encode(["filename" => $base64]);
        
    }


    public function returnsuplier(Request $req){
        $errorthrow = [];
        $validthrow = [];
        $id_ag = Auth::user()->id;

        $tanggal = $req->tanggal;
        
        $listproduk = $req->kode;
        $listjumlah = $req->jumlah;

        $produk = "";
        $jumlah = "";
        
        foreach($listproduk as $i => $ls)
        {
          

            //pengurangan stok
            $getstok = DB::table('stok')->where('kode_produk',$ls)->pluck("jumlah")->first();
          
            if($getstok - (int) $listjumlah[$i] < 0){
                $errorthrow[]=$listproduk;
            }else{
                $produk .= $ls.",";
                $jumlah .= $listjumlah[$i].",";
                $validthrow[] = $ls;
                DB::table("stok")->where("kode_produk",$ls)->update(["jumlah"=>(int)$getstok-(int)$listjumlah[$i]]);
            }
           
        }
        $counter = DB::table("retursup")->where(DB::raw("substr('tanggal',1,10)"),date("Y-m-d"))->count();
        $inv = date("ymd")."4".str_pad($counter+1,3,0,STR_PAD_LEFT);



        if(count($validthrow) > 0){
            DB::table("retursup")->insert(["tanggal"=>$tanggal,"invoice"=>$inv,"produk"=>$produk,"jumlah"=>$jumlah,"keterangan"=>$req->keterangan,"id_ag"=>$id_ag]);
        }
        

        if(count($errorthrow)>0){
            Session::flash('peringatan',$errorthrow);
            return back();
        }else{
            return back();
        }
       
        
    } 












}
