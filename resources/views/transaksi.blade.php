@php
$whoactive = "riwayattransaksi";
$master='kasir';
$hastoday = false;
$haslampau = false;
@endphp
@extends('layouts.layout2')

@section('title', 'Riwayat Transaksi')
@section('icon', 'fa fa-history mr-2 ml-2')
@section('pagetitle', ' Riwayat Transaksi')




@section('css')
<link rel="stylesheet" href="{{ asset('css/transaksi.css') }}">

@endsection
@section('js')
<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/transaksi.js') }}"></script>
<script src="{{ asset('js/returntransaksi.js')}}"></script>
@stop
@section('content')

<div class="card ml-2">
<div class="card-header">
  Filter
</div>
  <div class="card-body">
  <form action="{{url('/transaksi')}}" method="get">
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

                <button data-toggle="modal" data-target="#modalRiwayat" class="btn btn-primary"><i class="fa fa-print"></i></button>


                <button class="btn btn-danger hps-draf"><i class="fa fa-trash"></i>  Hapus Semua Draf</button>
                <!--<button data-toggle="modal" data-target="#modaluser" class="btn btn-info"><i class="fa fa-excel; mr-3"></i>Unduh Daftar Pelangan</button>-->
  </div>
</div>

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
    <div class="col text-center"><div class="text-center" style="width: 150px;">Dibayar</div></div>
    <div class="col text-center"><div class="text-center" style="width: 150px;">Status</div></div>
    <div class="col text-center"><div class="text-center" style="width: 130px;"></div></div>
    
    <div class="w-100 mb-2"></div>
    <div class="col text-center"><div style="width: 120px;" class="font-weight-bold">{{$datas['no_nota']}}</div></div>
    <div class="col text-center"><div style="width: 120px;" class="font-weight-bold">{{$datas['nama_pelanggan']}}</div></div>
    <div class="col text-center"><div style="width: 120px; font-weight:700">Rp.     {{number_format($datas["subtotal"])}}</div></div>
    <div class="col text-center"><div style="width: 150px; font-weight:700"> Rp. {{number_format($datas['bayar'])}}</div></div>
    <div class="col-2 text-center">
      <div style="width: 150px;">
        @if($datas['status']=="lunas")
        <span  class="bg-success font-weight-bold pl-3 pr-3 text-center rounded-pill" style="width:10px ">Lunas</span>
        @elseif($datas['status']=='belum lunas')
        <button data-toggle="modal" data-target="#exampleModalCenter" class="btn-bayar bg-danger font-weight-bold pl-3 pr-3 text-center rounded-pill" td="{{$datas['bayar']}}" subtotal="{{$datas['subtotal']}}" id_trans="{{$datas['kode_trans']}}">Belum Lunas</button>
        @elseif($datas['status']=='return') <span class="bg-warning font-weight-bold pl-3 pr-3 text-center rounded-pill">Retur</span>
        @elseif($datas['status']=='draf')<span class="bg-primary font-weight-bold pl-3 pr-3 text-center rounded-pill">Draf</span>
        @endif
    </div>
  </div>
    <div class="col text-center e">
      <div style="width: 130px;" class="">
        @if((Auth::user()->roles[0]['name'] == 'manager' or Auth::user()->roles[0]['name'] == 'kasir') and $datas["status"]!="draf")
        <div class="d-inline">
          <a id_trans="{{$datas['kode_trans']}}" class="btn btn-warning printing btn-sm m-1 w-25"><i style="" class="fa fa-print"></i></a>
          <a style="padding-left: 12px; padding-right: 12px;" id_trans="{{$datas['kode_trans']}}" class="btn btn-primary btn-sm returntrans"><i style="" class="fa fa-info"></i></a>
          <a id_trans="{{$datas['kode_trans']}}" class="btn btn-primary printingtt btn-sm m-1 w-25"><b>T</b></a>
        </div>
        @elseif($datas['status']=='draf')
        <a href="{{route('hapusdraft',['id'=>$datas['kode_trans']])}}" id_trans="{{$datas['kode_trans']}}" class="btn btn-danger hapustrans btn-sm m-1 w-25"><i style="" class="fa fa-trash"></i></a>
        @endif
    </div>
  </div>
  </div>
</div>
</div>
@endforeach

<div class="modal fade bd-example-modal-lg" tabindex="-2" role="dialog" id="returnform">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="overflow-y:scroll; max-height:60vh;">
        <p id="np">Nama Pelanggan :</p>
        <p id="tp">Nama Pelanggan :</p>
        <p id="almt">Nama Pelanggan :</p>

        <!-- barisan transaksi setelah retur -->
        <p id="keterangan-retur">Keterangan Retur :</p>
        <p id="nominal-telah-bayar">Keterangan Retur :</p>
        
        <p>Ini adalah daftar barang yang dibeli</p>
        <form  method="post" id="returnform">
        @csrf
        <input type="hidden" id="id_trans" name="id_trans">
        <table class="table table-borderless table-stripped">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th style="width: 200px;">Nama dan Merek</th>
                    <th>Harga</th>
                    <th style="width: 110px;">Diskon(/pcs)</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Jumlah Retur</th>
                </tr>

            </thead>
            <tbody id="returncont">
            
            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning" id="re-button">Kembalikan</button>
          <a href="" id="buyagain-parser"><button type="button"class="btn btn-primary" id="buyagain-button">Transaksi kembali</button></a>
</form>
      </div>
    </div>
  </div>
</div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Pelunasan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
        <input type="hidden" class="form-control" id="totalbayar">
        <input type="hidden" class="form-control" id="td">
            <label for="">Masukan Nominal</label>
            <input type="text" class="form-control uang" id="nominal-bayar">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" >Tutup</button>
        <button type="button" class="btn btn-primary" id="tombolbayar" id_trans="">Bayar</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modaluser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Unduh Data Pelanggan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{url('/downloaddatauser')}}" id="usercetak" method="post">
        @csrf
      <div class="modal-body">
        <div class="form-row mb-3">
          <div class="col">
                <label for="">Mulai dari</label>
                <input name="md" class="form-control" id="md" type="date">
          </div>
          <div class="col">
               <label for="">Sampai dengan</label>
                <input name="sd" class="form-control" id="sd" type="date">
          </div>
        </div>
        <div class="form-check">
        <input name="telepon" checked id="telepon" type="checkbox" class="form-check-input">
          <label for="telepon" class="">No Telepon</label>
         
        </div>
        <div class="form-check">
        <input name="alamat" checked id="alamat" type="checkbox" class="form-check-input">
          <label for="alamat" class="">Alamat</label>
         
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Cetak</button>
      </div>
      </form>
    </div>
  </div>
</div>




<!-- modal print riwayat -->
<div class="modal fade" id="modalRiwayat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cetak Riwayat</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('download_trans')}}" method="post">
        @csrf
      <div class="form-row mb-3">
          <div class="col">
                <label for="">Mulai dari</label>
                <input name="md" class="form-control" id="md" type="date">
          </div>
          <div class="col">
               <label for="">Sampai dengan</label>
                <input name="sd" class="form-control" id="sd" type="date">
          </div>
        
        </div>
     @if(Auth::user()->roles[0]['name'])
        <div class="form-group">
            <label for="ck"> Unduh Untuk Admin Gudang </label>
            <input id="ck" class="" type="checkbox" name="cua">
          </div>
      </div>
      @endif
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Unduh</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-sm setjmlreturn" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <p class="title"></p>
      </div>
      <div class="modal-body">
        <input type="hidden" value="0" class="form-control idnya">
        <p>Jumlah</p>
        <input type="number" class="form-control inputan">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary okebutton">OKE</button>
      </div>
    </div>
  </div>
</div>

<script>
        $(document).ready(function () {




            $(".uang").keyup(function () {
                $(this).val(formatRupiah($(this).val(), ""))
            });


            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }
        });

    </script>
@endsection
