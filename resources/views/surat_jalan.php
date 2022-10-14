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
            margin: 100px;
            font-family: tes !important;
            font-size: 10pt;
            line-height: 70% ;
        }
     

        
        body {
          
            
            
        }
        
        td{
            height: 0px;
            padding: 1px;
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
        <table style="margin-top: 30px; width: 750px">
            <tr>
                <td>
                    <div class="address" style="width:200px">
                        <img style="height:20px;" src="{{ public_path('assets/logo.svg') }}" alt="">
                        <p class="brand-address">Jl. Agus Salim D no.10 <br> Telp/Fax (024) 3554929 /085712423453 <br> Semarang </p>
                    </div>
                </td>
                <td></td>
                <td align="right" style="width:300px" valign="top">
                    <h4 class="date-times">Semarang,
                        {{ date("d-M-Y", strtotime($data->updated_at)) }}</h4>
                </td>
            </tr>
            <tr>
                <td align="center" id="bigtitle" colspan="3">
                    <div class="big-title">
                        <h2 class="title">
                            Surat Jalan
                        </h2>
                        <h5 class="no-nota">NO.{{ $data->no_nota }}</h5>
                    </div>
                </td>

            </tr>
            <tr>
                <td valign="top">
                    <h4>Telah terima dari</h4>
                </td>
                <td> {{ $data->ttd }}</td>
                <td></td>
            </tr>
            <tr>
                <td valign="top">
                    <h4>Untuk Proyek</h4>
                </td>
                <td> {{ $data->up }}</td>
                <td></td>
            </tr>
            @if($td != 0)
                <tr>

                    <td valign="top">
                        <h4>Telah Dibayar</h4>
                    </td>
                    <td>Rp. {{ number_format($td) }}</td>
                    <td></td>

                </tr>
            @endif
            <tr>
                <td  valign="top">
                    <h4>Uang Sejumlah</h4>
                </td>
                <td > Rp. {{ number_format($data->us) }}</td>
                <td></td>
            </tr>
            <tr>
                <td valign="top">
                    <h4>Berupa</h4>
                </td>
                <td> {{ $data->brp }}</td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <h4>Guna Membayar</h4>
                </td>
                <td> {{ $data->gm }}</td>
                <td></td>
            </tr>
            <tr>
                <td  valign="top">
                    <h4>Total</h4>
                </td>
                <td >  Rp. {{ number_format($data->total)}}</td>
                <td></td>
            </tr>
            <tr><td></td></tr>
            @foreach($opsi as $opsis)
            @if($opsis->judul != "waktu" || $opsis->judul = 'Waktu')
                <tr>

                    <td valign="top">
                        <h4>{{ $opsis->judul }}</h4>
                    </td>
                    <td colspan="2"> {{ $opsis->ket }}</td>
  

                </tr>
            @endif
            @endforeach
          
            <tr align="center" >
                <td colspan="3" style="padding-top:50px; padding-bottom:30px">
                    <h4 class="ttd-header">Mengetahui,</h4>

                </td>
            </tr>
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
            </tr>
        </table>
    </div>
</body>

</html>
