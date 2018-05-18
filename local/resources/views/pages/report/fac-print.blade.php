<?php  use App\func as f; ?>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ระบบจัดการข้อมูลผลงาน</title>

    <!-- Styles -->
    <link href="{{ url('css/main.css') }}" rel="stylesheet">
    <link href="{{ url('css/custom-style.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ url('js/jquery-2.1.4.min.js') }}"></script>

        
</head>
<body style="background-color:white">
  <div class="col-md-12">
    <div id="paper">
          @if(isset($html))
          {!!$html!!}
          @endif 
                  
                  
    </div>
  </div>
</body>