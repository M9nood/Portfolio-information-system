<?php
use App\func as f;
use App\constantsValue as val;

$acd_category = val::ACDCategory();
$yy = f::getYearSemester();
?>
@extends('pages.Academic-dev.index')

  
@section('subcontent')

<div class="row">
     <div class='col-md-12'>
       <div class="card">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('/academic-dev')}}" ><i class="fa fa-lg fa-table " aria-hidden="true"></i>&nbsp รายการผลงาน</a></li>
          <li role="presentation" ><a href="{{url('/academic-dev/report')}}"  ><i class="fa fa-lg  fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #009688;border-bottom:0px;color:#009688" href=""  ><i class="fa fa-lg fa-plus-square fa-lg" aria-hidden="true"></i>  &nbsp เพิ่ม</a></li>
        </ul>

        <div class="panel panel-info" style="margin-top:30px">
            <!-- Default panel contents -->
            <div class="panel-heading title-content"><h5 >แบบฟอร์มเพิ่มผลงานพัฒนาวิชาการ</h5></div>
            <div class="panel-body">
              <div  class="alert alert-danger print-error-msg" style="margin:15px 50px;display:none">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul></ul>
                </div>
                {{ Form::open(['url' => 'academic-dev/add/save', 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
                  <div class="col-md-12 dropdown" >
                    <div class="form-group ">
                      <label for="taskName" class="col-sm-3 control-label">ชื่อตำรา/เอกสาร</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control " name="taskName" id="taskName" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="category" class="col-sm-3 control-label">ประเภทงาน</label>
                      <div class="col-sm-6">
                        <select class="form-control drop-box"  name="category">
                          <option value="" >---กรุณาระบุประเภทงาน---</option>
                          @foreach($acd_category as $key => $cat)
                          <option value="{{$key}}" >{{$cat['name']}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="category" class="col-sm-3 control-label">ประกอบวิชา</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control input-sm" name="acd_subject" id="acd_subject">
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="semester" class="col-sm-3 control-label">ภาคการศึกษาที่เริ่มทำ</label>
                        <div style="display:inline-block;margin-top: 5px;" >
                            <div class="col-sm-4">
                              <select class="form-control drop-box" style="margin-top: 0px;"  name="semester" style="min-width: 30px;">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                              </select>
                            </div>
                            <label for="dateEnd" class="col-sm-1 control-label" style="margin-left:10px"> / </label>
                            <div class="col-sm-5">
                                <select class="form-control drop-box input-sm" style="margin-top: 0px;" name="yearSemester" id="yearSemester">
                                  @foreach($yy as $y)
                                    <option value="{{$y}}" @if((date('Y')+543)==$y) selected @endif>{{$y}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="semester" class="col-sm-3 control-label">หน่วยกิต/สัปดาห์</label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control input-sm" name="credit" id="credit"  >
                      </div>
                  </div>
                    <div class="form-group">
                      <label for="dateStart" class="col-sm-3 control-label">วันที่ดำเนินการ</label>
                      <div class="col-sm-3">
                        <input   id="dateStart"  name="dateStart" class="datepicker form-control input-sm" data-date-format="mm/dd/yyyy" >
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="location" class="col-sm-3 control-label">เอกสารแนบ</label>
                        <div class="col-sm-9">
                            <div style="position:relative;margin-top: 10px;">
                            <a class='btn-file' href='javascript:;'>
                              <img src="{{url('img/clip.png')}}" alt="" width="20">
                              <input type="file"  name="file[]" id="uploadFile" multiple  style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='showLabelFile()'>
                            </a>
                            <span style="padding-left:5px;"><a href="" title="คำอธิบาย">หากประสงค์จะเลือกไฟล์มากกว่า 1 ไฟล์ต้องเลือกพร้อมกัน</a></span>
                            </div>
                          </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            <div id="tagFile" style="margin-top:20px;display:block"></div>
                        </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary" id="formSubmit">บันทึก</button>
                        <button type="reset" class="btn btn-default" onClick="resetForm()">รีเซ็ต</button>
                      </div>
                    </div>
                    <div id="list-dlt"></div>
                  </div> 
                  </div>
                {{ Form::close() }}
            </div>
            <div class="overlay" style="display:none;"   id="loader-icon">
                <div class="m-loader mr-20">
                  <svg class="m-circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/>
                  </svg>
                </div>
                <h3 class="l-text">กำลังบันทึกข้อมูล</h3>
            </div>
        </div>
      </div>
     </div>
  <script>
     $(document).ready(function(){  
        var options={
          beforeSubmit:before,
          success:afterSuccess,
          resetForm:true
      };
      $("#uploadform").submit(function(){
          $('#loader-icon').show();
          $(this).ajaxSubmit(options);
          return false;
        });
        function afterSuccess(data){
          if($.isEmptyObject(data.error)){
            $('#loader-icon').hide();
	          if($.isEmptyObject(data.errException)){
              swal("สำเร็จ!", data.success , "success");
              setTimeout(function () {
                window.location.href = "{{url('academic-dev')}}"; //will redirect to your blog page (an ex: blog.html)
              }, 2000);
            }
            else{
              swal("เกิดข้อผิดพลาด!", data.errException , "error");
            }
	        }else{
                    $('#loader-icon').hide();
	                	printErrorMsg(data.error);
	        }
          $("#formSubmit").prop('disabled', false);
          $('#tagFile').html("");
        }
        function before(){
          $("#formSubmit").attr("disabled", "disabled"); 
        }
      });

      
      function resetForm() {
        $('.datepicker').datepicker({
          format: 'dd/mm/yyyy',
          todayBtn: true,
          language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
          thaiyear: true              //Set เป็นปี พ.ศ.
      }).datepicker("setDate", "0"); 
      }
  </script>
  <script src="{{ url('js/form.myscript.js') }}"></script>
  <script type="text/javascript" src="{{url('js/plugins/sweetalert.min.js')}}"></script>
  <script src="{{ url('js/plugins/bootstrap-datepicker-custom.js') }}"></script>
  <script src="{{ url('js/plugins/bootstrap-datepicker.th.min.js') }}"></script>
  <script>
  $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", "0");  //กำหนดเป็นวันปัจุบัน
  });
  </script>
@endsection