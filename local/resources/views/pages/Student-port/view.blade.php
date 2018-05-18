<?php  use App\func as f; ?>
@extends('pages.Student-port.index')

@section('subcontent')
<div class="row" id="section-content" style="display:none">
     <div class='col-md-12'>
       <div class="card">
     
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" ><a href="{{url('/std-portfolio')}}" ><i class="fa fa-lg  fa-table" aria-hidden="true"></i> ตารางผลงานนักศึกษา</a></li>
          <li role="presentation"><a href="{{url('/std-portfolio/report')}}"  ><i class="fa fa-lg fa-file-text" aria-hidden="true"></i>&nbsp ดูรายงาน</a></li>  
          <li role="presentation" class="active"><a style="background-color: white;border:1px solid #009688;border-bottom:0px;color:#009688" onClick="refresh()" ><i class="fa fa-lg  fa-address-book-o" aria-hidden="true"></i> ข้อมูลผลงานนักศึกษา</a></li>
        </ul>


        <div class="panel panel-success" style="margin-top:30px">
          <!-- Default panel contents -->
          <div class="panel-heading title-content"><h5>ข้อมูลผลงานนักศึกษา</h5></div>
          <div class="panel-body">
             <div class="row">
              <div class="col-sm-12"> 
                 <table  class="table table-bordered text-warp">
                    <tr>
                        <td width="200" class="td-label">รหัส</td><td>{{$stp->stp_id}}</td>
                    </tr>
                    <tr>
                        <td class="td-label">เรื่อง</td><td>{{$stp->stp_name}}</td>
                    </tr>
                    <tr>
                        <td class="td-label">รายละเอียด</td><td >{!!$stp->stp_description!!}</td>
                    </tr>
                    <tr>
                        <td class="td-label">รางวัลที่ได้รับ</td><td >{!!$stp->award!!}</td>
                    </tr>
                    <tr>
                        <td class="td-label">รายชื่อนักศึกษา</td>
                        <td >
                          {!!f::stdList($stp->student_name_list)!!}
                          @if(count(explode(",",$stp->student_name_list))>10)
                          <span><a onclick='window.open("{{url('std/'.$stp->stp_id)}}", "_blank", "toolbar=yes,width=450,height=500");'>ดูรายชื่อ</a></span>
                          @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="td-label">อาจารย์ผู้ควบคุม</td><td >{!!f::superviserList($stp->superviser_id)!!}</td>
                    </tr>
                    <tr>
                        <td class="td-label">วันที่ดำเนินการ</td><td >{!!f::dateThaiFull($stp->stp_proceed_date)	!!}</td>
                    </tr>
                    <tr>
                        <td class="td-label">อัลบั้ม</td><td onClick='window.open("{{url('album/dept/std-portfolio/'.f::getAlumid($stp->album_id))}}")'><a href="#">เปิด</a></td>
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
                <a href="{{url('std-portfolio')}}" class="btn btn-primary">กลับ</a>
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
