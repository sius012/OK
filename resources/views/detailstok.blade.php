@php $whoactive = "stokharian" ;

    $master='admingudang';
@endphp
@extends('layouts.layout2')
@section('icon', 'fa fa-th-list mr-2 ml-2')
@section('pagetitle', 'Stok Harian')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail_stok.css') }}">
<style>
    .myUL{
        top: 173px;
    }
</style>
<script src="{{ asset('js/print.js') }}"></script>
@isset($kodeproduk)
<script>
    $(document).ready(function(){
       
        $("#dssubmit").modal('show');
    });
</script>
@endisset
@if(Session::has('peringatan'))
<script>
    alert('{{count(Session::get("peringatan"))}}'+" "+"Produk Gagal dimasukan, pastikan stok tidak kosong");
</script>
@endif
<link rel="stylesheet" href="{{ asset('css/open_sans.css') }}">
@stop

    @section('title','Stok Harian')
    @section('js')
    <script src="{{ url('js/detailstok.js') }}"></script>
    @stop

        @section('content')
        <div class="form-group ml-2">
            <input type="checkbox" id='tk'>
            <label for="tk">Tambah/Revisi</label>
        </div>
        <div class="form-group ml-2">
            <input type="checkbox" id='rs'>
            <label for="rs">Retur Ke Suplier</label>
        </div>
        <section class="content">
            <div class="container-fluid">
            
                   
          
                        <button style="font-size: 0.85rem" type="button" class="btn float-right btn-primary ml-2" data-toggle="modal"
                        data-target="#cetakmodal"><i style="font-size: 0.85rem" class="fa fa-print mr-1"></i>Print</button>

                        <button type="button" class="btn btn-tambah-data bg-warning ml-2 float-right"
                            data-toggle="modal" data-target="#exampleModals">
                            Return Barang
                        </button>

                        <button type="button" class="btn float-right btn-primary" data-toggle="modal"
                            data-target="#dssubmit">Tambah Data</button>

                        
                
               
            </div>

            <div class="row mb-3">




                <div class="col">
                <button class="btn btn-primary" data-target="#filtermodal" data-toggle="modal"><i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>

            <div class="row">
                <div class="col" id="dscont">


                </div>
            </div>
            <div class="row">
                <div class="col" id="rscont">


                </div>
            </div>





            <!-- Modal -->
            <div class="modal fade bd-example-modal-lg" id="dssubmit" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form id="detailstoksubmitter" @isset($url) action="{{$url}}" @else action="{{url('/detailstok')}}" @endisset  @isset($by)  
                            by={{$by}}
                            np="{{$np}}"
                            hp="{{$hp}}"
                            dsc="{{$dsc}}"
                            tp="{{$tp}}"
                            stn="{{$stn}}"
                            @endisset>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label><br>
                                    <input value="{{date('Y-m-d\TH:i')}}" required type="datetime-local" class="form-control" name="tanggal"
                                        id="tanggal">
                                </div>

                                <div class="form-group parent1">
                                    <label for="produk-select">Produk</label>
                                    :<span class='spanis'></span>
                                    <input class="custom-select inputan-produk" @isset($kodeproduk) value="{{$kodeproduk}}" @endisset name="produk-select" id="produk-select">

                                    <ul class="myUL">
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah-select">Jumlah</label>
                                    <input required type="number" class="form-control" name="jumlah-select" id="jumlah">
                                </div>
                                <div class="form-group">
                                    <label for="status-select">Status</label>
                                    <select class="custom-select" name="status-select" id="status-select" required>
                                        <option value="masuk">Masuk</option>
                                        <option value="keluar">Keluar</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label><br>
                                    <textarea required cols="88.5" rows="5" class="form-control" name="keterangan"
                                        id="keterangan"></textarea>
                                </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>

                        </div>
                        </form>
                    </div>
                </div>
            </div>



            <div class="modal fade bd-example-modal-lg" id="cetakmodal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cetak Stok Harian</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form id="cetaksubmitter">
                            <div class="form-group">
                                    <label for="tanggal">Pilih Berdasarkan</label><br>
                                    <select name="" id="berdasarkan" class="form-control">
                                    <option value="hmb">Harian Minggu Bulanan</option>
                                        <option value="tanggal">Tanggal</option>
                                      
                                    </select>
                                </div>
                                <div class="row" id="toggle-tanggal">
                                    <div class="col">
                                    <label for="">Dari</label>
                                    </div>
                                    <div>
                                    <input type="date" id="tanggal2" class="form-control">
                                    </div>
                                    <div class="w-100">

                                    </div>
                                    <div class="col">
                                    <label for="">Sampai</label>
                                    </div>
                                    <div>
                                    <input type="date" id="tanggal3" class="form-control">
                                    </div>
                                    
                                   
                                </div>
                                <div class="form-group">
            <label for="ck"> Unduh Untuk Admin Gudang </label>
            <input id="ck" class="" type="checkbox" name="cua">
          </div>

                                <div class="form-group" id="toggle-hmb">
                                    <select name="" id="hmb" class="form-control">
                                        <option value="harian">Harian</option>
                                        <option value="mingguan">Mingguan</option>
                                        <option value="bulanan">Bulanan</option>
                                    </select>
                                </div>
                                <div class="form-group parent1">
                                    <label for="produk-select">Produk</label>
                                    :<span class='spanis'></span>
                                    <input class="custom-select inputan-produk" @isset($kodeproduk) value="{{$kodeproduk}}" @endisset name="produk-select" id="produk-select2">

                                    <ul class="myUL">
                                    </ul>
                                </div>
                                <div class="form-check">
                                    <input id="keluars" class="form-check-input" type="checkbox"
                                       >
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Barang Keluar
                                    </label>
                                
                                </div>
                                <div class="form-check">
                                    <input id="masuks" class="form-check-input" type="checkbox" >
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Barang Masuk
                                    </label>
                                
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        id="suplier">
                                    <label class="form-check-label" for="flexCheckIndeterminate">
                                        Retur  Kesuplier
                                    </label>
                                
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

            <div class="modal fade  bd-example-modal-lg" id="exampleModals" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Return Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('/tambahrs') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input name="tanggal" type="date" class="form-control">
                                </div>
                                <div class="form-row mt-3 " id="first-row">
                                    <div class="col-4 parent1">
                                        <label for="">Kode Produk</label>
                                        <input type="text" class="form-control inputan-produk"
                                            placeholder="Ketik Kode Atau Nama" name='kode[]'>
                                        <ul class="myUL">

                                        </ul>
                                    </div>
                                    <div class="col-5 parent1">
                                        <label for="">Nama</label>
                                        <input type="text" class="form-control nama-produk np" placeholder="Nama Produk">
                                    </div>
                                    <div class="col-2 parent1">
                                        <label for="">Jml</label>
                                        <input type="text" class="form-control" placeholder="Jumlah" required name='jumlah[]'>

                                    </div>
                                    <div class="col-1 -parent1">

                                    </div>
                                </div>
                                <a class="btn btn-primary mt-3" id="tambahbutton">Tambah+</a>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control"></textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


        </section>

        <div class="modal fade" tabindex="-1" id="filtermodal" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Filter</h5>
        </div>
        <div class="modal-body">
        <form id="the-filter">
         <div class="form-group">
             <label for="">Pilih Tanggal</label>
             <div class="form-row">
            <div class="form-group">
                <input type="date" class="form-control mb-2" id="m-f">
          </div>
          <div class="form-group m-2">
                sampai
          </div>
          <div class="form-group">
                <input type="date" class="form-control mb-2" id="s-f">
          </div>
                
            </div>
         </div>
           
            <div class="form-group">
                <label for="">Status</label>
                <div class="col">
                <select name="" id="tipe-f" class="form-control">
                <option value="">Pilih Tipe(Masuk Keluar)</option>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">keluar</option>
                </select>
                </div>
                
            </div>

            <div class="form-group parent1">
                <label for="">Produk</label>
                : <span></span>
                <input type="text" class="custom-select inputan-produk form-control" id="produk-f">  
                <ul class="myUL">

                </ul>
                
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search mr-1" ></i>Cari</button>
            </div>
</form>
        </div>
    </div>
  </div>
</div>
        @stop
