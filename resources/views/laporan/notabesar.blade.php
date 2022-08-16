@php 
    $nominal = 0;
    $cashback = 0;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            font-family: arial;
            
            line-height: 1;
        }
        table{
            border-collapse: collapse;
            font-size: 8pt;
        }
        th,td{
            border: 1px solid black;
   
        }

        p{
            margin: 0px;
        }

        br{
            display: block;
            margin-bottom: 0em;
        }
    </style>
</head>
<body>
    <h3>Laporan Nota Besar</h3>

    <table style="width:100px">
        <thead>
            <tr>
                
                <th>No. nota</th>
                <th>Nama, No Telepon</th>
                <th style="width:75px">Proyek</th>
                <th>Guna Membayar</th>
                <th style="width:200px">Spesifikasi</th>
                <th>Total</th>
                <th>Termin</th>
                <th>Tanggal</th>     
               
                <th  style="width:100px">Uang Sejumlah</th>
                
                
            </tr>
        </thead>
        <tbody>
        @foreach($notabesar as $i => $nb)
        @if(isset($nb["termins"]))
        @foreach($nb["termins"] as $j => $termins)
        <tr>
      
               @if($j == 0) <td @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif >{{$nb["maindata"]->no_nota}}</td> @endif
               @if($j == 0) <td @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{$nb["maindata"]->ttd}}<br>@if($nb["maindata"]->telepon != null)({{$nb["maindata"]->telepon}})@endif</td> @endif
               @if($j == 0) <td @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{$nb["maindata"]->up}}</td> @endif
               @if($j == 0) <td @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{$nb["maindata"]->gm}}</td> @endif
               @if($j == 0) <td @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>
                    @if(isset($nb["opsi"]))
                    @foreach($nb["opsi"] as $k => $opsis)
                        <p><b>{{$opsis->judul}}</b> : {{$opsis->ket}} </p><br>
                    @endforeach
                    @endif

               </td> @endif
               @if($j == 0) <td @if(isset($nb['termins']))  rowspan="{{ count($nb['termins'])}}" @endif>{{number_format($nb["maindata"]->total,0,".",".")}}</td> @endif
                <td>{{$termins->termin}}</td>
                <td>@if($termins->status == "dibayar"){{date("d-m-Y",strtotime($termins->updated_at))}}@else {{"-"}}@endif</td>
                
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
                <td colspan=5>{{number_format($nominal,0,".",".")}}</td>
            </tr>
            <tr>
            <td colspan=4>Nominal Cashback</td>
            <td colspan=5>{{number_format($cashback,0,".",".")}}</td>
            </tr>
            <tr>
                <td colspan=4>Nominal Akhir</td>
                <td colspan=5>{{number_format($nominal-$cashback,0,".",".")}}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>