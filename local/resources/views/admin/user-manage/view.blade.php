<?php  use App\func as f; ?>
@extends('admin.user-manage.index')

@section('subcontent')
<div class="row" id="section-content" style="display:none">
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('admin/user-manage')}}" ><i class="fa fa-table" aria-hidden="true"></i> รายการผู้ใช้</a></li>
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #009688;border-bottom:0px;color:#009688" onClick="refresh()" ><i class="fa fa-address-book-o" aria-hidden="true"></i> ข้อมูลผู้ใช้</a></li>
        </ul>


        <div class="panel panel-success" style="margin-top:30px">
          <!-- Default panel contents -->
          <div class="panel-heading title-content"><h5>ข้อมูลผู้ใช้งาน</h5></div>
          <div class="panel-body">
             <div><h5>ข้อมูลผู้ใช้ : {{$user->name." ".$user->lastname}}</h5></div>
             <div class="row">
              <div class="col-sm-12"> 
                 <table  class="table table-bordered">
                  <tr>
                  <td>คำนำหน้าชื่อ</td><td>{{$user->title_name}}</td>
                 </tr>
                 <tr>
                  <td>ชื่อ</td><td>{{$user->name}}</td>
                 </tr>
                 <tr>
                  <td>นามสกุล</td><td>{{$user->lastname}}</td>
                 </tr>
                 <tr>
                  <td>อีเมล</td><td>{{$user->email}}</td>
                 </tr>
                 <tr>
                  <td>ตำแหน่ง</td><td>{{$user->user_position_name}}</td>
                 </tr>
                 <tr>
                  <td>สังกัด</td><td>{{f::getDeparmentName($user->department_id)}}</td>
                 </tr>
                 <tr>
                  <td>คณะ</td><td>{{f::getFacultyName($user->department_id)}}</td>
                 </tr>
                 <tr>
                  <td>มหาวิทยาลัย</td><td>{{f::getInstitutionName()}}</td>
                 </tr>
                 <tr>
                  <td>สถานะผู้ใช้</td><td>{{$user->user_level}}</td>
                 </tr>
                 </table>
              </div>
            </div>
            <!--<div class="row">
              <div class="col-sm-12">
              <h5>ผลงาน</h5>
                <div class='col-md-3'>
                  <div class="card">
                    <a href=""><i class="fa fa-file" aria-hidden="true"></i> งานบริการวิชาการและอื่นๆ</a>
                  </div>
                </div>
                <div class='col-md-3'>
                  <div class="card">
                    <a href=""><i class="fa fa-file" aria-hidden="true"></i> งานเข้ารับฝึกอบรม</a>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="row">
              <div class="col-sm-12 text-center"> 
                <a href="{{url('admin/user-manage')}}" class="btn btn-primary">กลับ</a>
              </div>
            </div>
          </div>
        </div>
      </div>
     </div>
  </div>
  <div class="row text-center" style="padding-top:10%" id="page-loading">
    <img src="{{url('img/loading-page1.gif')}}" alt="" width="200">
  </div>

<!-- modal confirm delete -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">ลบ</h4>
      </div>
      <div class="modal-body">
      <p id="msg"></p>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">ยกเลิก</button>
        <button type="button" id="btn-confirm" class="btn btn-default">ลบ</button>
      </div>
    </div>
  </div>
</div>
</div>

<!-- modal view detail -->
  <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">รายละเอียด</h4>
      </div>
      <div class="modal-body">
      <p id="msg"></p>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
        
      </div>
    </div>
  </div>
</div>
</div>
  

<script src="{{ url('js/modal.script.js') }}"></script>
<script>
$(document).ready(function(){  
  //$('#head_sort').click();
  $('#page-loading').remove();
  $('#section-content').show();
})
</script>

@endsection
