<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Omah Kunci</title>

    <!-- Fonts -->
    <link rel="icon" href="{{ asset('assets/preloader.svg') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap');
        * {
            margin: 0;
            padding: 0;
            
            font-family: 'Lato', sans-serif;
        }

        body {
            background-image: linear-gradient(to right, #06335C, #1562AA);
        }

        .container .row .col-4 .link-wrapper .login-btn,
        .home-btn,
        .register-btn {
            background-color: #ffec26;
            color: black;

            padding: 5px;
            margin-right: 10px;

            width: 100px;
            border-radius: 8px;

            text-align: center;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="wrapper m-3 ml-4 mb-2">
        <div class="header d-inline-flex">
            <img style="height: 40px;" class="img-header" src="{{ asset('assets/Group 1.svg') }}">
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <div class="row mt-5">
                        <div class="col">
                            <div class="wrapper-inline d-inline-flex">
                                <h2 class="card-title text-light mr-3 font-weight-bold">Amankan Lingkungan anda bersama</h2>
                                <h2 style="color: #FAE511" class="card-title font-weight-bold">Omah Kunci</h2>
                            </div>
                            <p class="card-text w-75 text-light">
                                Aplikasi Pengelolaan toko Omah Kunci
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div style="margin-left: -18px;" class="flex-center position-ref full-height mt-3 link-wrapper">
                                @if (Route::has('login'))
                                <div class="d-inline-flex ml-3">
                                    @auth
                                    <a class="nav-link home-btn" href="{{ url('/redirecting') }}">Beranda</a>
                                    @else
                                    <a class="nav-link login-btn" href="{{ route('login') }}">Masuk</a>

                                    @endauth
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <img style="height: 330px;" src="{{ asset('assets/security.svg') }}">
                </div>
            </div>
            <div style="width: 1210px;" class="row mt-5">
                <footer class="mt-5" style="width: 1600px; margin-left:-40px; margin-top: 13px;">
                    <p class="card-text text-left text-light mt-5 ml-0">
                        Copyright © 2022 Omah Kunci. All rights reserved.
                    </p>
                </footer>
            </div>
        </div>
    </div>
</body>
</html>