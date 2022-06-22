<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">

    <link rel="icon" href="{{ asset('assets/preloader.svg') }}">

    <title>Omah Kunci || Login</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
        *{
            font-family: 'Quicksand', sans-serif;
        }
        body{
            background-image: linear-gradient(to right, #06335C, #1562AA);
        }
        .container .col .title{
            color: #FAE511;
            font-weight: bold;
        }
        .container .top{
            margin-top: 60px
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
            background-color: #022F59;
            font-weight: bold;

            text-decoration: none;
            padding: 11px;
            width: 300px;
            border: none;
            border-radius: 100px;
        }
        .container .request-akuns a{
            background-color: white;
            color: #022F59;
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
            <img style="height: 80px; margin-bottom: 10px;" src="{{asset('assets/Group 2.svg')}}" alt="">
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
    </div>
    </form>
</body>
</html>