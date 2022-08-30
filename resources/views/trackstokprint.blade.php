@php 
    $no = 1;
    $nominal = 0;
    $cashback = 0;
   


    $totalR=0;
            $jumlahR=0;
@endphp
@php
        $date = \Carbon\Carbon::parse(date('d-M-Y'))->locale('id');

        $date->settings(['formatFunction' => 'translatedFormat']);

    @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
   
        * {
         
            margin: 0px;
        }

        body{
            margin: 1cm;
        }

         .logo-img {
           width: 120px;
        }

        .container .header {
            align-items: center;
    

            margin-top: 35px;
        }

        .container .address {
  
            font-size: 8pt;
            margin-bottom: 40px;
            width: 
        }

        .container .data-wrapper {
   

        }
        .container .data-wrapper .table tbody tr td, th{
            padding: 2px;
        }

        .container .data-wrapper .table.{
            border-collapse: collapse;
        
        }

        .table-data{
        width: 1px;
        border-collapse: collapse;
        
        }

        .table-data td, .table-data th{
            border: 1px solid black;
            font-size: 8pt;
            margin: 0;
            text-align: left;
            padding: 4px;
        }

        .barcode{
       
            font-size: 8pt !important;
        }

    

        td{
            padding: 2px;
        }

        p{
            margin: 0;
            font-size: 8pt;

        }
        h3{
            font-size: 10pt;
        }

        th, td{
           font-size: 8pt; 
        }
    </style>
</head>

<body>
  
    
            <table style="width: 190mm; margin-left:0px">
                <tr>
                    <th align="center"> <img class="logo-img" src="{{ public_path('assets/logo.svg') }}" alt=""></th>
                </tr>
                <tr>
                    <th align="center"><p class="address">
                            Jl. Agus Salim D no.10 <br> Telp/Fax.  085712423453 / (024) 3554929  Semarang <br>
                        </p></th>
                </tr>
            </table>
  
            <br>
            <br>
            <h3>Stok Harian Produk</h3>
        <p style="margin-bottom: 5px">Tanggal : {{$tanggal}}</p>

     
        @isset($m2)
           <h6>Barang Masuk dari Supplier</h6>
            <table class="table-data" style="width:180mm !important; margin-top: 5px; margin: 5px;margin-bottom: 80px" >
            <tr>
                        <th style="text-align: center">No</th>
                        <th style="width: 60px">Tanggal</th>
                        <th style="width:60px">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th style="width: 60px; text-align: center">A. Gudang</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($m2 as $da)
                    <tr>
                       <td style="text-align: center">{{$no}}</td>
                       <td style="text-align: center">{{date("d-M-Y",strtotime($da->tgl))}}</td>
                        <td style="text-align: center">{{$da->kode_produk}}</td>
                        <td >{{$da->nama_kodetype." ".$da->nama_merek." ".$da->nama_produk}}</td>
                        <td style="text-align: center">{{$da->jumlah}}</td>
                        <td >{{$da->keterangan}}</td>
                        <td style="text-align: center">{{$da->name}}</td>
                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset

        @isset($m1)
        <h6>Barang Retur</h6>
            <table class="table-data" style="width:180mm !important; margin-top: 5px; margin: 5px; margin-bottom: 80px" >
            <tr>
                        <th style=" text-align: center">No</th>
                        <th style="width:60px; text-align: center">Tanggal</th>
                        <th style="width: 80px; text-align: center">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th style="text-align: center; width: 40px">Jumlah</th>
                        @if($gudang =='false')  <th style="text-align: center; width: 40px">Harga</th> @endif
                    </tr>
    
            @php 
            
          
            
            $no = 
            1; @endphp
         
              
              
                    @foreach($m1 as $da)
                    <tr>
                       <td style="text-align: center">{{$no}}</td>
                       <td style="text-align: center">{{date("d-M-Y",strtotime($da->created_at))}}</td>
                        <td style="text-align: center">{{$da->kode_produk}}</td>
                        <td >{{$da->nama_kodetype." ".$da->nama_merek." ".$da->nama_produk}}</td>
                        <td style="text-align: center">{{$da->jumlah}}</td>
                        @if($gudang =='false')<td style="text-align: center">{{number_format(Tools::doDisc($da->jumlah,$da->harga_produk,$da->potongan,$da->prefix),0,",",".")}}</td>@endif

                    </tr>
                    @php $no++;
                    
                    
                    
                    $totalR += Tools::doDisc($da->jumlah,$da->harga_produk,$da->potongan,$da->prefix);
                    $jumlahR += $da->jumlah;
                    @endphp
              

                    @endforeach
                    <tr>
                    @if($gudang=="false") <td colspan="4">Total</td> @else <td colspan="4">Total</td>@endif
                    @if($gudang=="false")
                    <td>{{$jumlahR}}</td>
                    <td> {{number_format($totalR,0,",",".")}}</td>@endif
                
                 

                    </tr>
                    @if($gudang=="false")
                    @isset($m1potongan)
                    <tr>
                        <td colspan=4>Potongan</td>
                        <td></td>
                        <td> {{number_format($m1potongan,0,".",".")}}</td>
                        
                  
                    </tr>
                    <tr>
                        <td colspan=4>Total Retur</td>
                        <td></td>
                        <td> {{number_format($totalR - $m1potongan,0,".",".")}}</td>
                     
                    </tr>
                    @endisset
                    @endif
                
            </table>
        @endisset


        @isset($k2)
            <h6>Barang Revisi</h6>
            <table class="table-data" style="width:180mm !important; margin-top: 5px; margin: 5px; margin-bottom: 80px" >
            <tr>
            <th style="text-align: center">No</th>
                        <th style="width:80px; text-align: center">Kode Produk</th>
                        <th style="text-align: center">Tanggal</th>
                        <th>Nama Produk</th>
                        <th style="text-align: center">Jumlah</th>
                        <th>Keterangan</th>
                        <th style="text-align: center">A. Gudang</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($k2 as $da)
                    <tr>
                       <td style="text-align: center">{{$no}}</td>
                       <td style="text-align: center">{{$da->kode_produk}}</td>
                        <td style="text-align: center">{{date("d-M-Y",strtotime($da->tgl))}}</td>
                        <td >{{$da->nama_kodetype." ".$da->nama_merek." ".$da->nama_produk}}</td>
              
                        <td style="text-align: center">{{$da->jumlah}}</td>
                        <td >{{$da->keterangan}}</td>
                        <td style="text-align: center">{{$da->name}}</td>
                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset

        
        @isset($k1)
            <h6>Barang Terjual</h6>
            <table class="table-data" style="width:180mm !important; margin-top: 10px; margin: 5px;margin-bottom: 80px" >
            <tr>
                        <th style="text-align: center">No</th>
                        <th style="text-align: center; width: 70px">Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th style="width:60px; text-align: center">Kode Produk</th>
                        <th>Nama Produk</th>
                      
                       @if($gudang =='false') <th style="text-align: center; width: 80px">Harga</th> @endif
                      
                        <th>Jumlah</th>
                    </tr>
    
            @php  
            $jumlah=0;
            
            $total=0;
            
            $no = 1@endphp
         

              
                    @foreach($k1 as $da)
                    <tr>
                       <td style="text-align: center">{{$no}}</td>
                       <td style="text-align: center">{{date("d-M-Y",strtotime($da->created_at))}}</td>
                       <td>{{$da->nama_pelanggan}}</td>
                       <td style="text-align: center">{{$da->kode_produk}}</td>
                        <td >{{$da->nama_kodetype." ".$da->nama_merek." ".$da->nama_produk}}</td>
                        @if($gudang=="false") <td style="text-align: right">{{number_format(Tools::doDisc($da->jumlah,$da->harga_produk,$da->potongan,$da->prefix),0,",",".")}}</td>@endif
                       
                        <td style="text-align: center">{{$da->jumlah}}</td>

                    </tr>
                    @php
                    $total += Tools::doDisc($da->jumlah,$da->harga_produk,$da->potongan,$da->prefix);
                    $jumlah += $da->jumlah;
                    
                    
                    $no++ @endphp
                    
                    @endforeach
                    <tr>
                    @if($gudang=="false") <td colspan="5">Total</td> @else <td colspan="5">Total</td>@endif
                    @if($gudang=="false")<td> {{number_format($total,0,",",".")}}</td>@endif
                        <td>{{$jumlah}}</td>
                        

                    </tr>
                    @if($gudang=="false")
                    @isset($k1potongan)
                    <tr>
                        <td colspan=5>Potongan</td>
                        <td>{{number_format($k1potongan,0,".",".")}}</td>
                        <td></td>
              
                    </tr>
                    <tr>
                        <td colspan=5>Total Cashback</td>
                        <td> {{number_format($cashbacknk,0,".",".")}}</td>
                        <td></td>
                     
                        
                    </tr>
                    <tr>
                        <td colspan=5>Total Retur</td>
                        
                        <td>{{number_format($totalR - $m1potongan,0,".",".")}}</td>
                        <td></td>
                     
                     
                    </tr>
                    <tr>
                        <td colspan=5>Total Pemasukan</td>
                        <td>{{number_format($total - $k1potongan - $cashbacknk - ($totalR - $m1potongan) ,0,".",".")}}</td>
                        <td></td>
                   
                    </tr>
                    @endisset
                    @endif
                
            </table>
        @endisset

        
        @isset($suplier)
            <h6>Barang Retur ke Supplier</h6>
            <table class="table-data" style="width:180mm !important; margin-top: 5px; margin: 5px;margin-bottom: 80px" >
            <tr>
                        <th style="text-align: center">No</th>
                        <th style="text-align: center">Kode Produk</th>
                        <th style="text-align: center">Tanggal</th>
                        <th>Nama Produk</th>
                        <th style="text-align: center">Jumlah</th>
                        <th>Keterangan</th>
                        <th style="text-align: center">Admin Gudang</th>
                    </tr>
    
            @php  $no = 1@endphp
         
              
              
                    @foreach($suplier as $da)
                    <tr>
                       <td style="text-align: center">{{$no}}</td>
                        <td style="text-align: center">{{$da["kode_produk"]}}</td>
                        <td style="text-align: center">{{date("d-M-Y",strtotime($da['tanggal']))}}</td>
                        <td >{{$da['nama_kodetype']." ".$da['nama_merek']." ".$da['nama_produk']}}</td>
                        <td style="text-align: center">{{$da["jumlah"]}}</td>
                        <td>{{$da["keterangan"]}}</td>
                        <td style="text-align: center">{{$da["Nama Admin"]}}</td>
                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
        @endisset
        <br>
        <br>
        <br>
        <h6>Barang Nota Besar</h6>
        <table class="table-data" style="width:180mm !important; margin-top: 5px; margin: 5px;margin-bottom: 80px" >
        <thead>
            <tr>
                
                <th style="text-align: center">No. nota</th>
                <th style="width: 100px; text-align: center">Nama, No Telepon</th>
                <th>Proyek</th>
                <th style="width: 100px">Guna Membayar</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Termin</th>
                <th style="text-align: center; width: 60px;">Tanggal</th>     
               
                <th  style="">Uang Sejumlah</th>
                
                
            </tr>
        </thead>
        <tbody>
        @foreach($barangNB as $i => $nb)
        @if(isset($nb["termins"]))
        @foreach($nb["termins"] as $j => $termins)
        <tr>
      
               @if($j == 0) <td style="text-align: center" @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif >{{$nb["maindata"]->no_nota}}</td> @endif
               @if($j == 0) <td style="text-align: center" @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{$nb["maindata"]->ttd}} @if($nb["maindata"]->telepon != null)({{$nb["maindata"]->telepon}})@endif</td> @endif
               @if($j == 0) <td style="text-align: center" @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{$nb["maindata"]->up}}</td> @endif
               @if($j == 0) <td style="text-align: center" @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{$nb["maindata"]->gm}}</td> @endif
               @if($j == 0) <td style="text-align: right" @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{number_format($nb["maindata"]->total,0,".",".")}}</td> @endif
                <td style="text-align: center">{{$termins->termin}}</td>
                <td style="text-align: center">@if($termins->status == "dibayar"){{date("d-m-Y",strtotime($termins->updated_at))}}@else {{"-"}}@endif</td>
                
                <td>@if($termins->us != null){{number_format($termins->us,0,".",".")}}<br>({{$termins->brp}}) @else {{"Belum dibayar"}} @endif</td>
                
               
            </tr>
        @php
            $nominal += $termins->us;
        @endphp
        @endforeach
        @else
        <tr>
      
      <td >{{$nb["maindata"]->no_nota}}</td> 
     <td >{{$nb["maindata"]->ttd}}<br>@if($nb["maindata"]->telepon != null)({{$nb["maindata"]->telepon}})@endif</td> 
      <td >{{$nb["maindata"]->up}}</td> 
      <td >{{$nb["maindata"]->gm}}(Cashback)</td> 
        <td>-</td>
        <td>-</td>
        <td>-</td>
      <td>{{number_format($nb["maindata"]->us,0,".",".")}}</td> 
      
      @php
            $cashback += $nb["maindata"]->us;
        @endphp
      
   </tr>

        @endif
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan=4>
                    Nominal Uang Masuk
                </td>
                <td colspan=4>{{number_format($nominal,0,".",".")}}</td>
            </tr>
            <tr>
            <td colspan=4>Nominal Cashback</td>
            <td colspan=4>{{number_format($cashback,0,".",".")}}</td>
            </tr>
            <tr>
                <td colspan=4>Nominal Akhir</td>
                <td colspan=4>{{number_format($nominal-$cashback,0,".",".")}}</td>
            </tr>
        </tfoot>
    </table>
 
 
</body>

</html>