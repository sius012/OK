<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/preloader.svg') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href='{{asset("plugins/summernote/summernote-bs4.min.css")}}'>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script src="{{asset('plugins/jquery/jquery.js')}}"> </script>

    <script src="{{ asset('js/sweetalert2.all.min.js')}}"></script>


    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    @if(Auth::user()->roles[0]['name'] == 'kasir' or Auth::user()->roles[0]['name'] == 'manager')
    <script>
        $(document).ready(function(){
         
            
            $(document).on('click', '.btnlink', function(e){
                e.preventDefault();
                window.location = "/prosesbayar/"+$(this).attr('id_nb');
            });
            $.ajax(
                {
                    headers: {
                        "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content'),
                    },
                    url: "/checkdata",
                    type: "post",
                    dataType: "json",
                    success: function(data){
                        
                         $(".badger").html(data.length);
                        let row=data.map(function(datas){
                            return `<a href="#" id_nb='${datas['id_transaksi']}' class="dropdown-item p-3 btnlink">
                        <i class="fas fa-info mr-2"></i>No Nota ${datas['no_nota']} sudah mendekati jatuh tempo
                    </a>`;
                        });
                        
                        $(".cont-notif").html(row);
                        if(data.length > 0){
                            $(".titler").html(row.length + " Notifikasi");
                        }else{
                            $(".badger").hide();
                            $(".titler").hide();
                        }
                      
                        
                       
                    },error: function(err){
                       
                      //  alert(err.responseText);
                    }
                }
            );
        });
    </script>
    @endif
    @yield('css')
    @yield('js')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper m-0">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center m-0">
            <img clagss="animation__shake" src="{{asset('assets/preloader.svg')}}" alt="AdminLTELogo" height="60" width="60">
            <h3 id="preloader-title">Omah Kunci</h3>
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light bgnavbar ">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
              @if(Auth::user()->roles[0]['name'] != 'admin gudang')      <span class="badge badge-danger navbar-badge badger">0</span>@endif
                    </a>
                    <div class="dropdown-menu dropdown-menu dropdown-menu-right row ">
                    <span class="dropdown-item dropdown-header titler">15 Notifications</span>
                    <div class="dropdown-divider"></div><div class="cont-notif"></div>
                    <div class="dropdown-divider"></div>
                    </div>
                </li>

                <li>

                    <a class="dropdown-item m-0 p-0 mt-2" href="{{ route('logout') }}" data-toggle='modal' data-target='#logoutmdl'>
                        <p class="bg-danger m-2 px-3 py-1 elevation-1 text-align-center rounded-pill">  <i class="fa fa-sign-out text-dark "></i> <b class="">Logout<b></p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>


                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4 m-0 overflow-hidden" style="overflow-x: hidden !important;">
            <!-- Brand Logo -->
            <!-- <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> -->

            <!-- Sidebar -->
            <div class="sidebar  p-0 overflow-x-hidden" style="overflow-x: hidden !important;" >
                <a href="{{url('/accountsetting')}}" class="brand-link m-0 d-flex align-items-center justify-content-center" style="border-bottom:2px solid white;padding: 15px; background-color: #06335C;">
                    <img style="margin-right: -40px;" src="{{asset('assets/preloader.svg')}}" alt="AdminLTE Logo" class="brand-image m-0">
                    <span style="color: #FAE511;" class="brand-text font-weight-bold">Omah Kunci</span>
                </a>
                <!-- Sidebar user panel (optional) -->
                <div class="mt-3 pb-3 mb-3 d-block pl-0  " >
                    <div class="image">
                        <a href="{{url('/accountsetting')}}">
                            <img style="height: 90px;" width="90" src="{{asset('assets/pp/'.auth()->user()->pp)}}" class=" elevation-2 img-circle mb-2" alt="User Image">
                        </a>
                    </div>
                    <div class="">
                        <p class="d-block text-light">{{Auth::user()->name}}</p>
                    </div>
                    @php  
                        $role = "Guest";
                        switch(Auth::user()->roles[0]['name']){
                            case "manager":
                                $role = "Manager";
                                break;
                            case "admin gudang":
                                $role = "Admin Gudang";
                                break;
                            case "kasir":
                                $role="Kasir";
                                break;
                            default:
                                $role="Guest";
                                break;
                        }
                    
                    
                    @endphp

                    
                    <p class="text-light mb-0 mt-0">{{$role }}</p>
                </div>

                <!-- SidebarSearch Form -->
                <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column ml-2" data-widget="treeview" role="menu" data-accordion="false" style="overflow-x: none !important">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               @if(Auth::user()->roles[0]['name'] == 'manager')   
               <li class=" nav-item">
                                        <a href="{{url('/dashboard')}}" class="nav-link {{$whoactive=='dashboard' ? 'active' : ''}}"" >
                  <p>Dashboard</p>
                </a>
              </li>

               @endif
                <li class="nav-item">
                                    <a href="{{url('/stok')}}" class="nav-link {{$whoactive =='katalog' ? 'active' : ''}}">
                                        <p>Katalog</p>
                                    </a>
                                </li>
                        @if(auth()->user()->roles[0]['name'] == 'kasir' or auth()->user()->roles[0]['name'] == 'manager' )
                        <li class="nav-item menu-open">
                            <a style="background-color: rgba(108, 117, 125, 0.253); " href="#" class="nav-link {{$master=='kasir' ? 'actives' : ''}}">
                                <p style="color: white;">
                                    Kasir
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/kasir')}}" class="nav-link {{$whoactive=='kasir' ? 'active' : ''}}"">
                  <p>Transaksi Umum</p>
                </a>
              </li>
              <li class=" nav-item">
                                        <a href="{{url('/transaksi')}}" class="nav-link {{$whoactive=='riwayattransaksi' ? 'active' : ''}}">
                  <p>Riwayat Transaksi</p>
                </a>
              </li>
              <li class=" nav-item">
                                        <a href="{{url('/menusuratjalan')}}" class="nav-link {{$whoactive=='menusuratjalan' ? 'active' : ''}}">
                  <p>Riwayat Surat Jalan</p>
                </a>
              </li>
              <li class=" nav-item">
                                        <a href="{{url('/preorderpage')}}" class="nav-link {{$whoactive=='preorderpage' ? 'active' : ''}}"" >
                  <p>Riwayat Preorder</p>
                </a>
              </li>

                                <li class="nav-item">
                                    <a href="{{url('/notabesar')}}" class="nav-link {{$whoactive=='notabesar' ? 'active' : ''}}"">
                  <p>Nota Besar</p>
                </a>
              </li>
              <li class=" nav-item">
                                        <a href="{{url('/transaksipreorder')}}" class="nav-link {{$whoactive=='riwayatnotabesar' ? 'active' : ''}}">
                                            <p>Riwayat Nota Besar</p>
                                        </a>
                                </li>
                            
                            </ul>
                        </li>
                        @endif


                        @if(auth()->user()->roles[0]['name'] == 'admin gudang' or auth()->user()->roles[0]['name'] == 'manager')
                        <li class="nav-item menu-open">
                            <a style="background-color: rgba(108, 117, 125, 0.253);" href="#" class="nav-link {{$master =='admingudang' ? 'actives' : ''}}">
                                <p style="color: white;">
                                    Admin Gudang
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                               
                                <li class="nav-item">
                                    <a href="{{url('detailstok')}}" class="nav-link {{$whoactive =='stokharian' ? 'active' : ''}}">
                                        <p>Stok Harian</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->roles[0]['name'] == 'manager' )
                        <li class="nav-item menu-open">
                            <a style="background-color: rgba(108, 117, 125, 0.253);" href="#" class="nav-link {{$master=='manager' ? 'actives' : ''}}">
                                <p style="color: white;">
                                    Manager
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/produk')}}" class="nav-link {{$whoactive =='produk' ? 'active' : ''}}">

                                        <p>Produk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/paket')}}" class="nav-link {{$whoactive =='paket' ? 'active' : ''}}">

                                        <p>Paket</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('manajemen_akun') }}" class="nav-link  {{$whoactive =='kelolakun' ? 'active' : ''}}">

                                        <p>Kelola Akun</p>
                                    </a>
                                </li>
                               
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item">
                                    <a href="{{ url('accountsetting') }}" class="nav-link  {{$whoactive =='pakun' ? 'active' : ''}}">

                                        <p>Pengaturan Akun</p>
                                    </a>
                                </li>





                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                <h1 class="m-0 font-weight-bold mb-2">
                                    <i class="@yield('icon')"></i>
                                    @yield('pagetitle')
                                </h1>
                                </div>
                                <div class="col">
                                <div class="float-right">
                                    @yield('tanggal')
                                    </div>
                                    </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield("content")
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->

        <div class="modal fade bd-example-modal-sm" id="logoutmdl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    
    <div class="modal-content p-3">
      <p class="text-center">Anda yakin mau keluar ?</p>
      <div class="row">
          <div class="col-6 d-flex align-items-center justify-content-center">
            <button data-dismiss="modal " class="btn btn-secondary btn-closer" >Tidak</button>
          </div>
          <div class="col-6 d-flex align-items-center justify-content-center">
            <button class="btn btn-danger"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Ya</button>
          </div>
      </div>
     
    </div>
  </div>
</div>

        <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)

        </script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- ChartJS -->
        <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
        <!-- Sparkline asset -->
        <script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
        <!-- JQVMap asset -->
        <script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
        <script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
        <!-- jQuery Knoassethart -->
        <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
        <!-- daterangepasseter -->
        <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
        <!-- Tempusdomiasset Bootstrap 4 -->
        <script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- Summernoteasset> -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrassetbars -->
        <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE Aasset-->
        <script src="{{asset('dist/js/adminlte.js')}}"></script>
        <!-- AdminLTE fassetdemo purposes -->
        <script src="{{asset('dist/js/demo.js')}}"></script>
        <!-- AdminLTE dassetboard demo (This is only for demo purposes) -->
        <script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
</body>
</html>
