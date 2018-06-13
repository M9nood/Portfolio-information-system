<?php
use App\func as f;
use App\constantsValue as val;
$tb =  val::getTableAttr();
$tb = (object)$tb;

$color_folder = array("#ffa726","#ef5350","#66bb6a","#d24dff","#29b6f6","#bf8040");
$cntUserTask = f::countUserTask(Auth::user()->id);

?>
@extends('layouts.app',['page'=>'index'])

@section('content')
<div class="content-wrapper">
    <div class="page-title" >
        <div>
           <h1><i class="fa fa-dashboard"></i> หน้าหลัก</h1>
            <p>ระบบสารสนเทศสำหรับจัดการข้อมูลผลงาน</p>
         </div>
          <div>
            <ul class="breadcrumb">
                <li><i class="fa fa-home fa-lg"></i></li>
            </ul>
          </div>
    </div>
     <div class="row">
        <div class="col-md-12 ">
            <div class="card" style="padding-bottom:15px">
             <h4>สวัสดี!</h4>
             <div class="row">
                <div class="col-md-3 col-xs-6"><b>คุณ  {{Auth::user()->name}} {{ Auth::user()->lastname}} </b></div>
                <div class="col-md-4 col-xs-6"><b>อีเมล : </b> {{Auth::user()->email}}</div>
                <div class="col-md-5 col-xs-6"><b>ตำแหน่ง : </b> {{Auth::user()->user_position_name}}</div>
                <div class="col-md-3 col-xs-6"><b>ภาควิชา : </b> {{f::getDeparmentName(Auth::user()->department_id)}}</div>
                <div class="col-md-4 col-xs-6"><b>คณะ : </b> {{f::getFacultyName(Auth::user()->department_id)}}</div>
                <div class="col-md-5 col-xs-6"><b>มหาวิทยาลัย : </b> มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ</div>
                <div class="col-md-3 col-xs-6"><b>สถานะผู้ใช้ : </b> {{Auth::user()->user_level}}</div>
             </div>
             
             
            </div>   
        </div>
    </div>
    <div class="row">  
        <div class="col-md-12">
            <div class="card">
                <div class="row dashboard-list-title ">
                    <span><i class="fa fa-bar-chart" aria-hidden="true"></i>  ผลงานของคุณ</span>
                </div> 
                <div class="row">
                        <div class="col-md-1 "></div>
                        <div class="col-md-10 ">
                             <div class="embed-responsive embed-responsive-16by9">
                                 {{-- <canvas id="myChart" style="max-width: 70%;" class="embed-responsive-item"></canvas> --}}
                                 <canvas  class="embed-responsive-item" id="barChartDemo"></canvas>
                                 {{-- <div id="chartContainer" style="height: 300px; width: 100%;"></div> --}}
                             </div>
                         </div> 
                         <div class="col-md-1 "></div>    
                </div>
            </div>
        </div>  
    </div>
    <div class="row">
            <div class="col-md-12 col-xs-12">
             <div class="card">
               <div class="row">
                   
                   <div class="col-md-8 col-xs-12">
                       <div class="row dashboard-list-title ">
                           <span><i class="fa fa-print" aria-hidden="true"></i>  เอกสารล่าสุด</span>
                       </div> 
                       <div class="row" style="margin:15px 0px">
                           <div class="table-resonsive">
                               <table class="table  table-hover">
                                   <tr >
                                       <th>ชื่อเอกสาร</th>
                                       <th>วันเวลา</th>
                                   </tr>
                                   @foreach($docs as $key =>$doc)
                                   <tr onClick='window.open("https://drive.google.com/open?id={{$doc->doc_id}}")' class=@if($key%2==1) 'info' @endif  >
                                       <td>{{$doc->doc_name}}</td>
                                   <td>{{f::dateDBtoBE(substr($doc->lastest_updated,0,10))}} เวลา {{substr($doc->lastest_updated,11,5)}}น.</td>
                                   </tr>
                                   @endforeach
                               </table>
                           </div>
                       </div>
                   </div> 
                   <div class="col-md-4 col-xs-12">
                           <div class="row dashboard-list-title ">
                               <span><i class="fa fa-print" aria-hidden="true"></i>  เอกสารทั้งหมด</span>
                           </div> 
                           <div class="row" style="margin:15px 0px">
                               <div class="bs-component">
                                   <div class="list-group">
                                       <?php $color_at =0;?>
                                       @foreach($tb as $key => $tb)
                                       @if($key!='std-portfolio' )
                                        <a class="list-group-item" href="{{url('file/'.$key.'/user')}}"><span class="badge">{!!f::countDoc(Auth::user()->id,$key)!!}</span><i class="fa fa-folder fa-lg" style="color:{{$color_folder[$color_at]}}"></i>&nbsp เอกสาร{{$tb['name']}}</a>
                                       @elseif($key=='std-portfolio' )
                                        <a class="list-group-item" href="{{url('album/dept/'.$key)}}"><span class="badge">{!!f::countALLAlbum($key)!!}</span><i class="fa fa-folder fa-lg" style="color:{{$color_folder[$color_at]}}"></i>&nbsp อัลบั้ม{{$tb['name']}}</a>
                                       @endif
                                       <?php $color_at++;?>
                                       @endforeach
                                   </div>
                               </div>
                           </div>
                   </div>   
               </div>  
              </div>
            </div>
           </div>
       


 
    
 <script type="text/javascript" src="{{ url('js/plugins/chart.js') }}"></script>

 <script type="text/javascript">
     
    var cntTask = [<?php echo '"'.implode('","', $cntUserTask).'"' ?>];
    var ctx = document.getElementById("barChartDemo").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            "งานวิจัยและพัฒนาสิ่งประดิษฐ์", 
            "งานพัฒนาวิชาการ", 
            "งานเผยแพร่ผลงานทางวิชาการ", 
            "งานบริการวิชาการและอื่นๆ", 
            "งานเข้ารับฝึกอบรม", 
        ],
        datasets: [{
            label: 'จำนวนผลงาน',
            data: cntTask,
            backgroundColor: [
                'rgba(255,167,38, 0.4)',
                'rgba(239,83,80, 0.4)',
                'rgba(102,187,106, 0.4)',
                'rgba(210,77,255, 0.4)',
                'rgba(41,182,246, 0.4)',
                'rgba(191,128,64, 0.4)'
            ],
            borderColor: [
                'rgba(212,136,24,1)',
                'rgba(239,83,80, 1)',
                'rgba(69,165,73, 1)',
                'rgba(150,36,189, 1)',
                'rgba(24,136,186, 1)',
                'rgba(133,89,44, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 5,
                    min: 0,
                }
            }]
        }
    }
});

    </script>
    <script>
    $('.counter-count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
    </script>
@endsection
