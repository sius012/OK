@php $whoactive = "kelolakun" ;
$master = "manager";
$no=1;
@endphp


@extends('layouts.layout2')

@section('title', 'Manajement akun')
@section('icon', 'fa fa-users mr-3 ml-1')
@section('pagetitle', 'Manajement akun')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manajemen_akun.css') }}">
<link rel="stylesheet" href="{{ asset('css/open_sans.css') }}">
<script src="{{ asset('js/akun.js') }}"></script>
@if ($message = Session::get('info'));
	<script>
        Swal.fire("Permintaan Dikirim","","info");
    </script>
@endif
@stop
@php$no = 1; @endphp

@section('content')
<section class="content">
    <div class="mt-4">
        <div class="card">
            <div class="card-header">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-plus mr-2"></i>Tambah Akun
                </button>


            </div>
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama</th>
                        <th>email</th>
                        <th>role</th>
                        <th>password</th>
                        <th style="width:140px; text-align: center;">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($akun as $akuns)
                    <form class="updaterole" action="{{ route('updateakun',['id'=>$akuns->id]) }}" method='post'>
                        @csrf
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ $akuns->name }}</td>
                            <td>{{ $akuns->email }}</td>
                            <td><select name="roleid" id="" @if(auth()->user()->id == $akuns->id)
                                    @endif class="custom-select">
                                    <option value="1" @if($akuns->rolename=='manager') selected @endif
                                        >Manager</option>
                                    <option value="2" @if($akuns->rolename=='admin gudang') selected
                                        @endif>Admin Gudang</option>
                                    <option value="3" @if($akuns->rolename=='kasir') selected
                                        @endif>Kasir</option>
                                </select></td>
                            <td>

                                <div class="input-group">

                                    <input type="password" name="sandi" class="form-control" id="validationServerUsername" placeholder="Password Baru" aria-describedby="inputGroupPrepend3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text btnshow" id="inputGroupPrepend3" c><i class="fa fa-eye-slash"></i></span>
                                    </div>
                                </div>

                            </td>
                            <td type="submit" class="d-flex align-items-center justify-content-center mb-0"><button type="submit" class="mr-1 btn btn-success p-1 px-2"><i class="fa fa-check p-0"></i></button>
                    </form> @if($akuns->id
                    != auth()->user()->id)<button class="btn btn-danger p-1 px-2"><a href="{{ route('hapusakun',['id'=>$akuns->id]) }}" class="text-light"><i class="fa fa-trash"></i></a></button>@endif
                    </td>
                    </tr>
                    </form>
                    @php$no++ @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                <form action="{{url('/tambahakun')}}" id="tambahakun" method="post">
                    @csrf
                    <div class="form-group">
                        <label>Nama Pengguna : </label>
                        <input class="form-control mb-2" name="name" placeholder="Nama akun" type="text">
                    </div>
                    <div class="form-group">
                        <label>Email : </label>
                        <input class="form-control mb-2" name="email" placeholder="Email" type="email">
                    </div>
                    <div class="form-group">
                        <label>Password : </label>
                        <input class="form-control mb-2" name="password" placeholder="Password" type="password">
                    </div>
                    <div class="form-group">
                        <label>Role : </label>
                        <select class="form-control" name="role">
                            <option value="kasir">Kasir</option>
                            <option value="admin gudang" >Admin Gudang</option>
                            <option value="manager" >Manager</option>
                        </select>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus mr-2"></i>Tambah</button>
            </div>
            </form>
        </div>
    </div>
</div>
@stop
