<!DOCTYPE html>
<html lang="en">
    @php $no=1 ;
    $subtotal=0;
    $potongan=0;
    $potonganRetur=0;
    $jml=0; 
    $jmlretur=0;
    $cashback=0;
    @endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        table{
            border-collapse: collapse;
        }
        td,th{
            border: 1px solid black;
            font-size: 8pt;
            
        }

      
        

        th{
            background: grey;
            color: white;
        }
    </style>
</head>
<body>
<h4> Laporan Transaksi Nota Kecil</h4> 
<h6>
        ({{date('d-m-Y',strtotime($start))}} - {{date('d-m-Y',strtotime($end))}} )
    </h6>
    <table>
    <thead>
        <tr>
            <th>No</th>
            <th>tanggal</th>
            <th>No Nota</th>
            <th>Nama Pelanggan</th>
            @if($has==0)
                <th>No telp</th>
                <th>Alamat</th>
            @endif  
            
            <th>Jumlah</th>
            <th>Produk</th>
    @if($has==0)  
    <th>Harga Satuan(Setelah Disc)</th>
    
    <th>Total</th>
    <th>Pembayaran</th>
    @endif
    </tr>
    </thead>

    <tbody>
        @foreach($datas as $i => $dts)
        
        @if($dts["jmltrans"] > 0)
        @for($j=0;$j<(int)$dts['jmltrans'];$j++)
            <tr @if($dts['datas']->status == 'return') style="background: yellow" @endif>
       
                <td >{{$no}}</td>
                <td >{{date("d-M-Y",strtotime($dts['datas']->created_at))}}</td>
                <td >{{$dts["datas"]->no_nota}}</td>
                     
                     <td >{{$dts['datas']->nama_pelanggan}}</td>
                     @if($has==0)
                     <td >{{$dts['datas']->telepon}}</td>
                     <td >{{$dts['datas']->alamat}}</td>
                     @endif
        
    @php $no++; @endphp
      
                <td>{{$dts[$j]->jumlah}}</td>
                <td>{{$dts[$j]->nama_kodetype." ".$dts[$j]->nama_merek." ".$dts[$j]->nama_produk." "}}</td>
                
                
           @if($has==0)     
           <td>{{number_format(Tools::doDisc(1,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix),0,".",".")}}</td>
           <td >{{number_format(Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix),0,".",".")}}</td>
        
            <td style="background-color: {{$dts['datas']->metode == 'suratjalan' ? 'red' : 'white' }}" >{{$dts["datas"]->metode}}</td>
   
           @endif
            </tr>
        @php 
        $subtotal += Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix);
        if($dts[$j]->status != "return"){$jml += $dts[$j]->jumlah;}else{
            $jmlretur += $dts[$j]->jumlah;
        }
        
        @endphp
        @endfor 
        @elseif($dts['datas']->status=='cashback')
        <tr style="background: lightblue">
        <td >{{$no}}</td>
                <td >{{date("d-M-Y",strtotime($dts['datas']->created_at))}}</td>
                <td >{{$dts["datas"]->no_nota}}</td>
                     
                     <td >{{$dts['datas']->nama_pelanggan}}</td>
                 
                     <td >{{$dts['datas']->telepon}}</td>
                     <td >{{$dts['datas']->alamat}}</td>
                   
                     <td  @if($has==0) colspan=5 @else colspan=2 @endif>{{number_format($dts['datas']->subtotal,0,".",".")}}(Cashback)</td>
        
    @php $no++; @endphp
              
                
            
           
            </tr>

        @endif
        @php
             $potongan += $dts['potongan rupiah'];
             $potonganRetur += $dts['potongan retur'];
        @endphp

        @php
            $cashback += $dts["cashback"];
        @endphp
        @endforeach
        @if($has==0) 
            <tr>
                <th colspan=6>Total</td>
                <td >{{$jml}}</td>
                <td colspan=4>{{number_format($subtotal,0,".",".")}}</td>
            </tr>
            <tr>
                <th colspan=6>Total Retur</th>
                <td colspan=1>{{$jmlretur}}</td>
                <td colspan=4>{{number_format($potonganRetur,0,".",".")}}</td>
            </tr>
            <tr>
                <th colspan=7>Total Diskon</td>
                <td colspan=4>{{number_format($potongan,0,".",".")}}</td>
               
            </tr>
            <tr>
                <th colspan=7>Total Cashback(NK)</td>
                <td colspan=4>{{number_format($cashback,0,".",".")}}</td>
               
            </tr>
            <tr>
                <th colspan=7>Total Akhir</td>
                <td colspan=4>{{number_format($subtotal - $potongan - $potonganRetur - $cashback,0,".",".")}}</td>
            </tr>
        @endif
        
    </table>
 
</body>
</html>