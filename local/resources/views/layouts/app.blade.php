<!DOCTYPE html >
<?php
use App\func as f;

?>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{url('favicon.ico')}}" type="image/x-icon"/>

    <title>ระบบสารสนเทศสำหรับจัดการข้อมูลผลงาน</title>

    <!-- Styles -->
    <link href="{{ url('css/main3.css') }}" rel="stylesheet">
    <link href="{{ url('css/custom-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"  href="{{ url('css/font-awesome.min.css')}}">
    
    

    <!-- Scripts -->
        <script src="{{ url('js/jquery-2.1.4.min.js') }}"></script>
        <script src="{{ url('js/bootstrap.min.js') }}"></script>
        <script src="{{ url('js/plugins/pace.min.js') }}"></script>
        <script src="{{ url('js/main.js') }}"></script>
        
</head>
    <body class="sidebar-mini fixed">
           
        <div id="wrapper">
            <header class="main-header hidden-print">
            <a class="logo" href="{{url('/')}}"><img  src="{{url('img/fitm-logo-white.png')}}" width="60" style="margin-top:-15px"></a>
            <nav class="navbar navbar-static-top ">
                <!-- Sidebar toggle button-->
                <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
                <a class="navbar-brand" href="#">
                    ระบบสารสนเทศสำหรับจัดการข้อมูลผลงาน
                </a>
            <!-- Navbar Right Menu-->
            <div class="navbar-custom-menu">
                <ul class="top-nav">
                <!-- User Menu-->
                <li class="dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-lg fa-angle-down"></i></a>
                    <ul class="dropdown-menu settings-menu">
                    <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                <i class="fa fa-power-off" aria-hidden="true"></i>
                                                ออกจากระบบ
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                    </li>
                    </ul>
                </li>
                </ul>
            </div>
            </nav>
        </header>
        <!-- Side-Nav-->
        <aside class="main-sidebar hidden-print">
            <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left image"><img class="img-circle" width="50" src="{{(Session::get('avatar')=='') ? url('img/default-user.png'):Session::get('avatar')}}" alt="User Image"></div>
                <div class="pull-left info">
                <p class="user-name">{{ f::cutStr(Auth::user()->title_name." ".Auth::user()->name." ".Auth::user()->lastname,16,"...") }}</p>
                <p class="designation">{{Auth::user()->user_position_name}}</p>
                </div>
            </div>
            <!-- Sidebar Menu-->
            <ul class="sidebar-menu">
                <li class="@if($page=="index")active @endif"><a href="{{url("/")}}"><i class="fa fa-home fa-lg " aria-hidden="true"></i><span> หน้าหลัก</span></a></li>
                <li class="@if($page=="research-devinv")active @endif"><a href="{{url('/research-devinv')}}"><i class="fa fa-folder  fa-lg" aria-hidden="true" style="color:#4074a5" ></i><span> งานวิจัยและพัฒนาสิ่งประดิษฐ์</span></a></li>
                <li class="@if($page=="academic-dev")active @endif"><a href="{{url('/academic-dev')}}"><i class="fa fa-folder  fa-lg" aria-hidden="true" style="color:#4074a5"></i><span> งานพัฒนาวิชาการ</span></a></li>
                <li class="@if($page=="academic-pub")active @endif"><a href="{{url('/academic-pub')}}"><i class="fa fa-folder  fa-lg" aria-hidden="true" style="color:#4074a5"></i><span> งานเผยแพร่ผลงานทางวิชาการ</span></a></li>
                <li class="@if($page=="academic-service")active @endif"><a href="{{url('/academic-service')}}"><i class="fa fa-folder  fa-lg" aria-hidden="true" style="color:#4074a5" ></i></i><span> งานบริการวิชาการและอื่นๆ</span></a></li>
                <li class="@if($page=="training")active @endif"><a href="{{url('/training')}}"><i class="fa fa-folder  fa-lg" aria-hidden="true" style="color:#4074a5"></i><span> งานเข้ารับฝึกอบรม</span></a></li>
                <li class="@if($page=="std-portfolio")active @endif"><a href="{{url('/std-portfolio')}}"><i class="fa fa-graduation-cap fa-lg" aria-hidden="true" style="color:#4074a5"></i><span> ผลงานนักศึกษา</span></a></li>
                @if(Auth::user()->user_level=="dean" or Auth::user()->user_level=="headofDp")
                <li class="@if($page=="report")active @endif"><a href="{{url('/report')}}"><i class="fa fa-book fa-lg" aria-hidden="true" style="color:#4074a5"></i><span> รายงานสรุปผล</span></a></li>    
                @endif
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off" ></i><span>ออกจากระบบ</span></a></li>
            </ul>
            </section>
        </aside>
            @yield('content')  
        </div>
         
    </body>
    
</html>
   