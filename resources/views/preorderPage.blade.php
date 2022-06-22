@php
$whoactive = "preorderpage";
$master='kasir';
$no=1;

$haslampau = false;
$hastoday = false;


@endphp
@extends('layouts.layout2')
@section('pagetitle', 'Riwayat Preorder')
@section('icon', 'fa fa-history mr-2 ml-2')
@section('title', 'Riwayat Transaksi')




@section('css')
<link rel="stylesheet" href="{{ asset('css/preorderpage.css') }}">

@endsection
@section('js')
<script src="{{ asset('js/transaksi.js') }}"></script>
<script src="{{ asset('js/print.js') }}"></script>
<script>
    $(document).ready(function(){   
        $(".btnclose").click(function(){
            $("#exampleModal").modal("hide");
        });


        $(".hapustrans").click(function(e){
            e.preventDefault();
            Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showCancelyButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: `Hapus`,
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
               window.location = $(this).attr('href');
            } else if (result.isDenied) {
             
            }
          });
        });

        $(".btninfo").click(function(){
            var id=$(this).attr('id_trans');
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr('content'),
                },
                url: "/infopreorder",
                data: {
                    id: id
                },
                type: "post",
                dataType: "json",
                success: function(data){
                   
                    let row = data.map(function(datos,i){
                        return `
                            <tr>
                                <td>${i+1}</td>
                                <td>${datos['kode_produk']}</td>
                                <td>${datos['nama_produk']} ${datos['nama_merek']}</td>
                                <td>${datos['jumlah']}</td>
                            </tr>
                        `;
                    });
                    console.log(id)
                    $("#nn").text("No. nota: "+data[0]['no_nota']);
                    $("#tgl").text("Tanggal: "+data[0]['created_at']);
                    $("#ttd").text("Nama Pemesan: "+data[0]['nama_pelanggan']);
                    $("#tlp").text("No Telp: "+data[0]['telepon']);
                    $("#dp").text("DP: Rp."+parseInt(data[0]['bayar']).toLocaleString());
                    $("#btncetak").attr("id_pre",data[0]['kode_trans']);
                    $("#contproduk").html(row);
                    $("#preorder-parser").attr("href","/kasir?preorder_id="+data[0]['kode_trans']);
                    $("#examplemodal").modal("show");
                    $(".btngo").attr("id_pre",data[0]["kode_trans"]);
                },error: function(err){
                    alert(err.responseText);
                }

            })
        });

        $(".btngo").click(function(e){
            e.preventDefault();
            window.location = "/selesaikanpreorder/"+$(this).attr("id_pre");
        });

        function printpreorder(id_trans){
     
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id_pre: id_trans,
            },
            type: 'post',
            dataType: "json",
            url: "/cetakpreorder",
            success: function(data){
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },
            error: function(err){
                alert(err.responseText);
            }
        });
    }

    $(".btncetak").click(function(){
     printpreorder($(this).attr("id_pre"));
    });



    $(".close").click(function(){
       // alert("hai");
        $("#exampleModal").modal("hide");
    });


    
    });

   
</script>
@stop
@section('content')
    
    @csrf
<div class="row mb-3">
    <form action="{{url('/caripreorder')}}" method="get">
    <div class="col-12">
        <input class="search-box " type="text" placeholder="Ketik Nama Pelanggan" name="nama">
        <button style="border:none; background-color:transparent;"><i class="fas fa-search ml-1 search-icon"></i></button>
    </div>
    </form>
</div>
</form>



@foreach($data as $datas)
@if(\Carbon\Carbon::parse($datas->created_at)->isToday() == 1 and $hastoday == false)
<h5 class="font-weight-bold ml-2 mb-2">Hari Ini</h5>
@php $hastoday=true @endphp
@elseif(\Carbon\Carbon::parse($datas->created_at)->isToday() == 0 and $haslampau == false)
<h5 class="font-weight-bold">Sebelumnya</h5>
@php $haslampau=true @endphp
@endif
<div class="card datatrans p-3" id_trans="">
    <input type="hidden">
    <table class="table table-borderless text-center">
        <tr style="font-size: 0.75rem;" class="mb-0">
            <th style="width: 120px; margin-left:9px;">
                <div>No Nota</div>
            </th>
            <th style="width: 200px">
                <div>Telah terima dari</div>
            </th>
            <th style="width: 200px">
                <div>No Telp</div>
            </th>
            <th style="width: 200px">
                <div>DP</div>
            </th>
            <th style="width:190px;">
                <div>Tanggal Transaksi</div>
            </th>
            <th rowspan="2" style="width:120px;">
                <div class="mt-3"><a id_trans="{{$datas->kode_trans}}" class="btn btn-primary px-3 btninfo"><i style="" class="fa fa-info"></i></a></div>
            </th>
        </tr>
      
        <tr>
            <td style="width: 60px">
                <div>{{$datas->no_nota}}</div>
            </td>
            <td>
                <div>{{$datas->nama_pelanggan}}</div>
            </td>
            <td>
                <div>{{$datas->telepon}}</div>
            </td>
            <td class=" align-items-center justify-content-center">
                <div>Rp. {{number_format($datas->bayar)}}</div>
            </td>
            <td>
                <div>{{date('d M Y' ,strtotime($datas->created_at))}}</div>
            </td>
        </tr>
        @php $no++ @endphp
       
    </table>
</div>
@endforeach









      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informasi Preorder</h5>
        <button type="button" class="close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p id="tgl">Tanggal</p>
      <p id="nn">No. nota</p>
        <p id="ttd">Telah terima dari: Dionisius</p>
        <p id="tlp">Telepon: 083333</p>
        <p id="dp">DP: 083333</p>
        <table class="table"> 
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama dan Merek</th>
                    <th>Jumlah</th>
                <tr>
            </thead>
            <tbody id="contproduk">
                <tr>
                    <td>No</td>
                    <td>No</td>
                    <td>No</td>
                    <td>No</td>
                <tr>
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btnclose" data-dismiss="modal">Tutup</button>
        <button id_pre=""id="btncetak" class="btn btn-primary btncetak ml-2"><i style="" class="fa fa-print"></i></button>
        <a href="/selesaikanpreorder/" id="preorder-parser"><button id_pre=""id="btngo" class="btn btn-secondary btngo ml-2"><i style="" class="fa fa-arrow"></i>Lunasi</button></a>
      </div>
    </div>
  </div>
</div>

@endsection
 