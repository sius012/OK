<!DOCTYPE html>
<html lang="en">
    @php $no=1 ;
    $subtotal=0;
    $potongan=0;
    $potonganRetur=0;
    $jml=0;
    $cashback = 0;
    @endphp
    
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
        
        @if($dts["jmltrans"] > 0)
        @for($j=0;$j<(int)$dts['jmltrans'];$j++)
            <tr @if($dts['datas']->status == 'return') style="background: yellow" @endif>
        @if($j==0)
                <td rowspan="{{$dts['jmltrans']}}">{{$no}}</td>
                <td rowspan="{{$dts['jmltrans']}}">{{date("d-M-Y",strtotime($dts['datas']->created_at))}}</td>
                <td rowspan="{{$dts['jmltrans']}}">{{$dts["datas"]->no_nota}}</td>
                     
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->nama_pelanggan}}</td>
                     @if($has==0)
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->telepon}}</td>
                     <td rowspan="{{$dts['jmltrans']}}">{{$dts['datas']->alamat}}</td>
                     @endif
        
    @php $no++; @endphp
       @endif
                <td>{{$dts[$j]->jumlah}}</td>
                <td>{{$dts[$j]->nama_kodetype." ".$dts[$j]->nama_merek." ".$dts[$j]->nama_produk." "}}</td>
                
                
           @if($has==0)     
           <td>{{Tools::doDisc(1,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix)}}</td>
           <td >{{Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix)}}</td>
           @if($j==0) 
            <td rowspan="{{$dts['jmltrans']}}">{{$dts["datas"]->metode}}</td>
            @endif
           @endif
            </tr>
        @php 
        $subtotal += Tools::doDisc($dts[$j]->jumlah,$dts[$j]->harga_produk,$dts[$j]->potongan,$dts[$j]->prefix);
        if($dts[$j]->status != "return"){$jml += $dts[$j]->jumlah;}else{
           
        }
        
        @endphp
        @endfor 
        @elseif($dts['datas']->status=='cashback')
        <tr style="background: lightblue">
        <td @if( $dts['jmltrans'] > 0 ) rowspan={{$dts['jmltrans']}} @endif>{{$no}}</td>
                <td @if( $dts['jmltrans'] > 0 ) rowspan={{$dts['jmltrans']}} @endif>{{date("d-M-Y",strtotime($dts['datas']->created_at))}}</td>
                <td @if( $dts['jmltrans'] > 0 ) rowspan={{$dts['jmltrans']}} @endif>{{$dts["datas"]->no_nota}}</td>
                     
                     <td @if( $dts['jmltrans'] > 0 ) rowspan={{$dts['jmltrans']}} @endif>{{$dts['datas']->nama_pelanggan}}</td>
                     @if($has==0)
                     <td @if( $dts['jmltrans'] > 0 ) rowspan={{$dts['jmltrans']}} @endif>{{$dts['datas']->telepon}}</td>
                     <td @if( $dts['jmltrans'] > 0 ) rowspan={{$dts['jmltrans']}} @endif>{{$dts['datas']->alamat}}</td>
                     @endif
                     <td  @if($has==0) colspan=5 @else colspan=2 @endif>{{$dts['datas']->subtotal}}(Cashback)</td>
        
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
                <td colspan=7>Total Cashback</td>
                <td colspan=4>{{$cashback}}</td>
               
            </tr>
            <tr>
                <td colspan=7>Total Akhir</td>
                <td colspan=4>{{$subtotal - $potongan - $potonganRetur - $cashback}}</td>
            </tr>

            

        @endif
    </table>
 
</body>
</html>