<?php  
use App\func as f; 
?>
@extends('admin.fac-manage.index')
@section('subcontent')


<div class="row" id="section-content" >
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="{{url('admin/fac-manage')}}" ><i class="fa fa-lg fa-table" aria-hidden="true"></i>&nbsp คณะ</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="home">
            <div class="">
              <div class="title-content">
                <h5>รายการคณะ</h5>
              </div>
            </div>
        
                <div class="pull-right">
                  <a class="btn btn-primary btn-flat" data-toggle="tooltip" title="เพิ่ม" href="{{url('admin/fac-manage/add')}}"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" data-toggle="tooltip" title="รีเฟรช" onClick="refresh()"><i class="fa fa-lg fa-refresh"></i></a>
                </div>
              
                <div class="row">
                  <div class="col-sm-12" >
                    <div class="card">
                      <div class="card-body " >
                      <div class="table-responsive">
                        <table align="center" class="table table-hover table-bordered"  id="sampleTable" >
                          <thead>
                            <tr style="background-color:#8cb3d9">
                              <th class="text-center" width="20">#</th>
                              <th class="text-center" width="100">รหัสคณะ</th>
                              <th class="text-center" width="">ชื่อคณะ</th>
                              <th width="80"></th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach($fac as $key=> $fac)
                            <tr>
                              <td class="text-center">{{$key+1}}</td>
                              <td>{{$fac->faculty_id}}</td>
                              <td>{{$fac->faculty_name}}</td>
                              <td class="text-center">
                                <a class="btn btn-warning btn-xs" style="padding:5px"  title="แก้ไข"  href="{{url('/admin/fac-manage/edit/'.$fac->faculty_id)}}"><i class="fa fa-lg fa-pencil-square-o"></i></a>
                                <a class="btn btn-danger btn-xs "  style="padding:5px"  title="ลบ" data-toggle="modal" data-target="#confirmDeleteModal2" data-name="{{$fac->faculty_name}}" data-id="{{$fac->faculty_id}}"><i class="fa fa-lg fa-trash-o"></i></a>
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

<script src="{{ url('js/modal.script.js') }}"></script>
<script>
$(document).ready(function(){  
  //$('#head_sort').click();
  $('#page-loading').remove();
  $('#section-content').show();
})
</script>


  @endsection
