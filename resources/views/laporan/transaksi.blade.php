<!DOCTYPE html>
<html lang="en">
    @php $no=1 ;
    $subtotal=0;
    $potongan=0;
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
            <th>Nama Pelanggan</th>
            <th>Produk</th>
            <th>jumlah</th>
    @if($has==0)   <th>total</th>@endif
        </tr>
        @foreach($datas as $i => $dts)
        @for($j=0;$j<(int)$dts['jmltrans'];$j++)
            <tr>
                <td>{{$no}}</td>
       @if($j==0)  
                     <td rowspan="{{$dts['jmltrans']}}">{{date("d-M-Y",strtotime($dts['datas']->created_at))}}</td>
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->nama_pelanggan}}</td>
       @endif
                <td>{{$dts[$j]->nama_kodetype." ".$dts[$j]->nama_merek." ".$dts[$j]->nama_produk." "}}</td>
                <td>{{$dts[$j]->jumlah}}</td>
           @if($has==0)     <td>{{Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix)}}</td>@endif
            </tr>
        @php $no++;
        $subtotal += Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix);
        $jml += $dts[$j]->jumlah;
        @endphp
        @endfor 
        @php
             $potongan += $dts['potongan'];
        @endphp
        @endforeach
        @if($has==0) 
            <tr>
                <td colspan=4>Total</td>
                <td >{{$jml}}</td>
                <td>{{$subtotal}}</td>
            </tr>
            <tr>
                <td colspan=4>Total Diskon</td>
                <td colspan=2>{{$potongan}}</td>
            </tr>
            <tr>
                <td colspan=4>Total Akhir</td>
                <td colspan=2>{{$subtotal - $potongan}}</td>
            </tr>
        @endif
    </table>
 
</body>
</html>