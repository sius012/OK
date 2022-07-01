<!DOCTYPE html>
<html lang="en">
    @php $no=1 ;
    $subtotal=0;
    $potongan=0;
    $potonganRetur=0;
    $jml=0; @endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <table>
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
        @foreach($datas as $i => $dts)
        
        @for($j=0;$j<(int)$dts['jmltrans'];$j++)
            <tr>
        @if($j==0)
                <td rowspan="{{$dts['jmltrans']}}">{{$no}}</td>
                <td rowspan="{{$dts['jmltrans']}}">{{date("d-M-Y",strtotime($dts['datas']->created_at))}}</td>
                <td rowspan="{{$dts['jmltrans']}}">{{$dts["datas"]->no_nota}}</td>
                     
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->nama_pelanggan}}</td>
                     @if($has==0)
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->telepon}}</td>
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->alamat}}</td>
                     @endif
       @endif
                <td>{{$dts[$j]->jumlah}}</td>
                <td>{{$dts[$j]->nama_kodetype." ".$dts[$j]->nama_merek." ".$dts[$j]->nama_produk." "}}</td>
                
                
           @if($has==0)     
           <td>{{Tools::doDisc(1,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix)}}</td>
           <td>{{Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix)}}</td>
           @if($j==0) 
            <td rowspan="{{$dts['jmltrans']}}">{{$dts["datas"]->metode}}</td>
            @endif
           @endif
            </tr>
        @php $no++;
        $subtotal += Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix);
        $jml += $dts[$j]->jumlah;
        
        @endphp
        @endfor 
        @php
             $potongan += $dts['potongan rupiah'];
             $potonganRetur += $dts['potongan retur'];
        @endphp
        @endforeach
        @if($has==0) 
            <tr>
                <td colspan=6>Total</td>
                <td >{{$jml}}</td>
                <td colspan=4>{{$subtotal}}</td>
            </tr>
            <tr>
                <td colspan=7>Total Retur</td>
                <td colspan=4>{{$potonganRetur}}</td>
            </tr>
            <tr>
                <td colspan=7>Total Diskon</td>
                <td colspan=4>{{$potongan}}</td>
               
            </tr>
            <tr>
                <td colspan=7>Total Akhir</td>
                <td colspan=4>{{$subtotal - $potongan - $potonganRetur}}</td>
            </tr>
        @endif
    </table>
 
</body>
</html>