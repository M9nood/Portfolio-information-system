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
                  <p style="font-size:20px"><b>งานเข้ารับฝึกอบรม</b></p>
                  <div style="word-wrap:break-word;">
                      <p style="font-size:18px;display:inline-block" >
                        <b>ผู้เข้ารับฝึกอบรม </b>: {{f::getFullName(Auth::user()->id)}}<br>
                        <b>ระหว่างวันที่ </b>: {{f::dateThaiFull(f::dateFormatDB($_GET['startTime']))}} - {{f::dateThaiFull(f::dateFormatDB($_GET['endTime']))}}
                      </p>
                  </div>
                  <table border="1" width="100%" class="tb-report">
                   <thead>
                    <tr bgcolor="#99b3e6">
                      <th width="8%">ลำดับที่</th>
                      <th width="41%">งานเข้ารับฝึกอบรม</th>
                      <th width="18%">วันที่เริ่ม</th>
                      <th width="18%">วันที่สิ้นสุด</th>
                      <th width="10%">จำนวนบุคลากรที่เข้าร่วม</th>
                    </tr>
                   </thead>
                   <tbody>
                   @foreach( $tasks as $key => $task )
                    <tr>
                      <td valign="top" class="text-center">{{$key+1}}</td>
                      <td valign="top">{{$task->trn_name}}</td>
                      <td valign="top"><b>{{f::dateThaiFull($task->trn_start)}}</td>
                      <td valign="top">{{f::dateThaiFull($task->trn_end)}}</td>
                      <td valign="top" class="text-center">{{f::countCoTeacher($task->coTeacher)}}</td>
                    </tr>
                   @endforeach
                   </tbody>
                  </table> 
                  
    </div>
  </div>
</body>