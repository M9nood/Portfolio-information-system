<?php  use App\func as f; ?>
@extends('admin.user-manage.index')

@section('subcontent')
<div class="row" id="section-content" style="display:none">
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a onClick="refresh()" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-lg fa-table" aria-hidden="true"></i>&nbsp ตารางผู้ใช้</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="home">
            <div class="">
              <div id="title-content">
                <h5>ตารางแสดงผู้ใช้งาน</h5>
              </div>
              <div class="pull-right">
                <a class="btn btn-primary btn-flat" data-toggle="tooltip" title="เพิ่ม" href="{{url('admin/user-manage/add')}}"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" data-toggle="tooltip" title="รีเฟรช" onClick="refresh()"><i class="fa fa-lg fa-refresh"></i></a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="sampleTable" >
                      <thead>
                        <tr style="background-color:#8cb3d9">
                          <th width="10">#</th>
                          <th width="">ชื่อ - สกุล</th>
                          <th width="">อีเมล</th>
                          <th width="">ตำแหน่ง</th>
                          <th width="">สาขา</th>
                          <th width="">สถานะ</th>
                          <th width=""></th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($users as $key => $user)
                            <tr>
                              <td>{{$key+1}}</td>
                              <td>{{$user->name." ".$user->lastname}}</td>
                              <td>{{$user->email}}</td>
                              <td>{{$user->user_position_name}}</td>
                              <td>{{f::getDeparmentName($user->department_id)}}</td>
                              <td>{{$user->user_level}}</td>
                              <td style="text-align:center">
                                <a class="btn btn-info btn-xs" style="padding:5px;"  title="รายละเอียด"  href="{{url('/admin/user-manage/view/'.$user->id)}}"><i class="fa fa-lg fa-info-circle"></i></a>
                                <a class="btn btn-warning btn-xs" style="padding:5px;"  title="แก้ไข"  href="{{url('/admin/user-manage/edit/'.$user->id)}}"><i class="fa fa-lg fa-pencil-square-o"></i></a>
                                <a class="btn btn-danger btn-xs "  style="padding:5px;"  title="ลบ" data-toggle="modal" data-target="#confirmDeleteModal2" data-name="{{$user->name}}" data-id="{{$user->id}}"><i class="fa fa-lg fa-trash-o"></i></a>
                              </td>
                            </tr>
                      @endforeach
                      </tbody>
                    </table>
                    </div>
                  </div>
                </div>
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
  <div class="modal fade" id="confirmDeleteModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog dlt" role="document">
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
