@php
    $whoactive = 'kasir';
@endphp
@extends('layouts.layout2')

@section('title', 'Kasir')


@section('pagetitle', 'Kasir')


@section('js')
<script src="{{ asset('js/print.js') }}"></script>
<script src="{{ asset('js/mainjs/kasir.js') }}"></script>
<script src="{{ asset('js/preorder.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/kasir.css') }}">
@stop

    @section('content')
    @php
        $date = \Carbon\Carbon::parse(date('Y-m-d h:i:s'))->locale('id');

        $date->settings(['formatFunction' => 'translatedFormat']);

    @endphp

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="card" style="width: 500px" id="searcherbox">
                            
                            <div class="card-header">
                                Pilih Product
                            </div>
                            <div style="border-bottom:1px solid lightgray; x" class="card-body " >
                                <table style="width: 450px">
                                    <tr>
                                        <td > <input style="width: 300px" required class="search-box form-control mr-2" type="text" id="searcher" placeholder="Cari Barang Disini..."></td>
                                        <td style="width: 200px"> <input  min="1" required class="qty form-control " id="qty" placeholder="Quantity" type="number" value=1>
                                            <input style="width: 300px" required class="qty " id="hrg" placeholder="Quantity" type="hidden" value=1></td>
                                            <ul id="myUL">
                                                </ul>
                                    </tr>
                                    <tr>
                                        <td><p class="m-0 mt-3"><b>Harga  </b></p><p class="m-0 mt-3" id="hrg-nominal">: -</p></td>
                                    </tr>
                                </table>
            
                            </div>
                          
                            <div class="card-footer">
                                <button href="" class="btn btn-success" id="tambahproduk">Tambah Product</button>
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
                    <div class="row">
                        <p class="times">
                            {{ $date->format('l, j F Y ; h:i a') }}
                        </p>
                    </div>
                    <div class="row float-right">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            Tambah Preorder
                        </button>

                        <!-- Modal -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <form action="/sss" id="preordersubmitter">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Preorder</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Telah Terima Dari</label>
                                    <input id="ttd" type="text" class="form-control"  aria-describedby="emailHelp" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Telepon</label>
                                    <input id="telepon" type="text" class="form-control"  aria-describedby="emailHelp" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Uang Sejumlah</label>
                                    <input id="us" type="number" class="form-control"  aria-describedby="emailHelp" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Guna Membayar</label>
                                    <input id="gm" type="text" class="form-control" aria-describedby="emailHelp" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Sejumlah</label>
                                    <input id="sejumlah" type="text" class="form-control"  aria-describedby="emailHelp" required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" " id_pre="" id="tombolcetak2">Cetak</button>
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                            </div>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

            </div>

            <div class="row">
                <h5 class="nomor-nota ml-4 mt-4 mb-2">Nota : 001</h5>
                <table class="table table-light table-borderless">
                    <tr>
                        <th>No.</th>
                        <th>Item</th>
                        <th>Jumlah</th>
                        <th>Harga(/pcs)</th>
                        <th>diskon(/pcs)</th>
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
                <div class="col-4">
                    <div class="row">
                        <input class="nama-pelanggan" type="text" placeholder="Nama Pelanggan" id="nama">
                    </div>
                    <div class="row">
                        <label class="subtotal-label" for="subtotal">Subtotal</label>
                        <input type="text" class="subtotal" id="subtotal" name="subtotal" readonly>
                    </div>
                    <div class="row">
                        <label class="diskon-label" for="diskon">Potongan(RP)</label>
                        <input type="" class="diskon uang" id="diskon" name="diskon">
                    </div>
                    <div class="row">
                        <label class="total-label" for="total">Total</label>
                        <input type="text" class="total" id="totality" name="total" readonly>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card" id="tunai">
                        <div class="card-header mb-3" >
                            <p class="card-title">Pembayaran</p>         
                        </div>
                        <div class="card-body">
                            <div class="form-group d-inline-flex">
                                <input style="width:170px;" class="form-control mr-4 usethis uang" type="text" >
                                <select class="custom-select form-control w-25 usethisvia">
                                    <option value="Langsung">Langsung</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="Lainnya">Lainnya </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="wrapper float-right">
                        <div class="row">
                            <button class="btn selesai" id="selesai"><i class="fa fa-check mr-3"></i>Selesai</button>
                        </div>
                        
                        <div class="row">
                            <button class="btn reset " id="reset-button" ><i class="fa fa-trash mr-3"></i>Buang</button>
                        </div>
                        <div class="row">
                            <button class="btn next" id="next-button" >Lanjut</button>
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

    $(document).ready(function(){

    
       

    $(".uang").keyup(function(){
            $(this).val(formatRupiah($(this).val(),""))
    });


    function formatRupiah(angka, prefix){
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split   		= number_string.split(','),
    sisa     		= split[0].length % 3,
    rupiah     		= split[0].substr(0, sisa),
    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
}
});
    </script>

    @stop
