<?php
use App\func as f;
$users = f::userList();
?>
@extends('pages.Student-port.index')

@section('subcontent')

<div class="row">
  <div class='col-md-12'>
    <div class="card">

     <!-- Nav tabs -->
     <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" ><a href="{{url('/std-portfolio')}}" ><i class="fa fa-table" aria-hidden="true"></i> ตารางผลงานนักศึกษา</a></li>
      <li role="presentation"><a href="{{url('/std-portfolio/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>  
      <li role="presentation" class="active"><a style="background-color: white;border:1px solid #FF9800;border-bottom:0px;color:#FF9800" href=""  ><i class="fa fa-pencil-square-o fa-lg"></i>  &nbsp แก้ไข</a></li>
    </ul>

    <div class="panel panel-warning" style="margin-top:30px">
        <!-- Default panel contents -->
        <div class="panel-heading title-content"><h5 >แบบฟอร์มแก้ไขรายการผลงานนักศึกษา</h5></div>
        <div class="panel-body">
          <div  class="alert alert-danger print-error-msg" style="margin:15px 50px;display:none">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <ul></ul>
            </div>
             {{ Form::open(['url' => '/std-portfolio/saveEdit', 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
               <div class="col-md-12 dropdown" >
                 <div class="form-group">
                   <label for="taskName" class="col-sm-3 control-label">เรื่อง <span class="important">*</span></label>
                   <div class="col-sm-7">
                     <input type="text" class="form-control" name="taskName" id="taskName" value="{{$task->stp_name}}">
                   </div>
                 </div>
                 <div class="form-group">
                   <label for="stp_desc" class="col-sm-3 control-label">รายละเอียด</label>
                   <div class="col-sm-7">
                     <textarea class="form-control" rows="3" name="stp_desc" id="stp_desc">{{$task->stp_description}}</textarea>
                   </div>
                 </div>
                 <div class="form-group">
                   <label for="award" class="col-sm-3 control-label">รางวัลที่ได้รับ</label>
                   <div class="col-sm-7">
                     <input type="text" class="form-control " name="award" id="award" value="{{$task->award}}" >
                   </div>
                 </div>
                 <div class="form-group">
                   <label for="student" class="col-sm-3 control-label">รายชื่อนักศึกษา <span class="important">*</span></label>
                   <div class="col-sm-7">
                     <div class="row"  id="divStd" style="display:none">
                       <ul class="co_tch" style="list-style-type: none;" id="stdlisthtml"><ul>
                     </div>
                     
                     <!-- show first -->
                     <div class="row form-control" id="change-std-btn" style="margin:0px">
                        <div class="col-sm-7 text-center" >
                            <label class="btn btn-success" onclick='window.open("{{url('std/'.$task->stp_id)}}", "_blank", "toolbar=yes,width=450,height=500");'>ดูรายชื่อ</label>
                        </div>
                        <div class="col-sm-1 pull-right" style="padding-left:0px;padding-right:0px">
                            <label class="btn btn-warning" onclick="changeStd()"><i class="fa fa-lg fa-edit" title="เปลี่ยน"></i></label>
                        </div>
                     </div>
                     <!-- show when click change -->
                     <div class="row" id="text-std" style="display:none">
                        <div class="col-sm-7">
                          <input type="text" class="form-control input-sm" name="student" id="student" >
                        </div>
                        <div class="col-sm-2">
                            <label class="btn btn-default" onclick="addstdName()">เพิ่ม</label>
                        </div>
                        <div class="col-sm-3" valign="middle" >
                          <label class="btn btn-success" onclick="showImportCSVBtn()">นำเข้ารายชื่อ</label>
                        </div>
                        <div id="stdList"></div>  
                      </div>
                     <div class="row" id="import-std" style="display:none" >
                       <div class="col-sm-7">
                         <input type="file" class="form-control "  name="csv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  >
                         <span><a data-target="#importExample" data-toggle="modal"  >ตัวอย่างการนำเข้า</a></span>
                        </div>
                       <div class="col-sm-3" valign="middle" >
                         <label class="btn btn-success" onclick="showInputStdBtn()">เพิ่มรายชื่อ</label>
                       </div>
                       <div id="stdList"></div>  
                     </div>
                 </div>
               </div>
               <div class="form-group">
                   <label for="co_tch" class="col-sm-3 control-label">บุคลาการผู้ควบคุม <span class="important">*</span></label>
                   <div class="col-sm-7">
                     <div class="row" id='divTeacher' style="display:none">
                       <ul class="co_tch"  id="teacher"><ul>
                     </div>
                     <div class="row">
                     <div class="col-sm-7">
                     <select class="form-control drop-box"  id="mySLT" >
                           @foreach($users as $key => $user)
                             @if($user->id!==Auth::user()->id and $user->user_level !== 'admin' )
                             <option value="{{$user->id}}" >{{$user->name." ".$user->lastname}} </option>
                             @endif
                           @endforeach
                     </select>   
                     </div >
                     <div class="col-sm-2">   
                       <label class="btn btn-default" onclick="showSelect()">เพิ่ม</label>
                     </div>
                     <div id="tch"></div>
                     </div>
                   </div>             
                 </div>
                 <div class="form-group">
                   <label for="dateStart" class="col-sm-3 control-label">วันที่ดำเนินการ</label>
                   <div class="col-sm-3">
                     <input   id="dateStart"  name="dateStart" class="datepicker form-control input-sm" data-date-format="mm/dd/yyyy" >
                   </div>
                 </div>
                 <div class="form-group">
                  <label for="album" class="col-sm-3 control-label">อัลบั้ม</label>
                  <div class="col-sm-2" id="div-filealbum" style="display:none" >
                      <div style="position:relative;margin-top: 10px;"  >
                          <div class='btn-album'  href='javascript:;'>
                            <img src="{{url('img/add-album.png')}}" style="z-index:-1" onClick="$('#uploadFile').click()" alt="" width="90">
                            <div id="tagFile" align="center" style="display:block"></div>
                            <p class="text-danger text-center" id="alert-image-album"></p>
                            <input type="file"  name="file[]" id="uploadFile" multiple  style='position:absolute;top:0;z-index:2;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' 
                            accept="image/x-png,image/gif,image/jpeg" size="40"  onchange='handleFiles(this.files)'>
                          </div>
                      </div>  
                  </div>
                  <div class=" col-sm-3" id="div-album" >
                      <div class="floated album-card">
                        <a class="limited">
                          <img style="" src="{{f::getImageAlbum($task->album_id)}}" width="100%"/>   
                          <i id='del-album' onClick="delAlbum()"  class='fa fa-times-circle fa-2x'></i>      
                        </a>
                        <div class="album-desc">
                            <label>{{f::cutStr(f::albumName($task->album_id),30,'...')}}</label><br>
                            <span>{{count($images)}} รูป</span>
                        </div>
                      </div>
                  </div>
              </div>
               <div class="form-group">
                   <div class="col-sm-offset-3 col-sm-9">
                     <button type="submit" class="btn btn-primary" id="formSubmit">บันทึก</button>
                     <button type="reset" class="btn btn-default" onClick="window.location.reload()">รีเซ็ต</button>
                   </div>
               </div> 
                 <input type="hidden" name="id" value="{{$task->stp_id}}" >
                 <div id="list-dlt"></div>
                 <input type="hidden" id="changeAlbum" name="changeAlbum" value="no">
                 <input type="hidden" id="import_csv" name="import_csv" value="no">
                 <input type="hidden" id="change_std" name="change_std" value="no">
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

    <!-- modal view file detail -->
    <div class="modal fade" id="importExample" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog viewfile-modal-dialog "   role="document" >
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="exampleModalLabel">ตัวอย่างการนำเข้ารายชื่อ</h4>
          </div>
          <div class="modal-body viewfile-modal-body" id="scroll">
          <p>1. เลือกไฟล์ นามสกุล <B>".csv"</B> ในการนำเข้า</p>
          <p>2. ภายในไฟล์ประกอบด้วยคอลัมน์ที่1 คือ <b>"ลำดับ"</b> และคอลัมน์ที่2 คือ <b>  "ชื่อ - สกุล"</b></p>
          <p ><img src="{{url('img/stp-example1.png')}}" alt=""></p>
          <p>3. กด <b>"บันทึก"</b></p>
        </div>
        <hr>
        <div class="modal-footer" style="border-top:0px">
            <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
          </div>
      </div>
    </div>
    </div>
  
  <script>
    var stdName = []
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
                window.location.href = "{{url('/std-portfolio')}}"; //will redirect to your blog page (an ex: blog.html)
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
      function labelAlbum(){
        var res = '';
        res += "<div style='margin-bottom:5px;display:inline-block'>" +
            "<span class='label label-info label-tag' id='upload-file-info0'>" +$('#uploadFile').get(0).files.length + " รูปภาพ<i id='icon-remove' style='margin-left:4px' onClick='removefile(0)' title='ลบ' class='fa fa-times-circle'></i></span>" +
            "</div>";
        $('#tagFile').html(res);
        $('#list-dlt').html('');
      }

      function changeStd(){
        $('#change-std-btn').hide()
        $('#text-std').show()
        $('#change_std').val("yes")
      }

      function addstdName(){
        
        var name = $('#student').val()
        if(name!=""){
        $('#divStd').show()
        var dub = false
        for(var i = 0; i < stdName.length; i++) {
          if(name.replace(/\s/g, '')===stdName[i].replace(/\s/g, '')) dub = true
        }
        if(!dub) stdName[stdName.length] = name
        $('#student').val('')
        stdList()
        }
        
      }
      function stdList(){
        var resShow = "",
              hiddenText = "";
          for (var i = 0; i < stdName.length; i++) {
              resShow += "<div class='col-sm-6'><li id='tagStd" + i + "'>" + (i + 1) + ".  " + this.stdName[i] + "</li></div><div class='col-sm-1'><i id='icon-remove' onclick='removeStd(" + i + ")' class='fa fa-times-circle'></i></div>";
          }
          $('#stdlisthtml').html(resShow);
          hiddenText = "<input type='hidden' name='stdList' value='" + this.stdName + "'>";
          $('#stdList').html(hiddenText);
          $('#divStd').show()
      }
      function removeStd(index){
          stdName.splice(index, 1);
          $('#tagStd' + index).hide();
          if(stdName.length==0) $('#divStd').hide() 
          stdList()
          
      }
      function showImportCSVBtn(){
          $('#text-std').hide()
          stdName = []
          stdList()
          $('#import_csv').val("yes")
          $('#import-std').show()
      }
      function showInputStdBtn(){
        $('#import-std').hide()
        $('#import_csv').val("no")
        $('#text-std').show()
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
    var dateSt = "{{f::dateDBtoBC($task->stp_proceed_date	)}}";
    if(dateSt != ''){
        $('#dateStart').datepicker("setDate", dateSt);
    }
  });

  $(document).ready(function(){  
      <?php
        foreach($superviser as $key=>$sup){
      ?>
        tarrVal['{{$key}}'] = '{{$sup->id}}';
        tarrName['{{$key}}'] = '{{$sup->name." ".$sup->lastname}}';
      <?php
        }
      ?>
      showSelect();
  });

  </script>
@endsection