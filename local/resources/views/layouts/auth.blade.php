<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{url('favicon.ico')}}" type="image/x-icon"/>
    <title>ระบบจัดการข้อมูลผลงาน</title>

    <!-- Styles -->
    <link href="{{ url('css/main3.css') }}" rel="stylesheet">
    <link href="{{ url('css/custom-style.css') }}" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
    <body class="sidebar-mini fixed login-bg" style="overflow-y: hidden;">
            <header class="main-header nofill-bar hidden-print" style="background:transparent!important;padding-left:20px;padding-right:20px;">
                <nav class="navbar navbar-static-top " style="background:transparent!important;margin-left:0px">
                        <a class="logo" href="{{url('/')}}"><img  src="{{url('img/fitm-logo-white.png')}}" width="60" style="margin-top:-15px"></a>
                        <a class="navbar-brand" href="{{url('/')}}">ระบบสารสนเทศสำหรับจัดการข้อมูลผลงาน
                        </a>
                <!-- Navbar Right Menu-->
                <div class="navbar-custom-menu">
                    <ul class="top-nav bar-login" >
                    <!-- User Menu-->
                    <li class="dropdown notification-menu">
                        <a style="font-family: Mitr;" class="@if($page=="about")active @endif" href="{{url('about')}}" ><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i> About</a>    
                    </li>
                    </ul>
                </div>
                </nav>
            </header>
        <div id="wrapper">
            
        
            

        @yield('content')

        <!-- Scripts -->
        <script src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/plugins/pace.min.js') }}"></script>
        <script src="{{asset('js/main.js') }}"></script>
        </div>
    </body>
</html>
