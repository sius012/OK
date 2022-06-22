@php
    $whoactive="paket";
    $master='manager';
@endphp
@extends('layouts.layout2')

@section('title', "Paket")
@section('icon', 'fa fa-cubes mr-3')
@section('pagetitle', "Paket")
@section("js")
    <script src="{{asset('js/paket/paket.js')}}"></script>
    <script>
        $(document).ready(function(){
            $("#modalpaketedit").modal("show");
            $(".closedBtn").click(function(e){
                $("#modalpaketedit").modal("hide");
            });



            $(".hps").click(function(e){
                e.preventDefault();
                Swal.fire({
            title: 'Apakah anda yakin ingin menghapus',
            showDenyButton: true,
            confirmButtonText: 'Batalkan',
            denyButtonText: `Hapus`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                
            } else if (result.isDenied) {
                
                window.location = $(this).attr('href');
            }
        })
            });
        });
    </script>
@endsection
@section('content')


<button style="background-color: #094985;" data-target="#modalpaket" data-toggle="modal" class=" btn mb-4 text-light"><i class="fa fa-plus mr-2"></i>Tambah Paket</button>
<div class="wrapper card p-1">
<table style="font-size: 0.75rem" class="table table-borderless  mt-3">
<thead>
    <tr>
        <th style="width: 150px;">Kode Paket</th>
        <th>Nama Paket</th>
        <th style="width: 220px;">Harga</th>
        <th>Tanggal Dibuat</th>
        <th class="text-center" style="width: 170px;">Aksi</th>
    </tr>
</thead>
@forelse($paket as $pakets)
@php $harga = array_sum(explode(",",substr($pakets->harga,0,-1))); @endphp
<tr>
    <td>{{$pakets->kode_paket}}</td>
    <td>{{$pakets->nama_paket}}</td>
    <td>Rp. {{number_format($harga,0,",",".")}}</td>
    <td>27 Januari 2022</td>
    <td class="text-center">
        <a style="font-size: 0.75rem" class="btn btn-danger text-light mr-3 hps" href="{{route('hapuspaket',['id'=>$pakets->id])}}"><i class="fa fa-trash"></i></a>
        <a style="font-size: 0.75rem" href="{{route('editpaket',['id'=>$pakets->id])}}" class="btn btn-warning "><i class="fa fa-edit"></i></a>
    </td>
</tr>
@empty
    <h3 class="p-2">Tidak ada paket terdaftar</h3>

@endforelse
</table>
</div>

<!-- modal-->

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalpaket">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Paket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body">
                <form action="{{url('/tambahpaket')}}" method="post" id="submitterpaket">
                    @csrf
                   
                <div class="form-group">
                    <label for="">Nama Paket</label>
                    <input name="namapaket" type="text" class="form-control" required>
                </div>
                <div class="form-row mb-3 " id="first-row">
                    <div class="col">
                        <Label>Produk</Label>
                        <input type="text" class="form-control inputan-produk bg-light" name="kode[]" placeholder="Kode" required>
                        <ul class="myUL">
                        </ul>
                    </div>
                    <div class="col">
                        <Label>Produk</Label>
                        <input type="text" class="form-control produk bg-light"  placeholder="Produk">
                    </div>
                    <div class="col">
                        <Label>Harga Produk</Label>
                        <input type="text" class="form-control harga-produk bg-light"  placeholder="Harga Produk" readonly>
                    </div>
                    <div class="col">
                        <Label>Jumlah Produk</Label>
                        <input type="text" class="form-control bg-light" name="jumlah[]" placeholder="Jumlah" required>
                    </div>
                    <div class="col">
                        <Label>Harga Promo</Label>
                        <input type="text" class="form-control bg-light" placeholder="Harga Promo" name="harga[]" required>
                    </div>
                </div>
                <button class="btn btn-primary mt-2" type="button" id="tambah-produk">Tambah</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Buat</button>
            </div>
            </form>
        </div>
    </div>
</div>

@isset($data)

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="modalpaketedit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Paket</h5>
                <button type="button" class="closedBtn close" >
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body">
                <form action="{{url('/ubahpaket')}}" method="post" id="submitterpaket">
                    @csrf
                    <input type="hidden" name="id" value="{{$data->id}}">
                <div class="form-group">
                    <label for="">Nama Paket</label>
                    <input name="namapaket" type="text" class="form-control bg-light" value="{{$data->nama_paket}}" required>
                </div>
                @foreach($data2["kode_produk"] as $i => $datas)
                <div class="form-row mb-3 " id="first-row">
                    <div class="col">
                        @if($i == 0)<Label>Produk</Label>@endif
                        <input type="text" class="form-control inputan-produk bg-light" value="{{$datas}}"name="kode[]" placeholder="Kode" required>
                        <ul class="myUL">
                        </ul>
                    </div>
                    <div class="col">
                    @if($i == 0)<Label>Harga</Label>@endif
                        <input type="text" class="form-control harga-produk bg-light"  placeholder="Harga" readonly>
                    </div>
                    <div class="col">
                    @if($i == 0)<Label>Jumlah</Label>@endif
                        <input type="text" class="form-control bg-light" name="jumlah[]" value="{{$data2['jumlah'][$i]}}" placeholder="Jumlah" required>
                    </div>
                    <div class="col">
                    @if($i == 0)<Label>Harga Promo</Label>@endif
                        <input type="text" class="form-control bg-light" placeholder="Harga" value="{{$data2['harga'][$i]}}" name="harga[]" required>
                    </div>
                </div>
                @endforeach
               
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closedBtn" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endisset

@endsection()
