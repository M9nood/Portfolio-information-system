
<?php  
use App\func as f; 
use App\constantsValue as val;
?>
@extends('pages.Training.index')

@section('subcontent')
<link rel="stylesheet" href="{{ url('css/bootstrap-datepicker.css') }}">
<div class="row">
     <div class='col-md-12'>
       <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('/training')}}" ><i class="fa fa-lg fa-table" aria-hidden="true"></i>&nbsp รายการผลงาน</a></li>
          <li role="presentation" class="active"><a href="{{url('/training/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>
        </ul>
        <div class="panel panel-default" style="margin-top:30px">
          <!-- Default panel contents -->
          <div class="panel-heading title-content"><h5 >ดูรายงาน</h5></div>
          <div class="panel-body">
           <div class="col-md-12 ">
              {{ Form::open(['url' => 'training/report', 'method' => 'get']) }}
              <table border="0" width="60%" align="center">
                <tr>
                  <td width="20%" valign="top" style="padding-bottom:10px" ><b>ช่วงการประเมิน</b></td>
                  <td colspan="4" style="padding-bottom:10px" align="left">
                        <div class="animated-radio-button">
                              <label>
                                <input type="radio" name="time" value="time1" onchange="time1()" @if(isset($_GET['time']) and $_GET['time'] =='time1')checked @endif><span class="label-text"><span class="label-text">รอบที่ 1 ( {{val::getEstimateTime1()}})</span>
                              </label>
                        </div>
                          <div class="animated-radio-button">
                                <label>
                                    <input type="radio" name="time" value="time2" onchange="time2()" @if(isset($_GET['time']) and $_GET['time'] =='time2')checked @endif><span class="label-text"> รอบที่ 2 ( {{val::getEstimateTime2()}})</span></p>
                                </label>
                          </div>
                  </td>
                </tr>
                <tr class="text-center">
                  <td width="20%" align="left" ><b>ช่วงวันที่</b></td>
                  <td width="100"><input type="text" id="dateStart" name="startTime" class="datepicker" data-date-format="mm/dd/yyyy"  required></td>
                  <td width="50"  >ถึง</td>
                  <td width="100" ><input type="text" id="dateEnd" name="endTime" class="datepicker" data-date-format="mm/dd/yyyy" required></td>
                  <td width="150">
                    <button class="btn btn-primary"  >ดูรายงาน</button>
                  </td>
                <tr>
              </table>
              {{ Form::close() }}
            </div>
          </div>
        </div>
      </div>
      @if(isset($_GET['startTime']) and isset($tasks) )
      <div class="paper-heading">รายงานเข้ารับฝึกอบรม &nbsp&nbsp&nbsp อัพเดตวันที่ : {{f::dateThaiFull(date("Y-m-d"))}}</div>
      <div class="card">
        <div class="row">
            <page size="A4"  id="report">
                  <p class="report-head" ><b>รายงานการเข้ารับฝึกอบรม</b></p><br>
                  <div style="word-wrap:break-word;">
                      <p class="report-desc" >
                        <b>ผู้เข้ารับฝึกอบรม </b>: {{f::getFullName(Auth::user()->id)}}<br>
                        <b>ระหว่างวันที่ </b>: {{f::dateThaiFull(f::dateFormatDB($_GET['startTime']))}} - {{f::dateThaiFull(f::dateFormatDB($_GET['endTime']))}}
                      </p>
                  </div>
                  
                  
                  <table border="1" width="100%" class="tb-report table-hover">
                   <thead>
                    <tr>
                      <th width="8%">ลำดับที่</th>
                      <th width="33%">งานเข้ารับฝึกอบรม</th>
                      <th width="15%">วันที่เริ่มอบรม</th>
                      <th width="15%">วันที่สิ้นสุด</th>
                      <th width="8%">จำนวนบุคลากรที่เข้าร่วม</th>
                      <th width="26%">เอกสาร</th>
                      <!--<th>เอกสาร</th> -->
                    </tr>
                   </thead>
                   <tbody>
                   @foreach( $tasks as $key => $task )
                    <tr>
                      <td valign="top" class="text-center">{{$key+1}}</td>
                      <td valign="top">{{$task->trn_name}}</td>
                      <td valign="top" align="center">{{f::dateDBtoBE($task->trn_start)}}</td>
                      <td valign="top" align="center">{{f::dateDBtoBE($task->trn_end)}}</td>
                      <td valign="top" class="text-center"><b>{{f::countCoTeacher($task->coTeacher)}}</b></td>
                      <td valign="top" align="center"><a  data-target="#viewfileModal" data-toggle="modal" data-tname="{{$task->trn_name}}" data-token="{{ csrf_token() }}" data-path="{{url('file/getbyid/'.$task->trn_id)}}"> ดูเอกสาร</a></td>
                    </tr>
                   @endforeach
                   </tbody>
                  </table>
                  
                </page>
                <div style="text-align:center;margin-top:15px;">
                  <?php
                    $printURL = url('training/print')."?startTime=".$_GET['startTime']."&endTime=".$_GET['endTime'];
                    //$printURL = url('pdf/test1');
                  ?>

                  <a class="btn btn-primary" onclick="window.open('{{$printURL}}')" id="print-btn"><i class="fa fa-lg fa-print"></i> พิมพ์รายงาน</a>
                  <a  class="btn btn-viewdt"  data-target="#viewAllfileModal" data-toggle="modal" data-token="{{ csrf_token() }}" data-datest="{{$_GET['startTime']}}" data-dateend="{{$_GET['endTime']}}" data-path="{{url('file/'.Auth::user()->id.'/training/all')}}" ><i class="fa fa-file"></i> พิมพ์เอกสาร</a></td>
                </div>
               </div> 
            </div>  
          @endif
     </div>
  </div>
 

<!-- modal view all file detail -->
  <div class="modal fade" id="viewAllfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog viewfile-modal-dialog "   role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">เอกสาร</h4>
      </div>
      <div class="modal-body viewfile-modal-body" id="scroll">
      <p id="msg"></p>
    </div>
    <hr>
    <div class="modal-footer" style="border-top:0px">
        <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
      </div>
  </div>
</div>
</div>
<!-- modal view file detail -->
<div class="modal fade" id="viewfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog viewfile-modal-dialog "   role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">เอกสาร</h4>
      </div>
      <div class="modal-body viewfile-modal-body" id="scroll">
      <p id="msg"></p>
    </div>
    <hr>
    <div class="modal-footer" style="border-top:0px">
        <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
      </div>
  </div>
</div>
</div>


<script src="{{ url('js/plugins/bootstrap-datepicker-custom.js') }}"></script>
<script src="{{ url('js/plugins/bootstrap-datepicker.th.min.js') }}"></script>
<script src="{{ url('js/modal.script.js') }}"></script>
<script>
$(document).ready(function () {
					 $('.datepicker').datepicker({
							 format: 'dd/mm/yyyy',
							 todayBtn: true,
							 language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
							 thaiyear: true              //Set เป็นปี พ.ศ.
					 }).datepicker("setDate", "0");  //กำหนดเป็นวันปัจุบัน
  var dateSt = "{{(isset($_GET['startTime']))? f::dateFormatBC($_GET['startTime']) : ''}}";
  var dateEnd = "{{(isset($_GET['startTime']))? f::dateFormatBC($_GET['endTime']) : ''}}";
  if(dateSt != ''){
    $('#dateStart').datepicker("setDate", dateSt);
  }
  if(dateEnd != ''){
    $('#dateEnd').datepicker("setDate", dateEnd);
  }
});
function showPath(){
  console.log(window.location.href = '../')
}

function time1(){
  var stYear = '{{val::startyearEstimateTime1()}}'
  var endYear = '{{val::endyearEstimateTime1()}}'
  $('#dateStart').datepicker("setDate", stYear);
  $('#dateEnd').datepicker("setDate", endYear);
}
function time2(){
  var stYear = '{{val::startyearEstimateTime2()}}'
  var endYear = '{{val::endyearEstimateTime2()}}'
  $('#dateStart').datepicker("setDate", stYear);
  $('#dateEnd').datepicker("setDate", endYear);
}


  </script>
@endsection