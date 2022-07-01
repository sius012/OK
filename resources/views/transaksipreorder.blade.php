@php  $whoactive='riwayatnotabesar';
$haslampau = false;
$hastoday = false;

$master='kasir' @endphp
@extends('layouts.layout2')
@section('pagetitle', 'Transaksi Preorder')
@section('icon', 'fa fa-history mr-2 ml-2')
@section('title', 'Riwayat Transaksi')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transaksiPreorder.css') }}">
    <script src="{{ asset('js/print.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/transaksi_progress_bar.css') }}">
    <style>
      td{
        text-align: left;
      }
    </style>
    <script>
      $(document).ready(function(){
        $("#infomodal").modal('show');
        $(".btnClose").click(function(){
          $("#infomodal").modal("hide");
        });

        $(".btnhapus").click(function(e){
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

        $("#printbutton").click(function(){
            
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {
                id_transaksi : $(this).attr('id_nb')
            },
            url: "/cetaknotabesar",
            type: "post",
            success: function(response){
                printJS({printable: response['filename'], type: 'pdf', base64: true});
            },error: function(err){
                Swal.fire('terjadi kesalahan','','info');
                alert(err.responseText);
            }
        });
    });

    $("#sj2").click(function(e){
        
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            url: "/kirimsj2",
            type: "post",
            data: {
                id_trans : $(this).attr('id_trans'),
              
            },
            success: function(response){
              
                 printJS({printable: response['filename'], type: 'pdf', base64: true});
              
            },error: function(err){
                 alert(err.responseText);
            }
         });
    });



      });
    </script>
@endsection

@section('content')
<form action="{{route('caritranspreorder')}}" method="post">
  @csrf
        <div class="row mb-5">
            <div class="col-12">
                <input class="search-box " type="text" placeholder="Cari nota besar..." name="no_nota">
                <button type="submit" class="search-icon"><i class="fas fa-search p-1"></i></button>
            </div>
        </div>
      </form>

        <div class="row">
          
        </div>
    
        @foreach($data as $datas)
        @if(\Carbon\Carbon::parse($datas['created_at'])->isToday() == 1 and $hastoday == false)
<h5 class="font-weight-bold ml-2 mb-2">Hari Ini</h5>
@php $hastoday=true @endphp
@elseif(\Carbon\Carbon::parse($datas['created_at'])->isToday() == 0 and $haslampau == false)
<h5 class="font-weight-bold">Sebelumnya</h5>
@php $haslampau=true @endphp
@endif


             <div class="card datatrans p-2"  id_trans="{{$datas['no_nota']}}">
                <div class="card-header p-0 mt-0">
                    <div class="wrapperzz p-1 mb-4 mt-0 m-1">
                      <h6 style="font-size: 0.85rem; font-weight: bold;" class="card-title float-right mr-2">{{strtotime(date("d-m-Y")) < strtotime(date("d-m-Y", strtotime($datas['min3jatuhtempo']))) ? date("d-M-Y", strtotime($datas["created_at"])) : "Telah mendekati jatuh tempo"}} </h6>
                      <h6 style="font-size: 0.85rem; font-weight: bold;" class="card-title">No Nota :  {{$datas["no_nota"]}}</h6>
                    </div>
                </div>
                <input type="hidden" >
                <table class="table table-borderless m-0">
       
                      <tr style="font-size: 0.75rem;">
                          <th style="width: 200px"><div >Telah diterima dari</div></th>
                          <th style="width: 200px"><div >Total</div></th>
                          <th style="width: 120px"><div >Tagihan 1</div></th>
                          @if($datas[0] != null)
                          <th style="width: 120px"><div >Tagihan 2</div></th>
                          <th style="width: 120px"><div >Tagihan 3</div></th>
                          @endif
                          <td style="width: 110px" rowspan="2" align="center" valign="center" class=""><div class="mt-3 justify-content-center">
                              <a href="{{route('showdetail',['no_nota'=>$datas['no_nota']])}}" class="" ><i style="background-color:#1562AA; color:white; padding:10px; border-radius:100%;" class="fa fa-list"></i></a>
                          </div></td>
                      </tr>
                   
                      <tr style="font-size: 0.50rem;">
                          <td><div>{{$datas["ttd"]}}</div></td>
                          <td><div>Rp. {{number_format( $datas["total"] )}}</div></td>
                      
                          <td><div class="mt-1"><i class="fa fa-check-circle"></i></div></td>
                          @if($datas[0] != null)
                          <td align="center" valign="center"><div>@If($datas[0][0]->status == "dibayar")<div class="mt-1"><i class="fa fa-check-circle"></i></div>@else <div><a style="font-size: 0.75rem;" class="btn btn-success text-light" href="{{route('prosesbayar',['id' => $datas[0][0]->id_transaksi])}}">Bayar</a></div>@endif</td>
                          <td><div>@If(  $datas[0][1]->status == "dibayar" and $datas[0][1]->status == "dibayar")<div class="mt-1"><i class="fa fa-check-circle"></i></div>@elseif($datas[0][1]->status == "menunggu") @else <div><a class="btn btn-success text-light" href="{{route('prosesbayar',['id' => $datas[0][1]->id_transaksi])}}">Bayar</a></div>@endif</td>
                          @endif
                      </tr>
                  
                </table>
                <div class="card-clicker">

                </div>
            </div>

            @endforeach



              




</div>


@isset($info)
  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" id="infomodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">No. Nota: {{" "}} {{$info[0]->no_nota}}</h5>
          <button type="button" class="close btnClose" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="stepper-wrapper">
                <div class="stepper-item @if($info[0]->status == 'dibayar') completed  @endif ">
                  <div class="step-counter @if($info[0]->status == 'dibayar')  text-light @endif">1</div>
                  <div class="step-name">Termin 1(DP)</div>
                </div>
                @if($opsi!=null)
                <div class="stepper-item  @if($info[1]->status == 'dibayar') completed  @endif ">
                  <div class="step-counter @if($info[1]->status == 'dibayar') text-light  @endif ">2</div>
                  <div class="step-name">Termin 2</div>
                </div>
                <div class="stepper-item  @if($info[2]->status == 'dibayar') completed  @endif ">
                  <div class="step-counter @if($info[2]->status == 'dibayar') text-light  @endif ">3</div>
                  <div class="step-name">Termin 3(Pelunasan)</div>
                </div>
                @endif
            </div>

            <hr class="m-0 p-0">
            <div class="card border-dark mb-3">
                <div class="card-header mb-3">
                  Tanggal Pemesanan : {{date('d-M-Y',strtotime($info[0]->created_at))}}
                </div>
                <div class="card-body text-dark m-0 p-0">
                  <div class="container-wrapper">
                      <table class="table table-striped table-borderless">
                        <tr>
                          <th class="float-left">Telah Diterima Dari </th><td align=left>{{$info[0]->ttd}}</td>
                        </tr>
                        <tr>
                          <th class="float-left">Up </th><td>{{$info[0]->up}}</td>
                        </tr>
                        <tr>
                          <th class="float-left">Uang Sejumlah  </th><td>Rp. {{number_format($info[0]->us,0,".",".")}}</td>
                        </tr>
                        <tr>
                          <th class="float-left">Berupa </th><td>{{$info[0]->brp}}</td>
                        </tr>
                        <tr>
                          <th class="float-left">Guna Membayar </th><td>{{$info[0]->gm}}</td>
                        </tr>
                        <tr>
                          <th class="float-left">Total  </th><td>Rp. {{number_format($info[0]->total,0,".",".")}}</td>
                        </tr>
                        @if($opsi!=null)
                        @foreach($opsi as $opsis)
                        <tr>
                          <th class="float-left">{{$opsis->judul}} </th><td align="left">{{$opsis->ket}}</td>
                        </tr>
                        @endforeach
                        @endif
                      </table>
                  </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary btnClose" data-dismiss="modal">Tutup</button>
        @if($opsi!=null)
       @if($info[2]->status == 'ready' or $info[2]->status == 'dibayar') <button class="btn btn-primary" id="sj2" id_trans="{{$info[1]->id_transaksi}}">Surat Jalan</button>@endif
       @endif

       @if($opsi!=null)
        <button id="printbutton" type="button" id_nb=" @if($info[1]->status == 'dibayar' and @info[2]->status == 'ready') {{$info[1]->id_transaksi}} @elseif( $info[2]->status == 'dibayar') {{$info[2]->id_transaksi}} @else  {{$info[0]->id_transaksi}} @endif" class="btn btn-primary">Cetak</button>
       @else
       <button id="printbutton" type="button" id_nb="{{$info[0]->id_transaksi}}" class="btn btn-primary">Cetak</button>
       @endif
        </div>
      </div>
    </div>
  </div>

  @endisset
@endsection