<?php  
use App\func as f; 

$faculty = f::getFaculty();
?>


@extends('admin.dep-manage.index')
@section('subcontent')


<div class="row" id="section-content" >
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="{{url('admin/dep-manage')}}" ><i class="fa fa-lg fa-table" aria-hidden="true"></i>&nbsp ภาควิชา</a></li>
           <li role="presentation" class="active"><a style="background-color: white;border:1px solid #009688;border-bottom:0px;color:#009688" href=""  ><i class="fa fa-plus-square fa-lg" aria-hidden="true"></i>  &nbsp เพิ่ม</a></li>
        </ul>

        <div class="panel panel-success" style="margin-top:30px">
          <!-- Default panel contents -->
          <div class="panel-heading title-content"><h5>แบบฟอร์มเพิ่มข้อมูลภาควิชา</h5></div>
          <div class="panel-body">
            <div  class="alert alert-danger print-error-msg" style="margin:15px 50px;display:none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <ul></ul>
            </div>
           {{ Form::open(['url' => 'admin/dep-manage/add', 'method' => 'post','enctype'=>'multipart/form-data','id' =>'uploadform','class'=>'form-horizontal']) }}
             <div class="row">
              <div class="table-responsive">
                 <table border="0" class="table tb-addUser" style="width:75%!important" align="center">
                 <tr>
                  
                  <td  style="text-align:right;width:120px">ชื่อภาควิชา</td>
                  <td >
                    <input type="text"  name="dep_name" class="form-control input-sm" >
                  </td>
                  <td  style="text-align:right;width:80px;" >คณะ</td>
                  <td >
                   <select class="form-control drop-box"  name="faculty_id" style="width:80%;margin-top:0px;">
                          <option value="">--- เลือกคณะ ---</option>
                          @foreach($faculty as $key => $fac)
                            <option value="{{$fac->faculty_id}}" >{{$fac->faculty_id}} {{$fac->faculty_name}}</option>
                          @endforeach  
                    </select>
                  </td>
                 </tr>
                 
                 <tr>
                  <td colspan="4" class="text-center">
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
  </div>    
  <div class="row text-center" style="padding-top:10%" id="page-loading">
    <img src="{{url('img/loading-page1.gif')}}" alt="" width="200">
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
                window.location.href = "{{url('admin/dep-manage')}}"; //will redirect to your blog page (an ex: blog.html)
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


  <script>
      $(document).ready(function(){  
      //$('#head_sort').click();
      $('#page-loading').remove();
      $('#section-content').show();
      })
    </script>

  @endsection
