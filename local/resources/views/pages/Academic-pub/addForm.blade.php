<?php
use App\func as f;
use App\constantsValue as val;
$acp_cat = val::ACPCategory();
$acp_role = val::ACPRole();
?>
@extends('pages.Academic-pub.index')
  
@section('subcontent')

<div class="row">
     <div class='col-md-12'>
       <div class="card">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('/academic-pub')}}" ><i class="fa fa-lg fa-table " aria-hidden="true"></i>&nbsp รายการผลงาน</a></li>
          <li role="presentation" ><a href="{{url('/academic-pub/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #009688;border-bottom:0px;color:#009688" href=""  ><i class="fa fa-lg fa-plus-square fa-lg" aria-hidden="true"></i>  &nbsp เพิ่ม</a></li>
        </ul>
        <div class="row">
            <div class="col-md-3" style="margin-top:30px">
              <label class="title-content"><h5 >ประเภทงานเผยแพร่ทางวิชาการ</h5></label>
            </div>
        </div>
        <div class="row">
        <div class="col-md-3">
            <div class="list-group" >
                <a id="type1" href="{{url('academic-pub/add/1')}}" class="list-group-item ">ผลงานวิจัย</a>
                <a id="type2" href="{{url('academic-pub/add/2')}}" class="list-group-item ">ผลงานพัฒนาสิ่งประดิษฐ์/งานสร้างสรรค์</a>
                <a id="type3" href="{{url('academic-pub/add/3')}}" class="list-group-item ">บทความทางวิชาการ</a>
              </div>
        </div> 
        <div class="col-md-9">
        <div class="panel panel-info" >
            <!-- Default panel contents -->
            <div class="panel-heading title-content"><h5 >แบบฟอร์มเพิ่มรายการ {{val::ACPtaskCategory($type)}} </h5></div>
            <div class="panel-body">
                <div  class="alert alert-danger print-error-msg" style="margin:15px 15px;display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul></ul>
                </div>
                {{ Form::open(['url' => 'academic-pub/add/save/'.$type, 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
                  <div class="col-md-12 dropdown" >
                    <div class="form-group">
                        <label for="taskName" class="col-sm-3 control-label">ชื่อวารสาร/งานประชุม</label>
                        <div class="col-sm-6">
                                <input type="text" class="form-control " name="taskName" id="taskName" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="taskNameType" class="col-sm-3 control-label">ชนิดวารสาร</label>
                        <div class="col-sm-6">
                            <select class="form-control drop-box"  name="taskNameType">
                                <option value="" >---กรุณาระบุชนิดวารสาร---</option>
                                @foreach($acp_cat as $key => $cat)
                                @if(substr($key,0,1)==$type)
                                <option value="{{$key}}" >{{$cat['name']}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                       
                    </div>
                    <div class="form-group ">
                      <label for="title" class="col-sm-3 control-label">เรื่อง</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control " name="title" id="title" >
                      </div>
                    </div>
                    @if($type==1)
                      <div class="form-group ">
                        <label for="title" class="col-sm-3 control-label">ฐานงานเผยแพร่</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control " name="base" id="base" >
                        </div>
                      </div>
                    @endif
                    @if($type!=3)
                    <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">หน้าที่</label>
                        <div class="col-sm-6">
                              <select class="form-control drop-box"  name="role">
                                    <option value="" >---กรุณาระบุหน้าที่---</option>
                                    @foreach($acp_role as $key => $role)
                                    @if(substr($key,0,1)==$type)
                                    <option value="{{$key}}" >{{$role['name']}}</option>
                                    @endif
                                    @endforeach
                              </select>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                      <label for="dateStart" class="col-sm-3 control-label">วันที่ตอบรับ/นำเสนอ</label>
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
        </div>
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
                window.location.href = "{{url('academic-pub')}}"; //will redirect to your blog page (an ex: blog.html)
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
    var type = '{{$type}}'
    $('#type'+type).addClass('active')
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: true,
                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                thaiyear: true              //Set เป็นปี พ.ศ.
            }).datepicker("setDate", "0");  //กำหนดเป็นวันปัจุบัน
  });
  </script>
@endsection