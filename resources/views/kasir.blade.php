@php
    $whoactive = 'kasir';
    $master='kasir';
@endphp
@extends('layouts.layout2')

@php
        $date = \Carbon\Carbon::parse(date('Y-m-d h:i:s'))->setTimeZone("Asia/Jakarta");



        $date->settings(['formatFunction' => 'translatedFormat']);

    @endphp

@section('title', 'Kasir')
@section('icon', 'fas fa-cash-register mr-2 ml-2')
@section('pagetitle', 'Kasir ')
@section('tanggal')

    <p class="times">{{$date->format('l, j F Y ;  h:i ')}}</p>


@stop


@section('js')
<script src="{{ asset('js/print.js') }}"></script>

<script src="{{ asset('js/mainjs/kasir.js') }}"></script>
<script>
    
</script>
@if($retur_id > 0)
    <script>
        $(document).ready(function(){
            
       
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id_retur : $("#returid").val()
            },
            url: "/getdetailofreturn",
            type: "post",
            dataType: "json",
            success: function (data) {
        
                $("#no-nota-retur").val(data["no_nota_retur"]);
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
        });
        

    </script>
@endif



<script src="{{ asset('js/preorder.js') }}"></script>
<script src="{{ asset('js/mytools/tools.js') }}"></script>

<style>
    .dropdown-submenu {
        position: relative;
        
    }

    .dropdown-submenu a::after {
        transform: rotate(-90deg);
        position: absolute;
        right: 6px;
        top: .8em;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-left: .1rem;
        margin-right: .1rem;
    }

    .dropdown-custom{
        border: 2px solid black;
    }
</style>
<script>
    $(document).ready(function () {
         if($("#jenis-transaksi").val() == "suratjalan"){
            $(".usethis").hide();
         }
        
        $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
              
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass('show');


            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                $('.dropdown-submenu .show').removeClass("show");
            });


            return false;
        });
    });

</script>
<link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
<script>
    $(document).ready(function () {
        
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $("meta[name=csrf-token]").attr('content')
            },
            url: "/removesection",
            type: "post",
            success: function () {
         
            },
            error: function (err) {
                alert(err.responseText);
            }
        });
    });

</script>

@stop

    @section('content')
    <input type="hidden" value="{{$preorder_id}}" id="preorderid">
    <input type="hidden" value="{{$retur_id}}" id="returid">
    <input type="hidden" value="" id="no-nota-retur">
    <input type="hidden" value=0 id="subafterdiskon">
    <input style="display:none" type="checkbox" @if($fromsj) checked @endif id="sj-checker">
    <input type="hidden" value="{{$id_sj}}" id="sj-id">
    <section class="content">
        <input type="hidden" id="id_pre">
        <input type="hidden" id="jenisproduk">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="row mb-3">
                        <select style="width: 500px;" name="" id="jenis-transaksi" class="form-control">
                            <option @if($jenis=="umum") selected @endif value="normal">
                                Transaksi Umum
                            </option>
                            <option  @if($jenis=="preorder") selected @endif value="preorder">
                                Transaksi Preorder
                            </option>
                            <option @if($jenis=="suratjalan")  selected @endif value="suratjalan">Surat Jalan</option>
                        </select>
                        <select name="" id="list-return" class="form-control">
                            
                        </select>
                    </div>
                    <div class="row">
                        <div class="card" style="width: 500px" id="searcherbox">

                            <div class="card-header">
                                <p class="card-title p-2">Pilih Produk</p>
                                <div class="alerts">
                                    <p style="width:300px; left:190px; top:12px;"
                                        class="card-text bg-warning p-2 rounded text-center float-right position-absolute">
                                        <i style="width:23px;"
                                            class="fa fa-info mr-2 text-light bg-dark p-1 rounded-circle text-center"></i>Barang
                                        Tidak Tersedia  <b id="stok-indi"></b></p>
                                    
                                </div>
                            </div>
                            <div style="border-bottom:1px solid lightgray; x" class="card-body ">
                                <table style="w-100">
                                    <tr>
                                        <td width="60%"> <input  required class="search-box form-control w-100 mr-2"
                                                type="text" id="searcher" placeholder="Cari Barang Disini..."></td>
                                                <td class="pl-5 pr-3">Qty: </td>
                                        <td class="align-content-right">   <input min="1" required class="form-control mr-3 w-75" 
                                                id="qty" placeholder="Quantity" type="number" value=1>
                                            <input style="width: 300px" required class="qty mr-3 " id="hrg"
                                                placeholder="Quantity" type="hidden" value=1></td>
                                        <ul id="myUL">
                                        </ul>
                                    </tr>
                                </table>

                            </div>

                            <div class="card-footer">
                                <button href="" class="btn btn-success beforesend" id="tambahproduk"><i class="fa fa-plus mr-2"></i>Tambah Produk</button>
                            </div>
                        </div>
                    </div>
                    <div class="drop">
                        <ul>
                        </ul>
                    </div>
                </div>
                <div class="times-wrapper col-6">
                    <div class="wrapperrs float-right">
                        <div class="row float-right">
                    <div style="width: 450px;" class="col float-right">
                        <div class="form-group form-group ml-2 ">
                            <input type="text" class="form-control mb-1 beforesend" id="nama" placeholder="Nama Pelanggan">
                            <input type="text" class="form-control mb-1 beforesend"  id="telp" placeholder="No. Telp">
                            <input type="text" class="form-control mb-1 beforesend" id="alamat"  placeholder="Alamat">
                            <div class="">
                            <div class="d-inline-flex" >
                                <label style="padding: 6px; width: 210px; background-color: #1363ae" class="rounded text-light mr-1 text-center">Subtotal</label>
                                <input class="form-control bg-light" id="subtotal" type="text" value=0 readonly>
                            </div>
                            <div class="d-inline-flex">
                                <label style="padding: 6px; width: 275px; background-color: #1363ae" class="rounded text-light mr-1 text-center">Disc</label>
                                <input class="form-control bg-light uang beforesend w-75" type="text" id="diskon" min="0" value=0>
                                <select class="form-control w-50" id="prefix">
                                     <option value="rupiah">Rp</option>
                                    <option value="%">%</option>
                                   
                                </select>
                            </div>
                            @if($retur_id > 0)
                            <div class="d-inline-flex " id="only-retur">
                                <label style="padding: 6px; width: 210px; background-color: #06335C" class="rounded text-light mr-1 text-center" >Retur</label>
                                <input class="form-control bg-light"  type="text" value="" id="ta">
                            </div>
                            <div class="d-inline-flex " id="only-retur">
                                <label style="padding: 6px; width: 210px; background-color: #06335C" class="rounded text-light mr-1 text-center" >Nota</label>
                                 <input id="keterangan-retur" class="form-control bg-light"  type="text" value="" >
                            </div>

                            @endif
                            <div class="d-inline-flex normalt">
                                <label style="padding: 6px; width: 210px; background-color: #06335C" class="rounded text-light mr-1 text-center" >Total</label>
                                <input class="form-control bg-light" id="totality" type="text" value=0 readonly>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                     
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <table class="table table-light table-borderless">
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Item</th>
                        <th>Jumlah</th>
                        <th>Harga(/pcs)</th>
                        <th>Diskon(/pcs)</th>
                        <th>Total</th>
                        <th>Aksi</th>
                       
                    </tr>
                    <tbody id="tabling">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
            <div class="col">
                <div class="card" id="tunai" style="margin-left: -10px;">
                    <div class="card-header mb-1">
                        <p class="card-title">Pembayaran</p>
                    </div>
                    <div class="card-body d-inline-flex mt-0">
                        <div class="col-8">
                            <div class="form-group mr-3">
                                <div>
                                <label>Pengiriman : </label>
                                <select class="form-control float-right beforesend mb-3" id="antarkah">
                                    <option value="tidak">Tidak dikirim</option>
                                    <option value="ya">Dikirim</option>

                                </select>
                                </div>
                                <input placeholder="Nominal pembayaran" class="form-control mr-3 mb-3 beforesend usethis uang" type="text">
                                <select class="custom-select form-control beforesend usethisvia">
                                    <option value="Tunai">Tunai</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Debet">Debet</option>
                                </select>
                                
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="wrapper float-right mt-3">
                                <div class="m-2" id="tampil-harga"><input type="checkbox" class="m-2" id="tampil-harga">Tampil Harga Item</div>
                                <div class="row">
                                    <button class="btn selesai selesaiindi" id="selesai"><i class="fa fa-check mr-3"></i>Selesai</button>
                                </div>

                                <div class="row">
                                    <button class="btn reset " id="reset-button"><i class="fa fa-trash mr-3"></i>Hapus</button>
                                </div>
                                <div class="row">
                                    <button class="next text-light" id="next-button"><i class="fa fa-arrow-right mr-3"></i>Lanjut</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




















































            <!-- <div class="row">
            <div class="col-4">
                <div class="row">
                    <input type="text" class="nama-pelanggan" id="nama" placeholder="Nama Pelanggan">
                </div>
                <div class="row mt-3">
                    <div class="col-2">
                        <p class="subtotal">Subtotal</p>
                    </div>
                    <div class="col-2"> 
                        <input class="subtotal-input" type="text" val="" id="subtotal">
                    </div>
                <div class="row">
                    <div class="col-2">
                        <p class="diskon">Diskon</p>
                    </div>
                    <div class="col-2"> 
                        <input class="diskon-input" type="text" id="diskon">
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <p class="total">Total</p>
                    </div>
                    <div class="col-2"> 
                        <input class="total-input" type="text" id="totality">
                    </div>
                </div>
            </div>
          

          <div class="col-3 payment-method">
                <div id="tunai" class="row mt-4">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2">
                                <input class="radio" type="radio">
                            </div>
                            <div class="col-2">
                                <p class="method-1">Tunai</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="methods">Via</p>
                            </div>
                            <div class="col-6">
                               <select id="cashvia-input"  class="form-control selected via " placeholder="pilih metode">
                                    <option value="BCA" selected>BCA</option>
                                    <option value="Mandiri" >Mandiri</option>
                                    <option value="BNI" >BNI</option>
                               </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="methods">nominal</p>
                            </div>
                            <div class="col-2">
                                <input class="method-input" type="text" id="cash-input">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="kredit" class="row mt-2">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2">
                                <input class="radio" type="radio">
                            </div>
                            <div class="col-2">
                                <p class="method-1">Kredit</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="methods">Via</p>
                            </div>
                            <div class="col-6">
                               <select id="kreditvia-input"  class="form-control " placeholder="pilih metode">
                                    <option value="BCA" selected>BCA</option>
                                    <option value="Mandiri" >Mandiri</option>
                                    <option value="BNI" >BNI</option>
                               </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <p class="methods">nominal</p>
                            </div>
                            <div class="col-2">
                                <input class="method-input" type="text" id="kredit-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4 option">
                <div class="row options">
                    <div class="col-2 mt-3">
                        <button class="selesai ml-4" href="#" id="selesai">Selesai</button>
                    </div>
                </div>
                <div class="row options">
                    <div class="col-2">
                        <button class="tunda ml-4" href="#">Tunda</button>
                    </div>
                </div>
                <div class="row options">
                    <div class="col-2">
                        <button class="reset ml-4" href="#" id="reset-button">Reset</button>
                    </div>
                </div>
                <div class="row options">
                    <button class="btn btn-warning ml-4" id="button_cetak"><i class="fa fa-print"></i> Cetak</button>
                </div>
            </div>
        </div>
     </div> -->
    </section>
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

    @stop
