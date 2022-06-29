<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        td,th{
            border: 2px solid black;
            padding: 5 px;
        }

        table{
            border-collapse: collapse;
        }

        button{
            padding: 10px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <a href="{{url('/normalize')}}"><button>Normalisasi perhitungan</button></a>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>kode trans</th>
                <th>produk</th>
                <th>harga/pcs</th>
                <th>jumlah</th>
                <th>diskon</th>
                <th>prefik</th>
                <th>diskon trans</th>
                <th>prefix 2</th>
                <th>subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datatrans as $datos)
                <tr>
                    <td></td>
                    <th>{{$datos->kode_trans}}</th>
                    <th>{{$datos->kode_produk}}</th>
                    <th>{{$datos->harga_produk}}</th>
                    <th>{{$datos->jumlah}}</th>
                    <th>{{$datos->potongan}}</th>
                    <th>{{$datos->prefix_item}}</th>
                    <th>{{$datos->diskon}}</th>
                    <th>{{$datos->prefix_trans}}</th>
                    <th>{{$datos->subtotal}}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>