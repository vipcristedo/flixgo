<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600%7CUbuntu:300,400,500,700" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('backend/css/bootstrap-reboot.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/jquery.mCustomScrollbar.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/admin.css')}}">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{asset('backend/icon/favicon-32x32.png')}}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{asset('backend/icon/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('backend/icon/apple-touch-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('backend/icon/apple-touch-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('backend/icon/apple-touch-icon-144x144.png')}}">

    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Dmitry Volkov">
    <title>Login</title>

</head>
<body>
<div class="sign section--bg" data-bg="img/section/section.jpg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sign__content">
                    <!-- authorization form -->
                    <form action="{{ route('login.form') }}" method="POST" class="sign__form">
                        @csrf
                        <a href="index.html" class="sign__logo">
                            <img src="{{asset('backend/img/logo.svg')}}" alt="">
                        </a>
                        @if(isset($error))
                        <span class="invalid-feedback sign__text" role="alert">
                            <a href=""><strong>{{ $error }}</strong></a>
                        </span>
                        @enderror
                        <div class="sign__group">
                            <input type="text" class="sign__input" name="email" placeholder="Email">
                        </div>
                        <div class="sign__group">
                            <input type="password" class="sign__input" name="password" placeholder="Password">
                        </div>
                        <button class="sign__btn" type="submit">Sign in</button>
                        <span class="sign__text"><a href="forgot.html">Forgot password?</a></span>
                    </form>
                    <!-- end authorization form -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="{{asset('backend/js/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('backend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('backend/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('backend/js/jquery.mousewheel.min.js')}}"></script>
<script src="{{asset('backend/js/jquery.mCustomScrollbar.min.js')}}"></script>
<script src="{{asset('backend/js/select2.min.js')}}"></script>
<script src="{{asset('backend/js/admin.js')}}"></script>
</body>
</html>
