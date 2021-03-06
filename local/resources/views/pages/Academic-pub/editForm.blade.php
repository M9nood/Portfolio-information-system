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
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #FF9800;border-bottom:0px;color:#FF9800" href=""  ><i class="fa fa-lg fa-pencil-square-o fa-lg"></i>  &nbsp แก้ไข</a></li>
        </ul>
        <div class="row">
            <div class="col-md-3" style="margin-top:30px">
              <label class="title-content"><h5 >ประเภทงานเผยแพร่ทางวิชาการ</h5></label>
            </div>
        </div>
        <div class="row">
        <div class="col-md-3">
            <div class="list-group" >
                <a  id="type{{$task->acp_task_type}}" onclick="window.location.reload()" class="list-group-item">{{val::ACPtaskCategory($task->acp_task_type)}}</a>
              </div>
        </div> 
        <div class="col-md-9">
        <div  class="panel panel-warning" >
            <!-- Default panel contents -->
            <div class="panel-heading title-content"><h5 >แบบฟอร์มแก้ไขรายการ {{val::ACPtaskCategory($task->acp_task_type)}} </h5></div>
            <div class="panel-body">
                <div  class="alert alert-danger print-error-msg" style="margin:15px 50px;display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul></ul>
                </div>
                {{ Form::open(['url' => 'academic-pub/saveEdit/'.$task->acp_task_type, 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
                  <div class="col-md-12 dropdown" >
                      <input type="hidden" name="id" value="{{$task->acp_id}}" >
                    <div class="form-group">
                          <label for="taskName" class="col-sm-3 control-label">ชื่อวารสาร/งานประชุม</label>
                          <div class="col-sm-6">
                                  <input type="text" class="form-control " name="taskName" id="taskName" value="{{$task->acp_name}}">
                          </div>
                    </div>
                    <div class="form-group">
                       
                        <label for="taskNameType" class="col-sm-3 control-label">ชนิดวารสาร</label>
                        <div class="col-sm-6">
                            <select class="form-control drop-box"  name="taskNameType">
                                <option value="" >---กรุณาระบุชนิดวารสาร---</option>
                                @foreach($acp_cat as $key => $cat)
                                @if(substr($key,0,1)==$task->acp_task_type)
                                <option value="{{$key}}" @if($task->acp_category==$key) selected  @endif>{{$cat['name']}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="title" class="col-sm-3 control-label">เรื่อง</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control " name="title" id="title" value="{{$task->acp_title}}" >
                        </div>
                      </div>
                      @if($task->acp_task_type==1)
                        <div class="form-group ">
                          <label for="title" class="col-sm-3 control-label">ฐานงานเผยแพร่</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control " name="base" id="base" value="{{$task->acp_base}}" >
                          </div>
                        </div>
                      @endif
                    @if($task->acp_task_type!=3)
                    <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">หน้าที่</label>
                        <div class="col-sm-6">
                              <select class="form-control drop-box"  name="role">
                                    <option value="" >---กรุณาระบุหน้าที่---</option>
                                    @foreach($acp_role as $key => $role)
                                    @if(substr($key,0,1)==$task->acp_task_type)
                                    <option value="{{$key}}" @if($task->acp_user_role==$key) selected  @endif >{{$role['name']}}</option>
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
                            <a class='btn-file'  href='javascript:;' id="btn-file" style="@if(isset($documents)) display:none @endif" >
                              <img src="{{url('img/clip.png')}}" alt="" width="20">
                              <input type="file"  name="file[]" id="uploadFile" multiple  style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' size="40"  onchange='showLabelFile()'>
                            </a>
                            <span id="btn-file-desc"  style="padding-left:5px;@if(isset($documents)) display:none @endif"><a href="" title="คำอธิบาย">หากประสงค์จะเลือกไฟล์มากกว่า 1 ไฟล์ต้องเลือกพร้อมกัน</a></span></div>
                            <div id="oldTagFile" style="display:block">
                            @for($i=0;$i<count($documents);$i++)
                              <div style="margin-bottom:6px;display:inline-block">
                                <span class='label label-info label-tag' id='oldfile-info{{$i}}'>{{$documents[$i]->doc_name}} 
                                <i id='icon-remove' style='margin-left:4px' onClick='removeoldfile({{$i}},"{{$documents[$i]->doc_id}}" )'  title='ลบ' class='fa fa-times-circle'></i>
                                </span>
                              </div>
                            @endfor
                            <label for="oldTagFile" class="btn btn-default btn-xs" id="changeFile">เปลี่ยน</label>
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
                    <div id="chkchange"></div>
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
    var list_dlt=[];
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

     function showLabelFile(){
       list_dlt=[];
       var len = document.getElementById('uploadFile').files.length;
       var res='';
       for(var i =0 ;i<len;i++){
         res += "<div style='margin-bottom:5px;display:inline-block'>"
                 +"<span class='label label-info label-tag' id='upload-file-info"+i+"'>"+$('#uploadFile').get(0).files[i].name +"<i id='icon-remove' style='margin-left:4px' onClick='removefile("+i+")' title='ลบ' class='fa fa-times-circle'></i></span>"
               +"</div>";
       }
       $('#tagFile').html(res);
       $('#list-dlt').html('');
     }

     function removefile(order){
       var htmlid = '#upload-file-info'+order;
       $(htmlid).hide();
       var len=list_dlt.length;
       list_dlt[len] = order;
       console.log(list_dlt[len]);
       $('#list-dlt').html("<input type='hidden' name='dltFile' value='"+list_dlt+"'>");
     };

      function removeoldfile(num,id){
        var htmlid = '#oldfile-info'+num;
        var fileId = id;
        $(htmlid).addClass('bg-gray');
        $.ajax({
           url: "{{url('deleteFile')}}/"+id,
           type: 'POST',
           data: { _token: "{{ csrf_token() }}",id: fileId },
           success: function() {
               $(htmlid).hide();
           }
       });
       return false; 
      }  
     function printErrorMsg (msg) {
       $(".print-error-msg").find("ul").html('');
       $(".print-error-msg").css('display','block');
       $.each( msg, function( key, value ) {
         $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
       });
     }

     $('#changeFile').click(function(){
       $('#oldTagFile').hide();
       $('#btn-file').show();
       $('#btn-file-desc').show();
       $('#tagFile').show();
       $('#chkchange').html('<input type="hidden" name="chkchange" value="yes">');
     });
 </script>
 <script src="{{ url('js/form.myscript.js') }}"></script>
 <script type="text/javascript" src="{{url('js/plugins/sweetalert.min.js')}}"></script>
 <script src="{{ url('js/plugins/bootstrap-datepicker-custom.js') }}"></script>
 <script src="{{ url('js/plugins/bootstrap-datepicker.th.min.js') }}"></script>
 <script>
 $(document).ready(function () {
  var type = '{{$task->acp_task_type}}'
  $('#type'+type).addClass('active')
           $('.datepicker').datepicker({
               format: 'dd/mm/yyyy',
               todayBtn: true,
               language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
               thaiyear: true              //Set เป็นปี พ.ศ.
           });  //กำหนดเป็นวันปัจุบัน
   var dateSt = "{{f::dateDBtoBC($task->acp_proceed_date	)}}";
   if(dateSt != ''){
     $('#dateStart').datepicker("setDate", dateSt);
   }

 });
  </script>
@endsection