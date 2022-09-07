@php $whoactive="dashboard";
$master='home'; @endphp
@extends('layouts.layout2')

@section('title', 'Omah Kunci || Dashboard')

@section('content_header')
<h1 class="m-0 text-dark"></h1>
@stop

@section('content')
<!-- Main content -->
<div class="container-fluid">

    <div class="row">
        <div class="col">
            <div style="background-color: #06335C; color: white;" class="box-indicator info-box">
                <div class="box-indicator-wrapper info-box-content">
                    <div class="row">
                        <div class="col-10 d-flex">
                            <h6 class="d-block my-auto">Produk Terjual</h6>
                        </div>
                        <div class="col-2 d-flex">
                            <i style="opacity: 50%;" class="fa fa-inbox mx-auto"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 d-flex"><span class="m-auto">Item</span></div>
                        <div class="col-8">
                            <h1 style="font-size: 50px; font-family: 'Open Sans', 'Sans Serif'; font-weight: 600;"
                                class="text-center">
                                {{ number_format($pt) }}
                            </h1>
                        </div>
                        <div class="col-2 d-flex">
                            <span class="m-auto"
                                style="padding: 6px 10px; background-color: #0047D0; border-radius: 100%;">
                                <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div style="background-color: #06335C; color: white;" class="box-indicator info-box">
                <div class="box-indicator-wrapper info-box-content">
                    <div class="row">
                        <div class="col-10 d-flex">
                            <h6 class="d-block my-auto">Total Produk</h6>
                        </div>
                        <div class="col-2 d-flex">
                            <i style="opacity: 50%;" class="fas fa-archive mx-auto"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 d-flex"><span class="m-auto">Item</span></div>
                        <div class="col-8">
                            <h1 style="font-size: 50px; font-family: 'Open Sans', 'Sans Serif'; font-weight: 600;"
                                class="text-center">
                                {{ number_format($produk) }}
                            </h1>
                        </div>
                        <div class="col-2 d-flex">
                            <span class="m-auto"
                                style="padding: 6px 10px; background-color: #0047D0; border-radius: 100%;">
                                <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div style="background-color: #06335C; color: white;" class="box-indicator info-box">
                <div class="box-indicator-wrapper info-box-content">
                    <div class="row">
                        <div class="col-10 d-flex">
                            <h6 class="d-block my-auto">Pengguna</h6>
                        </div>
                        <div class="col-2 d-flex">
                            <i style="opacity: 50%;" class="fa fa-user mx-auto"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 d-flex"><span class="m-auto">Item</span></div>
                        <div class="col-8">
                            <h1 style="font-size: 50px; font-family: 'Open Sans', 'Sans Serif'; font-weight: 600;"
                                class="text-center">
                                {{ ($user) }}
                            </h1>
                        </div>
                        <div class="col-2 d-flex">
                            <span class="m-auto"
                                style="padding: 6px 10px; background-color: #0047D0; border-radius: 100%;">
                                <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div style="background-color: #06335C;" class="card-body mb-0 text-light rounded-top">
                    <div class="row">
                        <div class="col">
                            <h1 class="card-title"><b>Laporan Harian</b></h1>
                            <div class="row float-right">
                                <h5 style="opacity: 50%" class="card-title mr-2">
                                    <b>Hari Ini, {{ date('d M Y') }}</b>
                                </h5>
                                <i style="opacity: 50%" class="fa fa-clone"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="background-color: #06335C; padding: 20px 50px" class="card-body text-light rounded-bottom">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Nota Kecil</span>
                            </div>
                            <h4>Rp.{{ number_format($daily['hari']['pemasukan nota kecil']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Nota Besar</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['hari']['pemasukan nota besar']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Preorder</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['hari']['pemasukan preorder']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Cashback Nota Kecil</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['hari']['cashback']) }}</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Cashback Nota Besar</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['bulanan']['cashbacknb']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div style="background-color: #06335C;" class="card-body mb-0 text-light rounded-top">
                    <div class="row">
                        <div class="col">
                            <h1 class="card-title"><b>Laporan Mingguan</b></h1>
                            <div class="row float-right">
                                <h5 style="opacity: 50%" class="card-title mr-2">
                                    <b>Hari Ini, {{ date('d M Y') }}</b>
                                </h5>
                                <i style="opacity: 50%" class="fa fa-clone"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="background-color: #06335C; padding: 20px 50px" class="card-body text-light rounded-bottom">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Nota Kecil</span>
                            </div>
                            <h4>Rp.{{ number_format($daily['minggu']['pemasukan nota kecil']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Nota Besar</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['minggu']['pemasukan nota besar']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Preorder</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['minggu']['pemasukan preorder']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Cashback Nota Kecil</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['minggu']['cashback']) }}</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Cashback Nota Besar</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['bulanan']['cashbacknb']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div style="background-color: #06335C;" class="card-body mb-0 text-light rounded-top">
                    <div class="row">
                        <div class="col">
                            <h1 class="card-title"><b>Laporan Bulanan</b></h1>
                            <div class="row float-right">
                                <h5 style="opacity: 50%" class="card-title mr-2">
                                    <b>Hari Ini, {{ date('d M Y') }}</b>
                                </h5>
                                <i style="opacity: 50%" class="fa fa-clone"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="background-color: #06335C; padding: 20px 50px" class="card-body text-light rounded-bottom">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Nota Kecil</span>
                            </div>
                            <h4>Rp.{{ number_format($daily['bulanan']['pemasukan nota kecil']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Nota Besar</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['bulanan']['pemasukan nota besar']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Pemasukan Preorder</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['bulanan']['pemasukan preorder']) }}</h4>
                        </div>
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Cashback Nota Kecil</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['bulanan']['cashback']) }}</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <span><i class="fa fa-chart-bar mr-2"></i></span>
                                <span>Cashback Nota Besar</span>
                            </div>
                            <h4>Rp. {{ number_format($daily['bulanan']['cashbacknb']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- /.row -->
</div>
<!--/. container-fluid -->
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
</section>
@stop