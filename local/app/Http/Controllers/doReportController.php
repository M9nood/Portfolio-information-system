<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Input as Input;
use Illuminate\Support\Facades\Storage;
use Session;
use Auth;
use DB;
use App\func as f;
use App\constantsValue as val;
use App\Http\Controllers\ReportformController ;

class doReportController extends Controller
{
    //
    protected $rpt;
    public function __construct(){
        
        //$this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(empty(Auth::user())) return redirect('/');
            if(Auth::user()->user_level!="dean" and Auth::user()->user_level!="headofDp"){
                return redirect('/permise');
            }  
            return $next($request);
        });
        
    }

    public function indexReport(){
        if(Auth::user()->user_level=="dean") return redirect('fac-report');
        if(Auth::user()->user_level=="headofDp") return redirect('dep-report');
    }

    public function facReport(Request $request){
        if(empty($request->task)){
            if(Auth::user()->user_level=="dean") return view('pages.report.fac-report');
        }
        else{
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            if($request->task=="research-devinv") { $html = $this->showReportRSD(Auth::user()->id,"fac","",$st_date,$end_date); }
            if($request->task=="academic-dev") { $html = $this->showReportACD(Auth::user()->id,"fac","",$st_date,$end_date); }
            if($request->task=="academic-pub") { $html = $this->showReportACP(Auth::user()->id,"fac","",$st_date,$end_date); }
            if($request->task=="training") { $html = $this->showReportTRN(Auth::user()->id,"fac","",$st_date,$end_date); }
            if($request->task=="academic-service") { $html = $this->showReportACS(Auth::user()->id,"fac","",$st_date,$end_date); }
            return view('pages.report.fac-report',['html'=>$html]);
        }
       
    }

    public function depReport(Request $request){
        if(empty($request->task) and empty($request->dep)){
            return view('pages.report.dep-report');
        }
        else{
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            if($request->task=="research-devinv") { $html = $this->showReportRSD(Auth::user()->id,"dep",$request->dep,$st_date,$end_date); }
            if($request->task=="academic-dev") { $html = $this->showReportACD(Auth::user()->id,"dep",$request->dep,$st_date,$end_date); }
            if($request->task=="academic-pub") { $html = $this->showReportACP(Auth::user()->id,"dep",$request->dep,$st_date,$end_date); }
            if($request->task=="training") { $html = $this->showReportTRN(Auth::user()->id,"dep",$request->dep,$st_date,$end_date); }
            if($request->task=="academic-service") { $html = $this->showReportACS(Auth::user()->id,"dep",$request->dep,$st_date,$end_date); }
            return view('pages.report.dep-report',['html'=>$html]);
        }
    }

    public function indexPrint($selectLvl,$id,$task){
        $st_date = $_GET['startTime'];
        $end_date = $_GET['endTime'];
        if($task == "research-devinv") $html = $this->printReportRSD(Auth::user()->id,$selectLvl,$id,$st_date,$end_date);
        if($task == "academic-dev") $html = $this->printReportACD(Auth::user()->id,$selectLvl,$id,$st_date,$end_date);
        if($task == "academic-pub") $html = $this->printReportACP(Auth::user()->id,$selectLvl,$id,$st_date,$end_date);
        if($task == "training") $html = $this->printReportTRN(Auth::user()->id,$selectLvl,$id,$st_date,$end_date);
        if($task == "academic-service") $html = $this->printReportACS(Auth::user()->id,$selectLvl,$id,$st_date,$end_date);
    }

    /*********************************************************************/
    /*                            ALL Report                             */
    /*********************************************************************/

    public function showReportRSD($uid,$selectLvl,$depid,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac" ){ $whereraw =" faculty_id =  '".$user->faculty_id."'"; $selectId = $user->faculty_id; }
            else { $whereraw =" users.department_id =  '".$depid."'"; $selectId = $depid; }

            $tasks = DB::table('research_devinvention')
                    ->leftjoin('users','research_devinvention.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereRaw($whereraw)
                    ->orderBy('rsd_proceed_date')
                    ->get();
             $tasks = f::checkRSDduration($tasks,$st_date,$end_date);
            $title_centent;
            $rsd_cat = val::getAllRSDCategory();
            if($selectLvl=="fac"){
                $title_centent ='<b>คณะ </b>: '.f::getFacultyNameUseFacId($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else if($selectLvl=="dep"){
                $title_centent ='<b>ภาควิชา </b>: '.f::getDeparmentName($selectId).' <b>คณะ </b>: '.f::getFacultyName($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else{
                $title_centent ='<b>ผู้ปฏิบัติงาน </b>: '.f::getFullName(Auth::user()->id).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';
            }
            
            $html=  '<page size="A4" id="report">
                    <p class="report-head" ><b>รายงานงานวิจัยและพัฒนาสิ่งประดิษฐ์</b></p><br>
                    <div style="word-wrap:break-word;">
                        <p class="report-desc" >'.$title_centent.'</p>
                    </div>
                    <table border="1" cellpadding="2"  width="100%" class="tb-report table-hover">
                    <thead>
                    </thead>
                    <tbody>';
                        for($i =0;$i<count($rsd_cat);$i++) {
                        $isFirst = true;$sub = 0; 
                        if($isFirst) {
            $html=$html.        '<tr style="background-color: #99b3e6;" >
                                <th colspan="6" style="padding-left:10px">'.($i+1).". ".$rsd_cat[$i]['name'].'</th>
                                </tr>
                                <tr style="text-align:center;" >
                                    <td width="8%">ลำดับที่</td>
                                    <td width="47%">'.$rsd_cat[$i]['full'].'</td>
                                    <td width="10%" >ภาคที่ได้รับการอนุมัติ</td>
                                    <td width="17%">วันที่ดำเนินการ</td>
                                    <td width="20%">เอกสาร</td>
                                </tr>';
                             $isFirst = false; 
                        }
                        foreach($tasks as $key => $task){
                            if(($i+1) == $task->rsd_category){
                              $sub++; 
            $html=$html.        '<tr>
                                <td align="center" valign="top" style="border:0px!important">'.($i+1).".".$sub.'</td>
                                <td valign="top" style="line-height: 1;"> เรื่อง '.$task->rsd_name.'<br>'.val::getRSDNameRole($task->rsd_user_role).'</td>
                                <td valign="top" align="center" >'.$task->rsd_semester.'</td>
                                <td valign="top" align="center" >'.f::dateDBtoBE($task->rsd_proceed_date).'</td>
                                <td valign="top" >'.f::getFileById($task->rsd_id).'</td>
                            </tr>';
                            }
                        }
                    }
            $html=$html.'</tbody>
                        </table>
                        </page> 
                        <div style="text-align:center;margin-top:15px;">';
                    
        $printURL = url("report/".$selectLvl."/".$selectId."/research-devinv/print")."?startTime=".$st_date."&endTime=".$end_date;
                

        $html=$html.'<a class="btn btn-primary" onclick="window.open('."'".$printURL."'".')" id="print-btn"><i class="fa fa-lg fa-print"></i> พิมพ์รายงาน</a>
                    <a  class="btn btn-viewdt"  data-target="#viewAllfileModal" data-toggle="modal" data-token="'.csrf_token().'" data-datest="'.$st_date.'" data-dateend="'.$end_date.'" data-path="'.url('file/'.$selectLvl.'/'.$selectId.'/research-devinv').'" ><i class="fa  fa-file"></i> พิมพ์เอกสาร</a></td>
                    </div>' ;
            return $html;  
            
        } 
    }

    public function showReportACD($uid,$selectLvl,$depid,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac" ){ $whereraw =" faculty_id =  '".$user->faculty_id."'"; $selectId = $user->faculty_id; }
            else { $whereraw =" users.department_id =  '".$depid."'"; $selectId = $depid; }

            $tasks = DB::table('academic_development')
                    ->leftjoin('users','academic_development.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('acd_proceed_date',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('acd_proceed_date')
                    ->get();
            $title_centent;
            $acd_cat = val::ACDCategory();
            if($selectLvl=="fac"){
                $title_centent ='<b>คณะ </b>: '.f::getFacultyNameUseFacId($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else if($selectLvl=="dep"){
                $title_centent ='<b>ภาควิชา </b>: '.f::getDeparmentName($selectId).' <b>คณะ </b>: '.f::getFacultyName($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else{
                $title_centent ='<b>ผู้ปฏิบัติงาน </b>: '.f::getFullName(Auth::user()->id).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';
            }
            
            $html=  '<page size="A4" id="report">
                        <p class="report-head" ><b>รายงานงานพัฒนาวิชาการ</b></p><br>
                        <div style="word-wrap:break-word;">
                            <p class="report-desc" >'.$title_centent.'</p>
                        </div>
                    <table border="1" width="100%" class="tb-report table-hover">
                    <thead>
                     <tr>
                       <th width="7%">ลำดับที่</th>
                       <th width="43%">งานพัฒนาวิชาการ แต่ละเรื่อง<br>ไม่เกิน 3 ภาคการศึกษาปกติ</th>
                       <th width="9%">ภาคที่ได้รับการอนุมัติ</th>
                       <th width="7%">จำนวนหน่วยกิต/สัปดาห์</th>
                       <th width="14%">วันที่ดำเนินการ</th>
                       <th width="20%">เอกสาร</th>
                       <!--<th>เอกสาร</th> -->
                     </tr>
                    </thead>
                    <tbody>';
                      for($i =0;$i<count($acd_cat);$i++){
                        $isFirst = true;$sub = 0; 
                          if($isFirst) {
            $html=$html.        '<tr>
                                  <td valign="top" class="text-center"><b>'.($i+1).'</b></td>
                                  <td valign="top" ><b>'.$acd_cat[$i+1]['name'].'<b></td>
                                  <td valign="top" class="text-center"></td>
                                  <td valign="top" class="text-center"></td>
                                  <td valign="top" class="text-center"></td>
                                  <td valign="top" class="text-center"></td>
                                </tr>';
                                $isFirst = false; 
                          }
                          foreach($tasks as $key => $task){
                            if(($i+1) == $task->acd_category){
                               $sub++; 
            $html=$html.       '<tr>
                                <td valign="top" style="border:0px!important"></td>
                                <td valign="top" style="line-height: 1;" > '.$task->acd_name.'</td>
                                <td valign="top" align="center" style="line-height: 1;"> '.$task->acd_semester.'</td>
                                <td valign="top" align="center" style="line-height: 1;"> '.$task->acd_creditPerWeek	.'</td>
                                <td valign="top" style="line-height: 1;" align="center" >'.f::dateDBtoBE($task->acd_proceed_date).'</td>
                                <td valign="top" style="line-height: 1;">'.f::getFileById($task->acd_id).'</td>
                              </tr>';
                          }
                        }
                        }      
            $html=$html.'</tbody>
                        </table>
                        </page> 
                        <div style="text-align:center;margin-top:15px;">';
                    
        $printURL = url("report/".$selectLvl."/".$selectId."/academic-dev/print")."?startTime=".$st_date."&endTime=".$end_date;
                

        $html=$html.'<a class="btn btn-primary" onclick="window.open('."'".$printURL."'".')" id="print-btn"><i class="fa fa-lg fa-print"></i> พิมพ์รายงาน</a>
                    <a  class="btn btn-viewdt"  data-target="#viewAllfileModal" data-toggle="modal" data-token="'.csrf_token().'" data-datest="'.$st_date.'" data-dateend="'.$end_date.'" data-path="'.url('file/'.$selectLvl.'/'.$selectId.'/academic-dev').'" ><i class="fa fa-file"></i> พิมพ์เอกสาร</a></td>
                    </div>' ;
            return $html;  
            
        } 
    }

    public function showReportACP($uid,$selectLvl,$depid,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac" ){ $whereraw =" faculty_id =  '".$user->faculty_id."'"; $selectId = $user->faculty_id; }
            else { $whereraw =" users.department_id =  '".$depid."'"; $selectId = $depid; }

            $tasks = DB::table('academic_publication')
                    ->leftjoin('users','academic_publication.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('acp_proceed_date',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('acp_proceed_date')
                    ->get();
            $title_centent;
            $acp_cat = val::ACPtaskCategory();
            if($selectLvl=="fac"){
                $title_centent ='<b>คณะ </b>: '.f::getFacultyNameUseFacId($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else if($selectLvl=="dep"){
                $title_centent ='<b>ภาควิชา </b>: '.f::getDeparmentName($selectId).' <b>คณะ </b>: '.f::getFacultyName($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else{
                $title_centent ='<b>ผู้ปฏิบัติงาน </b>: '.f::getFullName(Auth::user()->id).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';
            }
            
            $html=  '<page size="A4" id="report">
                    <p class="report-head" ><b>รายงานงานเผยแพร่ผลงานทางวิชาการ</b></p><br>
                    <div style="word-wrap:break-word;">
                        <p class="report-desc" >'.$title_centent.'</p>
                    </div>
                    <table border="1" cellpadding="2"  width="100%" class="tb-report table-hover">
                        <thead>
                          <tr>
                            <th width="8%">ลำดับที่</th>
                            <th width="55%">งานเผยแพร่ผลงานทางวิชาการ</th>
                            <th width="17%">วันที่ตอบรับ/นำเสนอ</th>
                            <th width="20%">เอกสาร</th>
                          </tr>
                         </thead>
                         <tbody>';
                         for($i =0;$i<count($acp_cat);$i++){
                           $isFirst = true;$sub = 0; 
                           if($isFirst) {
            $html=         $html.'<tr>
                                   <td valign="top" class="text-center"><b>'.($i+1).'</b></td>
                                   <td valign="top" ><b>'.$acp_cat[$i].'<b></td>
                                   <td valign="top" class="text-center"></td>
                                   <td valign="top" class="text-center"></td>
                                 </tr>';
                                 $isFirst = false; 
                           }
                           foreach($tasks as $key => $task){
                             if(($i+1) == $task->acp_task_type){
                                 $sub++; 
            $html=         $html.'<tr>
                                 <td valign="top" style="border:0px!important"></td>
                                 <td valign="top" style="line-height: 1;" > 
                                   ชื่อ'.val::ACPCategory($task->acp_category).' '.$task->acp_name.'<br>
                                   เรื่อง '.$task->acp_title.'<br>';
                                   if($task->acp_user_role!==99) $html=$html.'- '.val::ACPRole($task->acp_user_role); 
            $html=         $html.'</td>
                                 <td valign="top" align="center" style="line-height: 1;" >'.f::dateDBtoBE($task->acp_proceed_date).'</td>
                                 <td valign="top" style="line-height: 1;">'.f::getFileById($task->acp_id).'</td>
                               </tr>';
                           }
                           
                        }
                        if($sub==0){
            $html=       $html.'<tr>
                                 <td height="20" valign="top" colspan="2"></td>
                                 <td valign="top"></td>
                                 <td valign="top"></td>
                                </tr>';
                        }
                    }
                        $html=$html.'</tbody>
                        </table>
                        </page> 
                        <div style="text-align:center;margin-top:15px;">';
                    
        $printURL = url("report/".$selectLvl."/".$selectId."/academic-pub/print")."?startTime=".$st_date."&endTime=".$end_date;
                

        $html=$html.'<a class="btn btn-primary" onclick="window.open('."'".$printURL."'".')" id="print-btn"><i class="fa fa-lg fa-print"></i> พิมพ์รายงาน</a>
                    <a  class="btn btn-viewdt"  data-target="#viewAllfileModal" data-toggle="modal" data-token="'.csrf_token().'" data-datest="'.$st_date.'" data-dateend="'.$end_date.'" data-path="'.url('file/'.$selectLvl.'/'.$selectId.'/academic-pub').'" ><i class="fa fa-file"></i>  พิมพ์เอกสาร</a></td>
                    </div>' ;
            
            return $html;  
            
        } 
    }

    public function showReportTRN($uid,$selectLvl,$depid,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();

        if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac" ){ $whereraw =" faculty_id =  '".$user->faculty_id."'"; $selectId = $user->faculty_id; }
            else { $whereraw =" users.department_id =  '".$depid."'"; $selectId = $depid; }

             $tasks = DB::table('training')
                    ->leftjoin('users','training.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('trn_start',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('trn_start')
                    ->get();
            $title_centent;
            if($selectLvl=="fac"){
                $title_centent ='<b>คณะ </b>: '.f::getFacultyNameUseFacId($selectId).'<br>
                 <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else if($selectLvl=="dep"){
                $title_centent ='<b>ภาควิชา </b>: '.f::getDeparmentName($selectId).' <b>คณะ </b>: '.f::getFacultyName($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else{
                $title_centent ='<b>ผู้เข้ารับฝึกอบรม </b>: '.f::getFullName(Auth::user()->id).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';
            }
                    
            $html='<page size="A4" id="report">
                    <p class="report-head" ><b>รายงานการเข้ารับฝึกอบรม</b></p><br>
                    <div style="word-wrap:break-word;">
                        <p class="report-desc" >'.$title_centent.'</p>
                    </div>
                    <table border="1" width="100%" class="tb-report table-hover">
                    <thead>
                        <tr>
                        <th width="8%">ลำดับที่</th>
                        <th width="33%">งานเข้ารับฝึกอบรม</th>
                        <th width="15%">วันที่เริ่มอบรม</th>
                        <th width="15%">วันที่สิ้นสุด</th>
                        <th width="8%">จำนวนบุคลากรที่เข้าร่วม</th>
                        <th width="26%">เอกสาร</th>
                        <!--<th>เอกสาร</th> -->
                        </tr>
                    </thead>
                    <tbody>';

                    foreach( $tasks as $key => $task ){
                        $html=$html.'<tr>'.
                        '<td valign="top" class="text-center">'.($key+1).'</td>'.
                        '<td valign="top">'.$task->trn_name.'</td>'.
                        '<td valign="top" align="center">'.f::dateDBtoBE($task->trn_start).'</td>'.
                        '<td valign="top" align="center">'.f::dateDBtoBE($task->trn_end).'</td>'.
                        '<td valign="top" class="text-center">'.f::countCoTeacher($task->coTeacher).'</td>'.
                        '<td valign="top">'.f::getFileById($task->trn_id).'</td>'.
                        '</tr>';
                    }
        $html=$html.'</tbody>
                    </table>
                    </page>
                    <div style="text-align:center;margin-top:15px;">';
                    
        $printURL = url("report/".$selectLvl."/".$selectId."/training/print")."?startTime=".$st_date."&endTime=".$end_date;
                

        $html=$html.'<a class="btn btn-primary" onclick="window.open('."'".$printURL."'".')" id="print-btn"><i class="fa fa-lg fa-print"></i> พิมพ์รายงาน</a>
                    <a  class="btn btn-viewdt"  data-target="#viewAllfileModal" data-toggle="modal" data-token="'.csrf_token().'" data-datest="'.$st_date.'" data-dateend="'.$end_date.'" data-path="'.url('file/'.$selectLvl.'/'.$selectId.'/training').'" ><i class="fa fa-file"></i> พิมพ์เอกสาร</a></td>
                    </div>
                </div>' ;
            return $html;
                
            
        } 
    }

    public function showReportACS($uid,$selectLvl,$depid,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac" ){ $whereraw =" faculty_id =  '".$user->faculty_id."'"; $selectId = $user->faculty_id; }
            else { $whereraw =" users.department_id =  '".$depid."'"; $selectId = $depid; }

            $tasks = DB::table('academic_service')
                    ->leftjoin('users','academic_service.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('as_start_date',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('as_start_date')
                    ->get();
            $title_centent;
            $typeAS = val::getTypeNameAS();
            if($selectLvl=="fac"){
                $title_centent ='<b>คณะ </b>: '.f::getFacultyNameUseFacId($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else if($selectLvl=="dep"){
                $title_centent ='<b>ภาควิชา </b>: '.f::getDeparmentName($selectId).' <b>คณะ </b>: '.f::getFacultyName($selectId).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';;
            }
            else{
                $title_centent ='<b>ผู้ปฏิบัติงาน </b>: '.f::getFullName(Auth::user()->id).'<br>
                <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';
            }
            
            $html='<page size="A4" id="report">
                    <p class="report-head" ><b>รายงานงานบริการวิชาการและอื่นๆ</b></p><br>
                    <div style="word-wrap:break-word;">
                        <p class="report-desc" >'.$title_centent.'</p>
                    </div>
                    <table border="1" width="100%" class="tb-report table-hover">
                    <thead>
                     <tr>
                        <th width="8%">ลำดับที่</th>
                        <th width="57%">งานบริการวิชาการและอื่นๆ</th>
                        <th width="15%">วันที่ดำเนินการ</th>
                        <th width="25%">เอกสาร</th>
                     </tr>
                    </thead>
                    <tbody>';
                     for($i =0;$i<count($typeAS);$i++){
                     $isFirst = true;$sub = 0;
                     if($isFirst){ 
                        $html=$html.'<tr>'.
                                    '<td valign="top" class="text-center">'.($i+1).'</td>'.
                                    '<td valign="top" style="line-height: 1;"><b>'."$typeAS[$i]".'<b></td>'.
                                    '<td valign="top" class="text-center"></td>
                                    <td valign="top"></td>
                                    </tr>';
                            $isFirst = false;
                        }
                       foreach($tasks as $key => $task){
                         if(($i+1) == $task->as_category){
                            try{
                            $sub++; 
                            
            $html=$html.     '<tr>
                             <td valign="top" style="border:0px!important"></td>
                             <td valign="top" style="line-height: 1;">'.($i+1).".".$sub." ".$task->as_name.'</td>'.
                             '<td valign="top" align="center" >'.f::dateDBtoBE($task->as_start_date).'</td>'.
                             '<td valign="top" > '.f::getFileById($task->as_id).'</td>'.
                             '</tr>';
                            }catch (\Exception $e) {
                                return 'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง';
                            }  
                         }
                       
                       }
                    if(!$isFirst){
            $html=$html. '<tr>
                         <td height="20" valign="top" colspan="2"></td>
                         <td valign="top"></td>
                         <td valign="top"></td>
                       </tr>';
                       }
                     }
            $html=$html.'</tbody>
                   </table>
                    </page>
                    <div style="text-align:center;margin-top:15px;">';
                    
        $printURL = url("report/".$selectLvl."/".$selectId."/academic-service/print")."?startTime=".$st_date."&endTime=".$end_date;
                

        $html=$html.'<a class="btn btn-primary" onclick="window.open('."'".$printURL."'".')" id="print-btn"><i class="fa fa-lg fa-print"></i> พิมพ์รายงาน</a>
                    <a  class="btn btn-viewdt"  data-target="#viewAllfileModal" data-toggle="modal" data-token="'.csrf_token().'" data-datest="'.$st_date.'" data-dateend="'.$end_date.'" data-path="'.url('file/'.$selectLvl.'/'.$selectId.'/academic-service').'" ><i class="fa fa-file"></i> พิมพ์เอกสาร</a></td>
                    </div>' ;
            return $html;  
            
        } 
    }

    /*********************************************************************/
    /*                            ALL Print                              */
    /*********************************************************************/

    public function printReportRSD($uid,$selectLvl,$id,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac"){ $whereraw =" faculty_id =  '".$user->faculty_id."'";$selectId = $user->faculty_id; }
            else{ $whereraw =" users.department_id =  '".$user->department_id."'"; $selectId = $user->department_id; }

            $tasks = DB::table('research_devinvention')
                    ->leftjoin('users','research_devinvention.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereRaw($whereraw)
                    ->orderBy('rsd_proceed_date')
                    ->get();
            $tasks = f::checkRSDduration($tasks,$st_date,$end_date);
            $this->rpt = new ReportformController;
            $this->rpt->createReportRSD($tasks,$st_date,$end_date,$selectLvl,$selectId);  
        }
    }

    public function printReportACD($uid,$selectLvl,$id,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac"){ $whereraw =" faculty_id =  '".$user->faculty_id."'";$selectId = $user->faculty_id; }
            else{ $whereraw =" users.department_id =  '".$user->department_id."'"; $selectId = $user->department_id; }

            $tasks = DB::table('academic_development')
                    ->leftjoin('users','academic_development.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('acd_proceed_date',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('acd_proceed_date')
                    ->get();
            $this->rpt = new ReportformController;
            $this->rpt->createReportACD($tasks,$st_date,$end_date,$selectLvl,$selectId);  
        }
    }

    public function printReportACP($uid,$selectLvl,$id,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac"){ $whereraw =" faculty_id =  '".$user->faculty_id."'";$selectId = $user->faculty_id; }
            else{ $whereraw =" users.department_id =  '".$user->department_id."'"; $selectId = $user->department_id; }

            $tasks = DB::table('academic_publication')
                    ->leftjoin('users','academic_publication.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('acp_proceed_date',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('acp_proceed_date')
                    ->get();
            $this->rpt = new ReportformController;
            $this->rpt->createReportACP($tasks,$st_date,$end_date,$selectLvl,$selectId);  
        }
    }

    public function printReportTRN($uid,$selectLvl,$id,$st_date,$end_date){
        $user = DB::table('users')
                    ->rightjoin('departments','users.department_id','=','departments.department_id')
                    ->where('id',$uid)
                    ->first();
            if(!empty($user)){
            if($user->user_level=="dean" and $selectLvl=="fac"){ $whereraw =" faculty_id =  '".$user->faculty_id."'";$selectId = $user->faculty_id; }
            else{ $whereraw =" users.department_id =  '".$user->department_id."'"; $selectId = $user->department_id; }

            $tasks = DB::table('training')
                    ->leftjoin('users','training.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->whereBetween('trn_start',[$st_date,$end_date])
                    ->whereRaw($whereraw)
                    ->orderBy('trn_start')
                    ->get();
            $this->rpt = new ReportformController;
            $this->rpt->createReportTRN($tasks,$st_date,$end_date,$selectLvl,$selectId);  
        }
    }

    public function printReportACS($uid,$selectLvl,$id,$st_date,$end_date){
        $user = DB::table('users')
                ->rightjoin('departments','users.department_id','=','departments.department_id')
                ->where('id',$uid)
                ->first();
        if(!empty($user)){
        if($user->user_level=="dean" and $selectLvl=="fac" ){ $whereraw =" faculty_id =  '".$user->faculty_id."'"; $selectId = $user->faculty_id; }
        else { $whereraw =" users.department_id =  '".$user->department_id."'"; $selectId = $user->department_id; }

        $tasks = DB::table('academic_service')
                ->leftjoin('users','academic_service.personnel_id','=','users.id')
                ->leftjoin('departments','users.department_id','=','departments.department_id')
                ->whereBetween('as_start_date',[$st_date,$end_date])
                ->whereRaw($whereraw)
                ->orderBy('as_start_date')
                ->get();

                $this->rpt = new ReportformController;
                $this->rpt->createReportACS($tasks,$st_date,$end_date,$selectLvl,$selectId);  

        }
    }


    public function getFileByFaculty($facId,$task,Request $request){
        $html = "";
        $tb =  f::getTableName($task);        
        $stDate = $request->dateStart;//'2017-07-30';
        $endDate = $request->dateEnd;//'2018-02-01';
        $taskList = f::getTaskListforFaculty($tb,$stDate,$endDate,$facId);
        $files = array();
        foreach($taskList as $key => $task){ 
            $a_task_file = f::getFileData($task->id);
            foreach($a_task_file as $key => $aFile){
                array_push($files,$aFile->doc_id);
            }
        }
        if(empty($files)){
            $html = $html."<div style='margin-top:30px'><center>ไม่มีเอกสาร</center></div>";
        }
        else{
            $html = $html."<div class='pull-right' ><a onclick='openAllFile(".count($files).")'>เปิดทั้งหมด</a><br><br></div>";
            $html = $html."<table   class='tb-file table-hover'>";
            $html = $html."<tr><td></td><td>ชื่อเอกสาร</td><td>อ้างอิงโดย</td></tr>";
            for($n=0;$n<count($files);$n++){
                $html = $html.f::showFileinModal($files[$n],$n);
            }
            $html = $html."</table>";  
        }
        return response()->json(['html'=>$html]);
    }

    public function getFileByDepartment($depId,$task,Request $request){
        $html = "";
        $tb =  f::getTableName($task);        
        $stDate = $request->dateStart;//'2017-07-30';
        $endDate = $request->dateEnd;//'2018-02-01';
        $taskList = f::getTaskListforDepartment($tb,$stDate,$endDate,$depId);

        $files = array();
        foreach($taskList as $key => $task){ 
            $a_task_file = f::getFileData($task->id);
            foreach($a_task_file as $key => $aFile){
                array_push($files,$aFile->doc_id);
            }
        }
        if(empty($files)){
            $html = $html."<div style='margin-top:30px'><center>ไม่มีเอกสาร</center></div>";
        }
        else{
            $html = $html."<div class='pull-right' ><a onclick='openAllFile(".count($files).")'>เปิดทั้งหมด</a><br><br></div>";
            $html = $html."<table   class='tb-file table-hover'>";
            $html = $html."<tr><td></td><td>ชื่อเอกสาร</td><td>อ้างอิงโดย</td></tr>";
            for($n=0;$n<count($files);$n++){
                $html = $html.f::showFileinModal($files[$n],$n);
            }
            $html = $html."</table>";  
        }
        return response()->json(['html'=>$html]);
    }
    
   
}