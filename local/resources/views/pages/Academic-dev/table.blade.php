
<?php  use App\func as f;
use App\constantsValue as val;
?>
@extends('pages.Academic-dev.index')

@section('subcontent')

<div class="row" id="section-content" style="display:none">
     <div class='col-md-12'>
       <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a onClick="refresh()" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-lg fa-table" aria-hidden="true"></i>&nbsp รายการผลงาน</a></li>
          <li role="presentation"><a href="{{url('/academic-dev/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="home">
            <div class="">
              <div class="title-content" >
                <h5>รายการผลงานพัฒนาวิชาการของ : {{Auth::user()->name." ".Auth::user()->lastname}}</h5>
              </div>
              <div class="pull-right">
                <a class="btn btn-primary btn-flat" data-toggle="tooltip" title="เพิ่ม" href="{{url('/academic-dev/add')}}"><i class="fa fa-lg fa-plus"></i></a><a class="btn btn-info btn-flat" data-toggle="tooltip" title="รีเฟรช" onClick="refresh()"><i class="fa fa-lg fa-refresh"></i></a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-body ">
                    <div class="table-responsive">
                    <table class="table table-hover table-bordered " id="sampleTable" >
                      <thead>
                        <tr style="background-color:#8cb3d9">
                          {{--  <th id="head_sort" width="25">#</th>  --}}
                          <th id="head_sort" width="30">ปี</th>
                          <th width="70">วันที่ทำรายการ</th>
                          <th width="200">ชื่อตำรา/เอกสาร</th>
                          <th width="100">ประเภทงาน</th>
                          <th width="40">หน่วยกิต</th>
                          {{--  <th width="30">ปีที่ได้รับการอนุมัติ</th>  --}}
                          <th width="65"></th>
                        </tr>
                      </thead>
                      <tbody>
                          @foreach ($datas as $key=> $data)
                            <tr>
                              <td class="text-center">{{f::yearThai($data->acd_proceed_date	)}}</td>
                              <td>{{f::dateThai($data->acd_proceed_date	)}}</td>
                              <td>{{$data->acd_name}}</td>
                              <td>{{val::getACDCategory($data->acd_category)}}</td>
                              <td>{{$data->acd_creditPerWeek}}</td>
                              <td align="center">
                                <a class="btn btn-info btn-xs" style="padding:5px"  title="รายละเอียด"  data-toggle="modal" data-target="#viewModal"  data-id="{{$data->acd_id}}"><i class="fa fa-lg fa-info-circle"></i></a>
                                <a class="btn btn-warning btn-xs" style="padding:5px"  title="แก้ไข"  href="{{url('/academic-dev/edit/'.$data->acd_id)}}" @if(Auth::user()->id != $data->personnel_id) disabled @endif><i class="fa fa-lg fa-pencil-square-o"></i></a>
                                <a class="btn btn-danger btn-xs "  style="padding:5px"  title="ลบ" data-toggle="modal" data-target="#confirmDeleteModal" data-name="{{$data->acd_name}}" data-id="{{$data->acd_id}}"><i class="fa fa-lg fa-trash-o"></i></a>
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
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
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
  $('#head_sort').click();
  setTimeout(function () {
    $('#page-loading').remove();
  $('#section-content').show();
  }, 500);
  
})
</script>

@endsection
