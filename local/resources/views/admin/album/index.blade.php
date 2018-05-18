<?php
use App\func as f;
?>
@extends('layouts.admin')


@section('content')
<div class="content-wrapper">
    <div class="page-title">
        <div>
        <h1><i class="fa fa-dashboard"></i> อัลบั้ม ผลงานนักศึกษา</h1>
        <p>แกลอรี่ อัลบั้มรูปผลงานของนักศึกษา</p>
         </div>
         <div>
            <ul class="breadcrumb">
              <li><a href="{{url('/admin')}}"><i class="fa fa-home fa-lg" ></i></a></li>
              <li><a href="#">อัลบั้ม</a></li>
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
              
                        @foreach($albums as $key=> $album)
                        <div class=" col-sm-3" id="div-album" >
                          <div class="floated album-card">
                            <a class="limited">
                              <img style="" src="{{f::getImageAlbum($album->album_id)}}" width="100%"/>       
                            </a>
                            <div class="album-desc">
                            <label><a href="{{url('admin/album/all/std-portfolio/'.$album->album_id)}}">{{f::cutStr(f::albumName($album->album_id),30,'...')}}</a></label><br>
                                <span>{{f::countImg($album->album_id)}} รูป</span>
                            </div>
                          </div>
                      </div>
                        @endforeach

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
 
