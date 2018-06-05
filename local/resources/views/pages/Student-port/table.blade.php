
<?php  use App\func as f; ?>
@extends('pages.Student-port.index')

@section('subcontent')

<div class="row" id="section-content" style="display:none">
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a onClick="refresh()" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-lg fa-table" aria-hidden="true"></i>&nbsp ตารางผลงานนักศึกษา</a></li>
          <li role="presentation"><a href="{{url('/std-portfolio/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>  
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="home">
            <div class="">
              <div class="title-content" >
                <h5>รายการผลงานนักศึกษา</h5>
              </div>
              <div class="pull-right">
                @if(Auth::user()->isadm_stp == "yes")
                <a class="btn btn-primary btn-flat" data-toggle="tooltip" title="เพิ่ม" href="{{url('/std-portfolio/add')}}"><i class="fa fa-lg fa-plus"></i></a>@endif<a class="btn btn-info btn-flat" data-toggle="tooltip" title="รีเฟรช" onClick="refresh()"><i class="fa fa-lg fa-refresh"></i></a>
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
                          <th id="head_sort" width="30">ปี</th>
                          <th width="70">วันที่ดำเนินการ</th>
                          <th width="250">เรื่อง</th>
                          <th width="150">รางวัล</th>
                          <th width="65"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($datas as $data)
                          <tr>
                            <td>{{f::yearThai($data->stp_proceed_date)}}</td>
                            <td>{{f::dateThai($data->stp_proceed_date)}}</td>
                            <td>{{$data->stp_name}}</td>
                            <td>{{$data->award}}</td>
                            <td style="text-align:center">
                              @if(f::isOfficer())
                              <a class="btn btn-info btn-xs" style="padding:5px"  title="รายละเอียด"  href="{{url('/std-portfolio/view/'.$data->stp_id)}}"><i class="fa fa-lg fa-info-circle"></i></a>
                              <a class="btn btn-warning btn-xs" style="padding:5px"  title="แก้ไข"  href="{{url('/std-portfolio/edit/'.$data->stp_id)}}" ><i class="fa fa-lg fa-pencil-square-o" ></i></a>
                              <a class="btn btn-danger btn-xs "  style="padding:5px"  title="ลบ" data-toggle="modal" data-target="#confirmDeleteModal" data-name="{{$data->stp_name}}" data-id="{{$data->stp_id}}"><i class="fa fa-lg fa-trash-o"></i></a>
                              @else
                              <a class="btn btn-info btn-xs" style="padding:5px"  title="รายละเอียด"  href="{{url('/std-portfolio/view/'.$data->stp_id)}}"><i class="fa fa-lg fa-info-circle"></i> ดูผลงาน</a>
                              @endif
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
