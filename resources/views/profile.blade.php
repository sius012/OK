@php  $whoactive = "pakun";
$master='accountsetting' ;@endphp
@extends('layouts.layout2')

@section('title', 'Profil || Omah Kunci')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <style>
        input[type=file] {
            font-size: 10px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0.0  ;
            width: 75px;
            height: 75px;
       }

       .btn-photo{
        position: absolute;
        width: 50px;
        height: 50px;
        background-color: #143e87;
        border-radius: 50%;
        text-align: center;
        animation: .5s ;
        top: 125px;
        box-shadow: 0px 0px 4px -1px black;
       }

       .btn-photo:hover{
        background-color: #0e2d5e;
        
       }

       .btn-photo i{
        vertical-align: baseline;
        margin-top: 15px;
        color: white;
        font-size: 25px;
       }

       #img{
           height: 175px;
       }
    </style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
    <form action="{{route('accountupdate')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="col">
            <div class="row">

                <div class="col-4">
                    <div class="wrapper ml-2">
                        <div class="row">
                        <img class="rounded-circle profile-img" width='175' height='100' id="img"  src="{{asset('assets/pp/'.$data->pp)}}" alt="" >
                        <div class='btn-photo'><i class='fa fa-image'></i><input  type="file" id="img-input" accept="image/*" name='pp' onchange="loadFile(event)"></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <div class="wrapper-roles">
                                    <h6 class="card-title roles"><b>Role : </b>{{$data->rn}}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="wrapper-times">
                                    <h6 class="card-title times"><b>Tanggal Dibuat : </b>{{date('d-M-Y',strtotime($data->created_at))}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <div style="margin-left: -20px;" class="col-8">
                    <div class="row">
                        <div class="col">
                           
                                <div class="form-group">
                                    <label for="nama">Nama Pengguna</label>
                                    <div class="ml-0 row">
                                        <input type="text" class="form-control nama-input" id="nama" name="nama" value="{{$data->name}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pass">Password</label>
                                    <input type="password" class="form-control" id="pass" name="pass" disabled value="inipasswordbuatomahkuncidarianakbn">
                                    <small id="emailHelp" class="form-text text-muted">Password hanya dapat diubah oleh Manager.</small>
                                </div>
                                <div class="form-group">
                                  
                                          <div class="form-group">
                                            <label class="ml-2" for="email">Email</label>
                                            <div class="row">
                                                <input type="text" class="form-control ml-2 mr-1 w-100 " id="email" name="email" value="{{$data->email}}">
                                            </div>
                                          </div>
                                    
                                </div>
                                
                              
                          
                            <div class="form-group">
                                <button type='submit' class='btn btn-primary'>Perbarui</button>
                                </div>
                           
                        </div>
                         
                    </div>
                  
                </div>
               
            </div>
          
        </div>
    </div>
    </form>
</section>

<script>
  var loadFile = function(event) {
    var output = document.getElementById('img');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
@stop
