<?php
use App\func as f;
$users = f::userList();
?>
@extends('pages.Training.index')

  
@section('subcontent')

<div class="row">
     <div class='col-md-12'>
       <div class="card">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('/training')}}" ><i class="fa fa-lg fa-table " aria-hidden="true"></i>&nbsp รายการผลงาน</a></li>
          <li role="presentation" ><a href="{{url('/training/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #FF9800;border-bottom:0px;color:#FF9800" href=""  ><i class="fa fa-lg fa-pencil-square-o fa-lg"></i>  &nbsp แก้ไข</a></li>
        </ul>
        <div class="panel panel-warning" style="margin-top:30px">
          <!-- Default panel contents -->
          <div class="panel-heading title-content"><h5 >แบบฟอร์มแก้ไขผลงานเข้ารับฝึกอบรม</h5></div>
          <div class="panel-body">
            <div  class="alert alert-danger print-error-msg" style="margin:15px 50px;display:none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <ul></ul>
              </div>
              {{ Form::open(['url' => 'training/saveEdit', 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
                <div class="col-md-12 dropdown" >
                  <input type="hidden" name="id" value="{{$task->trn_id}}" >
                  <div class="form-group">
                    <label for="taskName" class="col-sm-3 control-label">ชื่องาน</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="taskName" id="taskName" value="{{$task->trn_name}}" >
                    </div>
                  </div>
          
                  <div class="form-group">
                      <label for="dateStart" class="col-sm-3 control-label">วันที่เริ่ม</label>
                      <div class="col-sm-2">
                       <input  id="dateStart"  name="dateStart" class="datepicker form-control input-sm" data-date-format="mm/dd/yyyy" >
                      </div>
                      <label for="dateEnd" class="col-sm-1 control-label">ถึง</label>
                      <div class="col-sm-2">
                        <input  id="dateEnd"  name="dateEnd" class="datepicker form-control input-sm" data-date-format="mm/dd/yyyy" >
                      </div>
                    </div>
                  <div class="form-group">
                    <label for="location" class="col-sm-3 control-label">สถานที่</label>
                    <div class="col-sm-6">
                      <textarea class="form-control" rows="2" name="location" id="location">{{$task->trn_address}}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="location" class="col-sm-3 control-label">ผู้เข้าร่วมฝึกอบรม</label>
                    <div class="col-sm-8">
                      <div class="row">
                        <ul class="co_tch" id="teacher"><ul>
                      </div>
                      <div class="row">
                        <div class="col-sm-7">
                       <select class="form-control drop-box" name="co_tch" id="mySLT" >
                            @foreach($users as $key => $user)
                              @if($user->id!==Auth::user()->id and $user->user_level !== 'admin')
                              <option value="{{$user->id}}" >{{$user->name." ".$user->lastname}} </option>
                              @endif
                            @endforeach
                       </select>
                       
                      </div >
                      <div class="col-sm-4">   
                        <label class="btn btn-default" onclick="showSelect()">เพิ่ม</label>
                      </div>
                      <div id="tch"></div>
                      </div>
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
                      <button type="reset" class="btn btn-default" onClick="resetEditForm();" >รีเซ็ต</button>
                    </div>
                  </div>
                </div> 
                <div id="chkchange"></div>
                <div id="list-dlt"></div>
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
                window.location.href = "{{url('training')}}"; //will redirect to your blog page (an ex: blog.html)
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

      $('#changeFile').click(function(){
        $('#oldTagFile').hide();
        $('#btn-file').show();
        $('#btn-file-desc').show();
        $('#tagFile').show();
        $('#chkchange').html('<input type="hidden" name="chkchange" value="yes">');
      });
      function removeoldfile(num, id) {
          var htmlid = '#oldfile-info' + num;
          var fileId = id;
          $(htmlid).addClass('bg-gray');
          $.ajax({
              url: "{{url('deleteFile')}}/" + id,
              type: 'POST',
              data: { _token: "{{ csrf_token() }}", id: fileId },
              success: function() {
                  $(htmlid).hide();
              }
          });
          return false;
      }
      function showSelect() {
          var e = document.getElementById("mySLT");
          var value = e.options[e.selectedIndex].value;
          var text = e.options[e.selectedIndex].text;
          if (checkDuplicate(value, tarrVal)) {
              tarrVal[tarrVal.length] = value;
              tarrName[tarrName.length] = text;
          }
          setTch();
      }

      function checkDuplicate(newVal, arrVal) {
          for (var m = 0; m < arrVal.length; m++)
              if (newVal == arrVal[m]) return false;
          return true;
      }

      function setTch() {
          var resShow = "",
              hiddenText = "";
          for (var i = 0; i < tarrName.length; i++) {
            if(tarrVal[i] != '{{Auth::user()->id}}'){
              resShow += "<div class='col-sm-6'><li id='tagTch" + i + "'>" + (i + 1) + ".  " + this.tarrName[i] + "</li></div><div class='col-sm-3'><i id='icon-remove' onclick='removeTch(" + i + ")' class='fa fa-times-circle'></i></div>";
            }
          }
          $('#teacher').html(resShow);
          hiddenText = "<input type='hidden' name='coTeacher' value='" + this.tarrVal + "'>";
          $('#tch').html(hiddenText);
      }

      function removeTch(index) {
          tarrName.splice(index, 1);
          tarrVal.splice(index, 1);
          $('#tagTch' + index).hide();
          setTch();
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
            });  //กำหนดเป็นวันปัจุบัน
    var dateSt = "{{f::dateDBtoBC($task->trn_start)}}";
    var dateEnd = "{{f::dateDBtoBC($task->trn_end)}}";
    if(dateSt != ''){
      $('#dateStart').datepicker("setDate", dateSt);
    }
    if(dateEnd != ''){
      $('#dateEnd').datepicker("setDate", dateEnd);
    }
  });
  $(document).ready(function(){  
          <?php
          $cnt = 0;
            foreach($coTeacher as $key=>$sup){
            if($sup->id!=Auth::user()->id){
          ?>
            tarrVal['{{$cnt}}'] = '{{$sup->id}}';
            tarrName['{{$cnt}}'] = '{{$sup->name." ".$sup->lastname}}';
          <?php
          $cnt++;
            }
          }
          ?>
          setTch();
  });
  </script>
@endsection

  
