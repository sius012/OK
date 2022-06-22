@php
namespace App\Http\Controllers;
$no = 1;
$subtotal = 0;


@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">





    <title>Document</title>
    <style>

        @font-face {
            font-family: 'tes';
            src: url("{{storage_path('fonts/fontku.ttf') }}") format("truetype");
            font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
            font-style: normal; // use the matching font-style here
        }
        * {
            font-family: monospace;
            text-transform: uppercase;
            font-weight: bold !important;
            letter-spacing: -0.5px;
            font-stretch: 10%;
           
        
        }

        html {
            margin: 0;
            max-width: 80mm;
        }

        body {
            margin: 0px;
            margin-top: 10px;
              margin-left:  10px;

        }

        .cont {}

        h3 {}

        span {
            font-family: "Brush 455";
        }

        .mulia {
            font-size: 35pt;

        }

        .trans:before {
            content: ' ';
            display: block;
            position: absolute;
            left: 15%;
            top: 2%;
            width: 75%;
            height: 50%;
            opacity: 0.2;
            background-image: url("{{public_path('nota/mj.png')}}");
            background-repeat: no-repeat;
            background-position: 40% 50%;
            background-size: 25rem;
        }

        .heading {
            left: 20px;
            margin: 0px;
        }

        .cont {
            width: 21.5cm;
            top: 0px;
        }

        .trans {
            width: 85%;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .trans td {
            border: 1px solid black;


        }

        .trans .null {
            padding: 10px;
        }

        .trans th {
            border: 1px solid black;

        }

        .row {
            padding: 0px;
        }

        h4 {
            margin: 3px;
            font-size: 9.5pt;
        }

        .foot {
            margin: 10px;
        }

        th {
            font-size: 8pt;
        }

        td {
            font-size: 5pt;
            padding: 0px;
            margin-top: -10px;
        }

        img {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            margin-top: 15px;
            margin-left: 10px;

            height: 25px;
        }

        .container {
            justify-content: center;
            margin-top: 0;
        }

        .row {
            left: 100px;
        }

    </style>
    <style>
        .logo-title {
            font-size: 20pt;
            font-weight: bold;

            margin: 0;
            margin-left: -45px;
        }

        .table {
            font-size: 10pt;
            width: 70mm;
            align-items: center;
            table-layout: fixed;

        }

        td,
        th {
            font-size: 10pt;
            word-wrap: break-word;
        }

        .centering {
            margin-top: -20px;
            margin-left: 60px;
        }

        h1 {
            margin: 80px;
            margin-bottom: 40px;
        }

        hr {
            border: dotted;

            margin:0;
        }

        .alamat {
            font-size: 8pt;
            margin-left: -80px;

            font-weight: lighter;

            text-align: center;
            width: 300px;
            margin-bottom:4px;
        }

        p {
            font-size: 10pt;
        }

    </style>
</head>
<body>

    <div class="container">

        <div class="row centering">
            <img src="{{ public_path('assets/logo.svg') }}" alt="">
            <p class="alamat">Jl. Agus Salim D no.10 <br> Telp/Fax.  085712423453 / (024) 3554929 <br> Semarang <br></p>
        </div>


        <hr style="margin:0;">
        <div>
            <table class="table" >

                <tr>
                    <td   width = 100 style="text-align: center; font-size: 1rem;" colspan=2>{{$data[0]->status == "return" ? "RETUR" : NOTA}}</td>
                </tr>
                @if($data[0]->status == 'return')
                <tr>
                    <td style="width: 100px !important"   colspan=2>Invoice: {{ $data[0]->keterangan  }}</td>
                </tr>
                @endif
                <tr>
                    <td>{{$data[0]->no_nota}}</td>
                    <td style="width: 100px !important" align="left" width="300"></td>

                </tr>
                <tr>
                    <td>{{$data[0]->metode}}</td>
                    <td style="width: 100px !important" align="left" width="300"></td>

                </tr>
                <tr>
                   <td>YTH. {{$data[0]->nama_pelanggan}}</td>
                   <td style="width: 100px !important; font-size: 8.5pt" width ='10000' align="right" width="300">{{date("d-m-Y h:i:s")}}</td>
                  
                </tr>
                <tr>
                   <td>{{$data[0]->telepon}}</td>
                    <td style="width: 100px !important" width ='100' align="right">KSR. {{$data[0]->name}}</td>
                </tr>
                <tr>
                   <td  style="width: 100px !important"width ='100' align="left" colspan="2">{{$data[0]->alamat}}</td>
                </tr>

            </table>
        </div>
        <hr sytle="margin:0;">
        <div class="row">
            <div class="col d-flex align-items-center justify-content-center">
                <table class="table2 ">


                  
                        @foreach($data2 as $dats)
                        <tr>

                            <td colspan="3" style="width:50mm">{{$dats->nama_kodetype}} {{$dats->nama_merek}} {{$dats->nama_produk}}</td>


                        </tr>

                        <tr>
                            <td style="width: 125px"> {{$dats->jumlah}} {{$dats->satuan}} {{" x"}} {{number_format($dats->harga,"0",".",".")}}</td>
                            <td  align="left">@if($dats->potongan > 0 )-{{$dats->prefix !== 'rupiah' ? $dats->potongan."%" : number_format($dats->potongan,"0",",",".")}}@endif</td>
                            <td style="width: 70px" align="right">{{number_format(Tools::doDisc($dats->jumlah,$dats->harga_produk,$dats->potongan,$dats->prefix),0,".",".")}}</td>
                            @php $no++; 
                            
                            $subtotal += Tools::doDisc($dats->jumlah,$dats->harga_produk,$dats->potongan,$dats->prefix);
                            @endphp
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                @if($data[0]->status !== 'return')
                <table class="table">
                    @if($data[0]->diskon > 0)
                    <tr>
                        <td>Subtotal</td>
                        <td align="right">{{number_format($subtotal,0,".",".")}}</td>

                    </tr>
                  
                    @endif
                    
                    @if($data[0]->diskon > 0)
                    <tr>
                        <td>DISC</td>
                        <td align="right"> {{$data[0]->prefix == "rupiah" ? number_format($data[0]->diskon, 0,".",".") : $data[0]->diskon."%"}}</td>

                    </tr>
                    @endif
                    @if($data[0]->keterangan_retur != null)
                    <tr>
                        <td>PEMBAYARAN SEBELUMNYA</td>
                        <td align="right" valign="top">{{number_format($data[0]->tlh_bayar,0,".",".")}}</td>
                    </tr>
                    @endif
                   
                    <tr>
                        <td>Grand Total</td>
                        <td align="right">{{number_format(Tools::doDisc(1,$subtotal,$data[0]->diskon,$data[0]->prefix,0,".",".") - $data[0]->tlh_bayar,0,".",".") }}</td>

                    </tr>

                    <tr>
                        <td>Dibayar</td>
                        <td align="right">{{number_format($data[0]->bayar,0,".",".") }}</td>

                    </tr>
                    @if($data[0]->status == "belum lunas")
                    <tr>
                        <td>Kurang Bayar</td>
                        <td align="right">{{number_format(Tools::doDisc(1,$data[0]->subtotal,$data[0]->diskon,$data[0]->prefix) - $data[0]->bayar,0,'.','.') }}</td>

                    </tr>
                    @else
                    <tr>
                        <td>Kembalian</td>
                        <td align="right">{{number_format($data[0]->bayar -$data[0]->subtotal,0,".",".") }}</td>

                    </tr>
                    @endif

                </table>
             
                @endif
                <hr style="margin:0;">
                @if($data[0]->status != 'return')
              
                <p style="margin:3px;">* Barang yang sudah dibeli <br> tidak dapat ditukar<br><br>
                @endif

                
               
                

                    Terimakasih</p>

                   
            </div>
        </div>


</body>
</html>