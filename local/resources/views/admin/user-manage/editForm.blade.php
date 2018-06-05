<?php  
use App\func as f; 
use App\constantsValue as val;

$position = val::getUserPosition();
$department = f::getDepartment();
$faculty = f::getFaculty();
$level = val::userLevel();
$isother = f::isOtherPosition($user->user_position_name);
$isotherttname = f::isOtherTitleName($user->title_name);
$ttName = val::getTitleName();

?>

@extends('admin.user-manage.index')
@section('subcontent')

<div class="row" id="section-content" >
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('admin/user-manage')}}" ><i class="fa fa-lg fa-table" aria-hidden="true"></i> ตารางผู้ใช้</a></li>
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #FF9800;border-bottom:0px;color:#FF9800" href=""  ><i class="fa fa-lg fa-pencil-square-o fa-lg" aria-hidden="true"></i>  &nbsp แก้ไข</a></li>
        </ul>

        <div class="panel panel-warning" style="margin-top:30px">
          <!-- Default panel contents -->
          <div class="panel-heading title-content"><h5>แบบฟอร์มแก้ไขข้อมูลผู้ใช้งาน</h5></div>
          <div class="panel-body">
            <div  class="alert alert-danger print-error-msg" style="margin:15px 50px;display:none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <ul></ul>
            </div>
           {{ Form::open(['url' => 'admin/user-manage/saveedit', 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
             <div class="row">
              <input type="hidden" name="id" value="{{$user->id}}" >
              <div class="col-md-12"> 
                 <table border="0" class="table tb-addUser" >
                 <tr>
                  <td width="35%" style="text-align:right">คำนำหน้าชื่อ</td>
                  <td>
                     <div class="col-sm-4" style="padding-left:0px">
                      <select class="form-control drop-box" style="margin-top:0px"  name="ttname" id="chkttname">
                            @foreach($ttName as $key => $ttName)
                              <option value="{{$key}}" @if ($ttName == $user->title_name) selected @endif>{{$ttName}}</option>
                            @endforeach 
                      </select>
                      <div style="margin-top:10px;">
                        <div style="white-space:nowrap;" class="animated-checkbox">
                              <label>
                                <input type="checkbox" id="chkotherttname" name="otherttname" onclick="actOtherttname();"/>
                                <span class="label-text">อื่นๆ</span>
                              </label>
                          </div>
                          <input type="text" name="otherttnametxt" id="otherttnametxt" class="form-control input-sm" placeholder="ระบุคำนำหน้าชื่อ" style="display:none" >
                        </div>
                    </div>
                  </td>
                 </tr>
                 <tr>
                  <td  style="text-align:right" >ชื่อ</td>
                  <td>
                    <input type="text"  name="name" class="form-control input-sm" value="{{$user->name}}" style="width:30%">
                  </td>
                 </tr>
                 <tr>
                  <td style="text-align:right">นามสกุล</td>
                  <td><input type="text" name="lname" class="form-control input-sm" value="{{$user->lastname}}" style="width:30%"></td>
                 </tr>
                 <tr>
                  <td  style="text-align:right">ชื่อย่อภาษาอังกฤษ</td>
                  <td>
                    <input type="text"  name="shortname"  class="form-control input-sm" value="{{$user->short_name_en}}" style="width:15%" >
                  </td>
                 </tr>
                 <tr>
                  <td style="text-align:right">อีเมล</td>
                  <td>
                    <div class="form-inline">
                      <input type="email" id="email" name="email" class="form-control input-sm" value="{{$user->email}}" style="width:30%">
                      <span style="padding-left:8px;font-size:12px;" id="checkAvailable"></span>
                    </div>
                  </td>
                 </tr>
                 <tr>
                  <td style="text-align:right">ตำแหน่ง</td>
                  <td >
                    <div class="col-sm-4" style="padding-left:0px">
                      <select class="form-control drop-box"  name="position" id="chkPosition">
                          @foreach($position as $key => $pos)
                            <option value="{{$key}}"  @if ($pos == $user->user_position_name) selected @endif>{{$pos}}</option>
                          @endforeach 
                      </select>
                      <div style="margin-top:10px;">
                       <div style="white-space:nowrap;" class="animated-checkbox">
                            <label>
                              <input type="checkbox" id="chkother" name="other" onclick="actOther();"/>
                              <span class="label-text">อื่นๆ</span>
                            </label>
                        </div>
                        <input type="text" name="otherPostxt" id="otherPostxt" class="form-control input-sm" placeholder="ระบุตำแหน่ง" style="display:none" >
                      </div>
                      
                    </div>
                  </td>
                 </tr>
                 <tr>
                  <td style="text-align:right">สังกัดภาควิชา</td>
                  <td >
                    <select class="form-control drop-box"  name="department" style="width:50%">
                          @foreach($department as $key => $dp)
                            <option value="{{$dp->department_id}}" @if ($dp->department_id ==$user->department_id) selected  @endif> {{$dp->department_name}}</option>
                          @endforeach  
                    </select>
                  </td>
                 </tr>
                 <tr>
                  <td style="text-align:right">คณะ</td>
                  <td>
                    <select class="form-control drop-box"  name="faculty" style="width:50%">
                          @foreach($faculty as $key => $fac)
                            <option value="{{$fac->faculty_id}}" @if ($fac->faculty_id == f::getFacultyId($user->department_id)) selected @endif> {{$fac->faculty_name}}</option>
                          @endforeach  
                    </select>
                  </td>
                 </tr>
                 
                 <tr>
                  <td style="text-align:right">สถานะผู้ใช้</td>
                  <td>
                    <select class="form-control drop-box" id="ulevel" onchange="$('#isadmSTP').removeAttr('checked');"  name="userlevel" style="width:30%">
                          <option value="">--- เลือกสถานะผู้ใช้ ---</option>
                          @foreach($level as $key => $level)
                            <option value="{{$key}}" @if ($key == $user->user_level) selected @endif>{{$level}}</option>
                          @endforeach  
                    </select>
                  </td>
                 </tr>
                 <tr>
                  <td></td>
                  <td>
                   <div style="white-space:nowrap;" class="animated-checkbox">
                     <label>
                       <input type="checkbox" id="isadmSTP" name="isadmSTP" value="yes" @if($user->isadm_stp=="yes") checked @endif />
                       <span class="label-text">สิทธิ์เป็นเจ้าหน้าที่ของภาควิชา</span>
                     </label>
                 </div>
                  </td>
                </tr>
                 <tr>
                  <td style="text-align:right"></td>
                  <td>
                    <button type="submit" class="btn btn-primary" id="formSubmit">บันทึก</button>
                    <button type="reset" class="btn btn-default" onClick="$('#checkAvailable').html('');$('#formSubmit').prop('disabled', false);">รีเซ็ต</button>
                  </td>
                 </tr>
                 </table>
                  
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
                window.location.href = "{{url('admin/user-manage')}}"; //will redirect to your blog page (an ex: blog.html)
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

      $('#isadmSTP').click(function() {
        if ($(this).is(':checked')) {
          if($("#ulevel").val() !="support"){
            $('#isadmSTP').removeAttr('checked');
            alert("ผู้ที่สามารถเพิ่มผลงานนักศึกษาต้องเป็นผ่ายสนับสนุนเท่านั้น")
          }
        }
      }); 
      
      $(document).ready(function(){  
        $('#email').blur(function(){
          var email =$(this).val();
          $.ajax({
            url: "{{url('admin/checkOriginal-email')}}/{{$user->id}}",
            type: 'POST',
            dataType: 'JSON',
            data: {  _token: "{{ csrf_token() }}",email: email },
            success: function(data) {
                if($.isEmptyObject(data.error)){
                  $('#checkAvailable').html(data.success);
                  $("#formSubmit").prop('disabled', false);
                }else{
                  $('#checkAvailable').html(data.error);
                  $("#formSubmit").attr("disabled", "disabled"); 
                }
            }
          });
        });
      });

      $(document).ready(function(){  
        if("{{$isother}}" == 1){
          $('#chkother').click()
          $('#otherPostxt').val('{{$user->user_position_name}}')
        }
        if("{{$isotherttname}}" == 1){
          $('#chkotherttname').click()
          $('#otherttnametxt').val('{{$user->title_name}}')
        }
        console.log('{{$isother}}')
        
      });


      function actOther(){
        if (document.getElementById('chkother').checked) 
        {
            $("#chkPosition").val('')
            document.getElementById("chkPosition").disabled=true;
            $('#otherPostxt').show()
        } else {
            document.getElementById("chkPosition").disabled=false;
            $('#otherPostxt').hide()
        }
        
      }
       function actOtherttname(){
        if (document.getElementById('chkotherttname').checked) 
        {
            $("#chkttname").val('')
            document.getElementById("chkttname").disabled=true;
            $('#otherttnametxt').show()
        } else {
            document.getElementById("chkttname").disabled=false;
            $('#otherttnametxt').hide()
        }
        
      }

       function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
          $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        });
      }

     
  </script>
  <script type="text/javascript" src="{{url('js/plugins/sweetalert.min.js')}}"></script>
  <script src="{{ url('js/form.myscript.js') }}"></script>

@endsection
