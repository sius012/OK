@php 
    $no = 1;
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
            font-family: Arial, Helvetica, sans-serif;
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

        .container .data-wrapper .table{
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
    </style>
</head>

<body>
  
    
            <table style="width: 190mm; margin-left:0px">
                <tr>
                    <th align="center"> <img class="logo-img" src="{{ public_path('assets/logo.svg') }}" alt=""></th>
                </tr>
                <tr>
                    <th align="center"><p class="address">
                            Jl. Agus Salim D no.10 <br> Telp/Fax. (024) 3554929 / 085712423453 Semarang <br>
                        </p></th>
                </tr>
            </table>
  
            <br>
            <br>
            <h3>Data STOK Produk</h3>
        <p style="margin-bottom: 20px">Tanggal : {{date('d-M-Y')}}</p>


            @foreach($data as $datas)
            @php  $no = 1@endphp
            <p style="margin-bottom:0px;padding:0px"><b>Tipe : {{$datas[0]->nama_tipe}}</b></p>
            <table class="table-data" style="width:200mm !important; margin-top: 20px; margin: 20px" >
              
                    <tr>
                        <th >CODE TYPE</th>
                        <th style="width:60px">MERK</th>
                        <th style="width:170px">NAMA BARANG</th>
                        <th >1/2 PSG</th>
                        <th>DISPLAY</th>
                        <th>GUDANG</th>
                        <th >TOTAL</th>
                    </tr>
                    @foreach($datas as $da)
                    <tr>
                    <td>{{$da->nama_kodetype}}</td>
                        <td >{{$da->nama_merek}}</td>
                        <td>{{$da->nama_produk}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      

                    </tr>
                    @php $no++ @endphp
              

                    @endforeach
                
            </table>
            @endforeach
 
 
</body>

</html>