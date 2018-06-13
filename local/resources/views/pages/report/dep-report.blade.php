<?php  
use App\func as f;
use App\constantsValue as val;
 ?>
<?php
  $tb =  val::getTableAttr();
  $deps = f::getAllDepSameUserFac(Auth::user()->department_id);
  $tb = (object)$tb;
  
?>
@extends('pages.report.index')

@section('subcontent')
<link rel="stylesheet" href="{{ url('css/bootstrap-datepicker.css') }}">
<link rel="stylesheet" href="{{ url('css/smart_wizard.css') }}">
<div class="row">
    <div class='col-md-12'>
       <div class="card">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          @if(Auth::user()->user_level=="dean")
          <li role="presentation" ><a href="{{url('fac-report')}}" ><i class="fa fa-file-text" aria-hidden="true"></i> รายงานคณะฯ</a></li>
          @endif
          <li role="presentation" class="active" ><a href="{{url('dep-report')}}"  ><i class="fa fa-file-text" aria-hidden="true"></i> รายงานภาควิชา</a></li>
        </ul>
        
        <div class="panel panel-default" style="margin-top:30px">
        <!-- Default panel contents -->
        <div class="panel-heading"><h5 style="font-family: Mitr">รายงานผลงานภาควิชา คณะ{{f::getFacultyName(Auth::user()->department_id)}}</h5></div>
        <div class="panel-body">
          <div class="col-md-12 ">
            {{ Form::open(['url' => 'dep-report', 'method' => 'get']) }}
            <table border="0" width="100%" align="center" >
                <tr>
                  <td  class="col-md-3"  style="text-align:right;padding-right:15px"><b>ภาควิชา</b></td>
                  <td  class="col-md-6" style="padding-bottom:10px" align="left">
                    @if(Auth::user()->user_level == "dean")
                    <select class="form-control drop-box"  name="dep" >
                          <option value="">--- เลือกภาควิชา ---</option>
                          @foreach($deps as $key => $dep)
                            <option value="{{$dep->department_id}}" @if(isset($_GET['dep']) and $_GET['dep'] ==$dep->department_id) selected @endif>{{$dep->department_name}}</option>
                          @endforeach 
                    </select>
                    @elseif(Auth::user()->user_level=="headofDp" or f::isOfficer())
                      <select class="form-control drop-box"  name="dep" >
                          <option value="{{Auth::user()->department_id}}">{{f::getDeparmentName(Auth::user()->department_id)}}</option>
                      </select>
                    @endif
                  </td>
                  <td  class="col-md-3"></td>
                </tr>
                <tr>
                  <td  class="col-md-3" align="right" valign="top" style="padding-bottom:10px;padding-right:15px"><b>ประเภทผลงาน</b></td>
                  <td  class="col-md-6" style="padding-bottom:10px">
                    <select class="form-control drop-box"  name="task" >
                          <option value="">--- เลือกประเภทผลงาน ---</option>
                          @foreach($tb as $key => $tb)
                            @if($key != "std-portfolio")
                              <option value="{{$key}}" @if(isset($_GET['task']) and $_GET['task'] ==$key) selected @endif>{{$tb['name']}}</option>
                            @endif
                          @endforeach 
                      </select>
                  </td>
                  <td  class="col-md-3"></td>
                </tr>
                <tr style="padding-bottom:10px">
                  <td class="col-md-3" align="right" valign="top" style="padding-bottom:10px;padding-right:15px" ><b>ช่วงการประเมิน</b></td>
                  <td class="col-md-6"  align="left">
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
                  <td  class="col-md-3"></td>
                </tr>
                <tr>
                  <td class="col-md-3" style="text-align:right;padding-right:15px" ><b>ช่วงวันที่</b></td>
                  <td class="col-md-3">
                    <input type="text" id="dateStart" name="startTime" class="datepicker" data-date-format="mm/dd/yyyy"  required>
                    <span style="padding:0px 5px">ถึง</span>
                    <input type="text" id="dateEnd" name="endTime" class="datepicker" data-date-format="mm/dd/yyyy" required>
                    <button class="btn btn-primary"  >ดูรายงาน</button>
                  </td>
                  <td  class="col-md-3"></td>
                <tr>
              </table>
          {{ Form::close()}}
            </div>
        </div>
        </div>
      </div>
      @if(isset($html))
      <div class="paper-heading">รายงานงานวิจัยและพัฒนาสิ่งประดิษฐ์ &nbsp&nbsp&nbsp อัพเดตวันที่ : {{f::dateThaiFull(date("Y-m-d"))}}</div>
        <div class="card">
          <div class="row">
            {!!$html!!}
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