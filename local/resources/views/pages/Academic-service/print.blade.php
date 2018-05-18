<?php  
use App\func as f;
use App\constantsValue as val;
?>
<?php
  $typeAS = val::getTypeNameAS();
?>
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
                  <p style="font-size:20px;display:inline-block" ><b>งานบริการวิชาการและอื่นๆ</b></p><br>
                  <div style="word-wrap:break-word;">
                      <p style="font-size:18px;display:inline-block" >
                        <b>ผู้ปฏิบัติงาน </b>: {{f::getFullName(Auth::user()->id)}}<br>
                        <b>ระหว่างวันที่ </b>: {{f::dateThaiFull(f::dateFormatDB($_GET['startTime']))}} - {{f::dateThaiFull(f::dateFormatDB($_GET['endTime']))}}
                      </p>
                  </div>
                  
                  
                  <table border="1" width="100%" class="tb-report table-hover">
                   <thead>
                    <tr>
                      <th width="8%">ลำดับที่</th>
                      <th width="56%">งานบริการวิชาการและอื่นๆ</th>
                      <th width="18%">วันที่เริ่ม</th>
                      <th width="18%">วันที่สิ้นสุด</th>
                      <!--<th>เอกสาร</th> -->
                    </tr>
                   </thead>
                   <tbody>
                    @for($i =0;$i<count($typeAS);$i++)
                    <?php $isFirst = true;$sub = 0; ?>
                      @foreach($tasks as $key => $task)
                        @if(($i+1) == $task->as_category)
                          <?php  $sub++; ?>
                          @if($isFirst) <!-- check head title -->
                            <tr>
                              <td valign="top" class="text-center">{{$i+1}}.</td>
                              <td valign="top" style="line-height: 1;"><b>{{$typeAS[$i]}}<b></td>
                              <td valign="top" class="text-center"></td>
                              <td valign="top" class="text-center"></td>
                            </tr>
                            <?php $isFirst = false; ?>
                          @endif
                          <tr>
                            <td valign="top" style="border:0px!important"></td>
                            <td valign="top" style="line-height: 1;">{{($i+1).".".$sub}} {{$task->as_name}}</td>
                            <td valign="top" >{{f::dateThaiFull($task->as_start_date)}}</td>
                            <td valign="top" >{{f::dateThaiFull($task->as_start_date)}}</td>
                          </tr>
                        @endif
                      @endforeach
                      @if(!$isFirst)
                      <tr>
                        <td height="20" valign="top" colspan="2"></td>
                        <td valign="top"></td>
                        <td valign="top"></td>
                      </tr>
                      @endif
                    @endfor
                   </tbody>
                  </table>
                  
    </div>
  </div>
</body>