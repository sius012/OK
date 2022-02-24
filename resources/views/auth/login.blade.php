<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
        *{
            font-family: 'Quicksand', sans-serif;
        }
        body{
            background-color: #1562AA;
        }
        .container .col .title{
            color: #FAE511;
            font-weight: bold;
        }
        .container .top{
            margin-top: 100px
        }
        .container .row .username label{
            color: white;
        }
        .container .row .username input{
            padding: 10px;
            border: 2px solid white;
            background-color: transparent;

            width: 300px;
            border-radius: 100px;
        }
        .container .row .password label{
            color: white;
        }
        .container .row .password input{
            padding: 10px;
            border: 2px solid white;
            background-color: transparent;

            width: 300px;
            border-radius: 100px;
        }
        .container .login button{
            color: white;
            background-color: #0D5497;
            font-weight: bold;

            text-decoration: none;
            padding: 11px;
            width: 300px;
            border: none;
            border-radius: 100px;
        }
        .container .request-akuns a{
            background-color: white;
            color: #1562AA;
            font-weight: bold;

            padding: 11px;
            text-align: center;
            text-decoration: none;
            width: 300px;
            border-radius: 100px;
        }
    </style>

    <title>Document</title>
</head>
<body>
<form action="{{ route('login') }}" method="POST">
@csrf
    <div class="container">
        <div class="col d-flex align-items-center justify-content-center top">
            <img src="{{asset('assets/Group 1.svg')}}" alt="">
        </div>
        <div class="col d-flex align-items-center justify-content-center">
            <h3 class="title">Omah Kunci</h3>
        </div>

        <br>

        <div class="row d-flex align-items-center justify-content-center">
            <div class="mb-3 username">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control text-light" id="email" name="email">
            </div>
        </div>
        <div class="row d-flex align-items-center justify-content-center">
            <div class="mb-3 password">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control text-light" id="password" name="password">
            </div>
        </div>

        <div class="col login d-flex align-items-center justify-content-center mt-3">
            <button type="submit">Log In</button>
        </div>
        <a class="d-flex align-items-center justify-content-center text-light mt-3" href="#">Lupa Password ?</a>
        <hr class="bg-light" style="width: 350px;">
        <div class="request-akuns d-flex align-items-center justify-content-center">
            <a class="request-akunr" href="{{url('/register')}}">Request Akun</a>
        </div>
    </div>
    </form>
</body>
</html>