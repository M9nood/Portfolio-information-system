<?php
use App\func as f;
?>
@extends('layouts.app',['page'=>''])


@section('content')
<div class="content-wrapper">
    <div class="page-title">
        <div>
        <h1><i class="fa fa-dashboard"></i> เอกสาร{{$taskName}}</h1>
        <p>เอกสารผลงาน{{$taskName}} ที่เกี่ยวข้องของบุคลากร</p>
         </div>
         <div>
            <ul class="breadcrumb">
              <li><a href="{{url('/')}}"><i class="fa fa-home fa-lg" ></i></a></li>
              <li><a href="#">เอกสาร</a></li>
            </ul>
          </div>
    </div>
    <div class="row" id="section-content" style="display:none">
      <div class='col-md-12'>
        <div class="card">
 
         <!-- Tab panes -->
         <div class="tab-content">
           <div role="tabpanel" class="tab-pane active" id="home">
             <div class="row">
               <div class="col-sm-12">
                 <div class="card">
                   <div class="card-body">
                   <div class="table-responsive">
                     <table class="table table-hover " id="sampleTable" >
                       <thead>
                         <tr>
                           <th>#</th>
                           <th >เอกสาร</th>
                           <th >งาน</th>
                           <th >วันที่อับโหลด</th>
                         </tr>
                       </thead>
                       <tbody>
                        @foreach($docs as $key=> $doc)
                       <tr onClick='window.open("https://drive.google.com/open?id={{$doc->doc_id}}")' class=@if($key%2==0) 'info' @endif >
                          <td class='text-center'>{!!f::getImgFileType($doc->doc_type)!!}</td>
                          <td>{{$doc->doc_name}}</td>
                          <td>{{f::getTaskName($doc->task_id)}}</td>
                          <td>{{f::dateThaiFull($doc->lastest_updated)}}</td>
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
 
    
</div>
<!-- Javascripts-->
    <script type="text/javascript" src="{{url('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
    <script type="text/javascript" src="{{url('js/plugins/jquery.form.js')}}"></script>
    <script src="{{ url('js/modal.script.js') }}"></script>
<script>
$(document).ready(function(){  
  //$('#head_sort').click();
  $('#page-loading').remove();
  $('#section-content').show();
})
</script>
    
@endsection
 
