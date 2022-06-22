@php
$whoactive = "menusuratjalan";
$master='kasir';
$hastoday = false;
$haslampau = false;
@endphp
@extends('layouts.layout2')
@section('pagetitle', 'Riwayat Surat Jalan')
@section('icon', 'fa fa-history mr-2 ml-2')
@section('title', 'Riwayat Surat Jalan')



@section('css')
<link rel="stylesheet" href="{{ asset('css/transaksi.css') }}">

@endsection
@section('js')
<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/menusuratjalan.js') }}"></script>
@stop
@section('content')

<div class="card ml-2">
<div class="card-header">
  Filter
</div>
  <div class="card-body">
  <form action="{{url('/menusuratjalan')}}" method="get">
                @csrf
                
                <div class="wrappers d-inline-flex mb-0">
                <input class="search-box form-control form-control-sm mr-2" type="text" placeholder="Cari nota..." name="no_nota">
                <div style="width: 200px;" class="form-group mr-2">
                    <select name="status" id="tipe" class="form-control dynamic w-100 form-control-sm" data-dependent = "state">
                        <option value="">Status</option>
                            <option value = "lunas">Lunas</option>
                            <option value = "belum lunas">Belum lunas</option>
                            <option value = "return">Retur</option>
                    </select>
                </div>
                <div style="width: 200px;" class="form-group">
                    <select name="waktu" id="tipe" class="form-control dynamic w-100 form-control-sm" data-dependent = "state">
                        <option value="">Waktu</option>
                            <option value = "terbaru">Terbaru</option>
                            <option value = "terlama">Terlama</option>
                    </select>
                </div>

               
                </div>
                
                <button class="btn btn-primary btn-sm ml-2" type="submit"><i class='fa fa-search'></i></button>
                </form>

               


                
                <!--<button data-toggle="modal" data-target="#modaluser" class="btn btn-info"><i class="fa fa-excel; mr-3"></i>Unduh Daftar Pelangan</button>-->
  </div>
</div>

<div>
@foreach($data as $datas)
@if(\Carbon\Carbon::parse($datas['created_at'])->isToday() == 1 and $hastoday == false)
<h5 class="font-weight-bold ml-2 mb-2">Hari Ini</h5>
@php $hastoday=true @endphp
@elseif(\Carbon\Carbon::parse($datas['created_at'])->isToday() == 0 and $haslampau == false)
<h5 class="font-weight-bold">Sebelumnya</h5>
@php $haslampau=true @endphp
@endif
<div class="cardo">
<div class="container">
  <div class="row text-center">
  <div class="col text-center"><div class="text-center" style="width: 120px;">Inv.</div></div>
    <div class="col text-center"><div class="text-center" style="width: 130px;"  >Nama</div></div>
    <div class="col text-center"><div class="text-center" style="width: 120px;">Tagihan</div></div>
    <div class="col text-center"><div class="text-center" style="width: 150px;">Status</div></div>
    <div class="col text-center"><div class="text-center" style="width: 300px;"></div></div>
    
    <div class="w-100 mb-2"></div>
    <div class="col text-center"><div style="width: 120px;" class="font-weight-bold">{{$datas['no_nota']}}</div></div>
    <div class="col text-center"><div style="width: 120px;" class="font-weight-bold">{{$datas['nama_pelanggan']}}</div></div>
    <div class="col text-center"><div style="width: 120px; font-weight:700">Rp.     {{number_format($datas["subtotal"])}}</div></div>

    <div class="col-2 text-center">
      <div style="width: 150px;">
       
        <span  class="bg-success font-weight-bold pl-3 pr-3 text-center rounded-pill" style="width:10px ">Surat Jalan</span>
    </div>
  </div>
    <div class="col text-center e">
      <div style="width: 300px;" class="">
        @if((Auth::user()->roles[0]['name'] == 'manager' or Auth::user()->roles[0]['name'] == 'kasir') and $datas["status"]!="draf")
        <div class="d-inline">
          <a id_trans="{{$datas['kode_trans']}}" class="btn btn-warning printing btn-sm m-1 w-25"><i style="" class="fa fa-print"></i></a>
          <a href="{{url('/kasir?jenis=normal&id_sj='.$datas['kode_trans'])}}" style="padding-left: 12px; padding-right: 12px;" id_trans="{{$datas['kode_trans']}}" class="btn btn-primary btn-sm returntrans"><i style="" class="fa fa-"></i>Lanjutkan</a>
        </div>
        @endif
        @if(Auth::user()->roles[0]['name'] == 'manager' and $datas["status"]!="draf")
        <a href="{{route('hapusdraft',['id'=>$datas['kode_trans']])}}" id_trans="{{$datas['kode_trans']}}" class="btn btn-danger hapustrans btn-sm m-1 w-25"><i style="" class="fa fa-trash"></i></a>
        @endif
    </div>
  </div>
  </div>
</div>
</div>
@endforeach
</div>
@endsection
