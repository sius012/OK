<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @font-face {

font-family: tes;
font-style: normal;
src: url("{{storage_path('/fonts/Consolas-Font/CONSOLA.ttf')}}");
}

@font-face {

font-family: tesb;
font-style: normal;
src: url("{{storage_path('/fonts/Consolas-Font/CONSOLAB.ttf')}}");
}
        * {
            margin: 0px;
            font-family: tes !important;
            font-size: 10pt;
            line-height: 70% ''
        }
     

        
        body {
          
            
            
        }
        
        td{
            height: 0px;
            padding: 1px;
            word-wrap: break-word;

        }

        td h4,h5{
            font-family: tesb !important;
            font-weight: normal;
        }

        .container-wrapper {
            margin: 30px;
            margin-top: 0;
        }

        .container-wrapper .header {
            display: inline-flex;
            margin-bottom: 40px;
            margin-left: 80px;
        }

        .container-wrapper .header .brand-title {
            margin-bottom: 0;
            text-transform: uppercase;
            font-family: tesb !important;
            font-weight: normal;
        }

        .container-wrapper table .address .brand-address {
            margin-top: 0;

            font-size: 8pt;
            line-height: 100%;
        }

        .container-wrapper table .date-times {
            font-size: 10pt;

            margin-left: 230px;
            width: 200px;
        }

        .container-wrapper .big-title {
            text-align: center;
          
            font-family: tesb !important;
            font-weight: normal;
        }

        .container-wrapper .big-title .title {
              margin-bottom: 3px;
          
            font-family: tesb !important;
            font-weight: normal;
            font-size: 12pt;
        }

        .container-wrapper .big-title .hr {
            margin: 0;

            width: 200px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .container-wrapper .big-title .no-nota {
            margin: 0;
        }

        .container-wrapper .content,
        .content h4 {
            text-transform: uppercase;
            margin: 0;
            margin-left: 40px;
        }

        .container-wrapper .ttd .ttd-header {
            text-align: center;
        }

        .container-wrapper .ttd .wrappers {
            display: inline-flex;
        }

        .container-wrapper .ttd .wrappers .customer {
            margin-left: 200px;
        }

        .container-wrapper .ttd .wrappers .sales {
            margin-left: 700px;
        }

        .container-wrapper table {
            width: 750px;
          
        }

        #bigtitle {
            height: 20px;

        }

        h4 {

            font-size: 10pt;
            margin: 0px;
            padding: 0px !important;
        }



    </style>
</head>

<body>
    <div class="container-wrapper">
        <table style="margin-top: 30px;">
            <tr>
                <td  colspan=2>
                    <div class="address" style="width:300px">
                        <img style="height:25px;" src="{{ public_path('assets/logo.svg') }}" alt="">
                        <p class="brand-address">Jl. Agus Salim D no.10  
                            <br>Telp/Fax 085712423453 / (024)3554929  <br>
                            Semarang </p>
                    </div>
                </td>
       
                <td colspan=3 align="right" style="width:10px" valign="top">
                    <h4 class="">Semarang,
                        {{ date("d-M-Y", strtotime($data->created_at))}}</h4>
                </td>
            </tr>
            <tr>
                <td align="center" id="bigtitle" colspan="4">
                    <div class="big-title">
                        @if($jenisnota == "notabesar")
                        <h2 class="title" style="text-decoration: underline;">
                            {{ $data->termin != 3 ? "TANDA TERIMA" : "NOTA" }}
                        </h2>
                        @elseif($jenisnota == "jasapasang")
                        <h2 class="title" style="text-decoration: underline;">
                            {{"JASA PASANG"}}
                        </h2>
                        @else
                        <h2 class="title" style="text-decoration: underline;">
                            {{"JASA SERVICE"}}
                        </h2>
                        
                        @endif
                        <h5 class="no-nota">NO.{{ $data->no_nota }}</h5>
                    </div>
                </td>

            </tr>
            <tr>
                <td style="width:200px" valign="top">
                    <h4>Telah terima dari</h4>
                </td>
                <td colspan="3"> {{ $data->ttd }}</td>
                
            </tr>
            <tr>
                <td style="width:200px" valign="top">
                    <h4>Telepon</h4>
                </td>

                <td colspan="3"> {{ $data->telepon }}</td>
                
            </tr>
            <tr>
                <td valign="top">
                    <h4>Untuk Proyek</h4>
                </td>
                <td colspan="3"> {{ $data->up }}</td>
              
            </tr>
            @if($td != 0)
                <tr>

                    <td valign="top">
                        <h4>Pembayaran Sebelumnya</h4>
                    </td>
                    <td colspan="3">Rp. {{ number_format($td,0,',','.') }}</td>
                 
                </tr>
            @endif
            <tr>
                <td style="" valign="top">
                    <h4>Uang Sejumlah</h4>
                </td>
                <td colspan="3" > Rp. {{ number_format($data->us,0,',','.') }}</td>
    
            </tr>
            <tr>
                <td valign="top">
                    <h4>Berupa</h4>
                </td>
                <td colspan="3"> {{ $data->brp }}</td>
            
            <tr>
                <td valign="top">
                    <h4>Guna Membayar</h4>
                </td>
                <td colspan="3"> {{ $data->gm }}</td>
             
            </tr>
            <tr>
                <td style="padding-bottom: 5px;" valign="top">
                    <h4>Total</h4>
                </td>
                <td colspan="3"> Rp. {{ number_format($data->total,0,',','.') }}</td>
        
            </tr>
            <tr>
                <td></td>
            </tr>
            @foreach($opsi as $opsis)
                <tr>

                    <td valign="top">
                        <h4>{{ $opsis->judul }}</h4>
                    </td>
                    
                    <td colspan="3"> {{ $opsis->ket }}</td>


                </tr>
            @endforeach
            @if($data->termin == 3)
                <tr>

                    <td valign="top">
                        <h4>NB</h4>
                    </td>
        
                    <td colspan="3"> {{ $data->kunci }}</td>
                   

                </tr>

            @endif
                <tr align="center">
                <td colspan="4" style="padding-top:25px; padding-bottom:30px">
                    <h4 class="ttd-header">Mengetahui,</h4>

                </td>
            </tr>
            @if($data->termin != 3)
        
            <tr>
                <td align="center">
                    <div class="wrappers">
                        <h4 class="customer">Customer,</h4>

                    </div>
                </td>
                <td></td>
                <td align="center" style="padding-left:150px">
                    <div class="wrappers">
                        <h4 class="sales">Sales,</h4>

                    </div>
                </td>
            </tr>  <tr >
                <td align="center" >
                    <br><br><br>
                    <div class="wrappers">
                        <h4 class="customer">{{"(".str_repeat('.', 25).")"}}</h4>

                    </div>
                </td>
                <td></td>
                <td align="center" style="padding-left:150px">
                    <div class="wrappers">
                    <br><br><br>
                        <h4 class="customer">{{"(".str_repeat('.', 25).")"}}</h4>

                    </div>
                </td>
            </tr>
            @endif
        </table>
    </div>
</body>

</html>
