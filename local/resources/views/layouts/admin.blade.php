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
    <title>ระบบสารสนเทศสำหรับจัดการข้อมูลผลงานสำหรับ Admin</title>

    <!-- Styles -->
    <link href="{{ url('css/main3.css') }}" rel="stylesheet">
    <link href="{{ url('css/custom-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('css/font-awesome.min.css') }}">

    <!-- Scripts -->
        <script src="{{ url('js/jquery-2.1.4.min.js') }}"></script>
        <script src="{{ url('js/bootstrap.min.js') }}"></script>
        <script src="{{ url('js/plugins/pace.min.js') }}"></script>
        <script src="{{ url('js/main.js') }}"></script>
        
</head>
    <body class="sidebar-mini fixed">
        <div id="wrapper">
            <header class="main-header hidden-print">
                <a class="logo" href="{{url('/admin')}}"><img src="{{url('img/fitm-logo-white.png')}}" width="60" style="margin-top:-15px"></a>
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a class="sidebar-toggle" href="#" data-toggle="offcanvas"></a>
                <a class="navbar-brand" href="#">
                    ระบบสารสนเทศสำหรับจัดการข้อมูลผลงานสำหรับ Admin
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
                <p>{{ f::cutStr(Auth::user()->name." ".Auth::user()->lastname,16,"...") }}</p>
                <p class="designation">{{Auth::user()->user_level}}</p>
                </div>
            </div>
            <!-- Sidebar Menu-->
            <ul class="sidebar-menu">
                <li class="@if($page=="index")active @endif" ><a href="{{url('/admin')}}"><i class="fa fa-home fa-lg " aria-hidden="true"></i><span> หน้าหลัก</span></a></li>
                <li class="@if($page=="user")active @endif"><a href="{{url('/admin/user-manage')}}"><i class="fa fa-address-book-o fa-lg" aria-hidden="true" style="color:#4074a5"></i><span> จัดการผู้ใช้งาน</span></a></li>
                <li class="@if($page=="dept-fac")active @endif"><a href=""><i class="fa fa-font-awesome fa-lg treeview" aria-hidden="true" style="color:#4074a5"></i><span> จัดการข้อมูลภาควิชา/คณะ <i class="fa fa-angle-right"></i></span></a>
                <ul class="treeview-menu">
                    <li><a href="{{url('/admin/dep-manage')}}"><i class="fa fa-circle-o"></i> จัดการข้อมูลภาควิชา</a></li>
                    <li><a href="{{url('/admin/fac-manage')}}"><i class="fa fa-circle-o"></i> จัดการข้อมูลคณะ</a></li>
                </ul>
                </li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off" ></i><span>ออกจากระบบ</span></a></li>
            </ul>
            </section>
        </aside>
            

            @yield('content')
        </div>
        <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
        </script>
        
    </body>
</html>
