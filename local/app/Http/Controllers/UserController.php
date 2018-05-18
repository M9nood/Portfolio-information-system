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

class UserController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //
    protected $client;
    protected $folder_id;
    protected $rootFolderId='1DuIEUjTttUWWpBm38wpOpoJH77ACAyHq';
    protected $service;
    protected $rpt;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function permise(){
        return view('pages.permise');
    }

    public function index(){
        $docs = DB::table('documents')
                ->orderBy('lastest_updated','desc')
                ->where(DB::raw('substr(doc_name,8,3)'),'=',Auth::user()->short_name_en)
                ->limit(10)
                ->get();
        //dd($docs);
        return view('pages.home',array('docs'=>$docs,'page'=> 'Face'));
    }


    public function about(){
        dd(Auth::user()->drive_folder_id);
        return view('pages.about');
    }

   
    /*********************************************************************/
    /*                     Research dev-invention                        */
    /*********************************************************************/
    public function indexRSD(){
        $dTable = DB::table('research_devinvention')
                    ->where('personnel_id',Auth::user()->id)
                    ->orderBy('rsd_proceed_date', 'desc')
                    ->get();
        $datas = array(
            'datas' => $dTable,
            'page'  => 'research_devinvention'
        );
        //dd($datas);
        return view('pages.Research-devinv.table',$datas);
    }

    public function addFormRSD(){
        return view('pages.Research-devinv.addForm');
    }

    public function viewRSD($id){
        $datas;
        if(isset($id)){
            $task = DB::table('research_devinvention')
                        ->where('rsd_id',$id)
                        ->first();
        }

        
        $html="";
        $html =$html.'<table class="tb-view-modal" border="0" width="100%">'.
                '<tr>
                    <td width="30%" valign="top"><b>รหัสงาน</b></td><td valign="top">'.$task->rsd_id.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ชื่อเรื่อง</b></td><td valign="top">'.$task->rsd_name.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ประเภทงาน</b></td><td valign="top">'.val::getRSDCategory($task->rsd_category).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>หน้าที่</b></td><td valign="top">'.val::getRSDNameRole($task->rsd_user_role).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ภาคการศึกษาที่ได้รับการอนุมัติ</b></td><td valign="top">'.$task->rsd_semester.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>วันที่ดำเนินการ</b></td><td valign="top">'.f::dateThaiFull($task->rsd_proceed_date).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>วันที่สิ้นสุดสัญญา</b></td><td valign="top">'.f::dateThaiFull($task->rsd_end_proceed_date).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>เอกสารแนบ</b></td><td>'.f::createTagLinkFile($task->rsd_id).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>เพิ่มโดย</b></td><td valign="top">'.f::getFullName($task->personnel_id).'</td>
                </tr>
                ';
        
        $html =$html."</table>";
        //echo $html;
        //dd($html);
        return response()->json(['html'=>$html]);
        //return "Tess!!!!!!!!";
    }

    public function editFormRSD($id=''){
        if($id !=''){
            if(f::checkPermise($id,'research_devinvention','rsd_id')){
                $task = DB::table('research_devinvention')
                            ->where('rsd_id',$id)
                            ->first();
                if(!isset($task)){ return view('errors.404');}
                $document = DB::table('documents')
                                ->where('task_id',$id)
                                ->get();
                $datas = array(
                    'task' => $task,
                    'documents' => $document
                );
                return view('pages.Research-devinv.editForm',$datas);
            }
            else return redirect('/permise');
        }
    }

    public function reportRSD(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $rsd = DB::table('research_devinvention')
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('rsd_proceed_date')
                   ->get();
            $tsk = array();
            $tsk = f::checkRSDduration($rsd,$st_date,$end_date);
            
            //dd($tsk);
            $data = array(
                'tasks' => $tsk
            );
            return view('pages.Research-devinv.report',$data);
        }
        else return view('pages.Research-devinv.report');
    }

    public function printRSD(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $rsd = DB::table('research_devinvention')
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('rsd_proceed_date')
                   ->get();
            $rsd = f::checkRSDduration($rsd,$st_date,$end_date);
            $this->rpt = new ReportformController;
            $this->rpt->createReportRSD($rsd,$st_date,$end_date);
           
        }
    }


    /*********************************************************************/
    /*                     Academic_development                          */
    /*********************************************************************/

    public function indexACD(){
        $dTable = DB::table('academic_development')
                    ->where('personnel_id',Auth::user()->id)
                    ->orderBy('acd_proceed_date', 'desc')
                    ->get();
        $datas = array(
            'datas' => $dTable,
            'page'  => 'academic-dev'
        );
        //dd($datas);
        return view('pages.Academic-dev.table',$datas);
    }

    public function addFormACD(){
        return view('pages.Academic-dev.addForm');
    }

    public function viewACD($id){
        $datas;
        if(isset($id)){
            $task = DB::table('academic_development')
                        ->where('acd_id',$id)
                        ->first();
        }

        
        $html="";
        $html =$html.'<table class="tb-view-modal" border="0" width="100%">'.
                '<tr>
                    <td width="30%" valign="top"><b>รหัสงาน</b></td><td valign="top">'.$task->acd_id.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ชื่อตำรา/เอกสาร</b></td><td valign="top">'.$task->acd_name.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ประเภทงาน</b></td><td valign="top">'.val::getACDCategory($task->acd_category).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ประกอบวิชา</b></td><td valign="top">'.$task->acd_subject.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>หน่วยกิต/สัปดาห์</b></td><td valign="top">'.$task->acd_creditPerWeek.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ภาคการศึกษาที่เริ่มทำ</b></td><td valign="top">'.$task->acd_semester.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>วันที่ดำเนินการ</b></td><td valign="top">'.f::dateThaiFull($task->acd_proceed_date).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>เอกสารแนบ</b></td><td>'.f::createTagLinkFile($task->acd_id).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>เพิ่มโดย</b></td><td valign="top">'.f::getFullName($task->personnel_id).'</td>
                </tr>
                ';
        
        $html =$html."</table>";
        //echo $html;
        //dd($html);
        return response()->json(['html'=>$html]);
        //return "Tess!!!!!!!!";
    }

    public function editFormACD($id=''){
        if($id !=''){
            if(f::checkPermise($id,'academic_development','acd_id')){
                $task = DB::table('academic_development')
                            ->where('acd_id',$id)
                            ->first();
                if(!isset($task)){ return view('errors.404');}
                $document = DB::table('documents')
                                ->where('task_id',$id)
                                ->get();
                $datas = array(
                    'task' => $task,
                    'documents' => $document
                );
                return view('pages.Academic-dev.editForm',$datas);
            }
        }
    }

    public function reportACD(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $acd = DB::table('academic_development')
                   ->whereBetween('acd_proceed_date',[$st_date,$end_date])
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('acd_proceed_date')
                   ->get();
            $data = array(
                'tasks' => $acd
            );
            return view('pages.Academic-dev.report',$data);
        }
        return view('pages.Academic-dev.report');
    }

    public function printACD(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $acd = DB::table('academic_development')
                   ->whereBetween('acd_proceed_date',[$st_date,$end_date])
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('acd_proceed_date')
                   ->get();
            $this->rpt = new ReportformController;
            $this->rpt->createReportACD($acd,$st_date,$end_date);
        }
    }


    /*********************************************************************/
    /*                     Academic_publication                          */
    /*********************************************************************/

    public function indexACP(){
        $dTable = DB::table('academic_publication')
                    ->where('personnel_id',Auth::user()->id)
                    ->orderBy('acp_proceed_date', 'desc')
                    ->get();
        $datas = array(
            'datas' => $dTable,
            'page'  => 'academic-pub'
        );
        //dd($datas);
        return view('pages.Academic-pub.table',$datas);
    }

    public function addFormACP($type=''){
        if($type!=''){
            return view('pages.Academic-pub.addForm',['type' => $type]);
        }

        return view('pages.Academic-pub.addForm',['type' => '1']);
    }

    public function editFormACP($id=''){
        if($id !=''){
            if(f::checkPermise($id,'academic_publication','acp_id')){
                $task = DB::table('academic_publication')
                            ->where('acp_id',$id)
                            ->first();
                if(!isset($task)){ return view('errors.404');}
                $document = DB::table('documents')
                                ->where('task_id',$id)
                                ->get();
                $datas = array(
                    'task' => $task,
                    'documents' => $document
                );
                return view('pages.Academic-pub.editForm',$datas);
            }
        }
    }

    public function viewACP($id){
        $datas;
        if(isset($id)){
            $task = DB::table('academic_publication')
                        ->where('acp_id',$id)
                        ->first();
        }

        $html="";
        $html =$html.'<table class="tb-view-modal" border="0" width="100%">'.
                '<tr>
                    <td width="30%" valign="top"><b>รหัสงาน</b></td><td valign="top">'.$task->acp_id.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ประเภทงานเผยแพร่ทางวิชาการ</b></td><td valign="top">'.val::ACPtaskCategory($task->acp_task_type).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ชนิดวารสาร</b></td><td valign="top">'.val::ACPCategory($task->acp_category).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ชื่อวารสาร</b></td><td valign="top">'.$task->acp_name.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>เรื่อง</b></td><td valign="top">'.$task->acp_title.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ฐานงานเผยแพร่</b></td><td valign="top">'.(($task->acp_base!='') ? $task->acp_base: '-').'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>หน้าที่</b></td><td valign="top">'.val::ACPRole($task->acp_user_role).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>วันที่ตอบรับ/นำเสนอ</b></td><td valign="top">'.f::dateThaiFull($task->acp_proceed_date).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>เอกสารแนบ</b></td><td valign="top">'.f::createTagLinkFile($task->acp_id).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>เพิ่มโดย</b></td><td valign="top">'.f::getFullName($task->personnel_id).'</td>
                </tr>
                ';
        
        $html =$html."</table>";
        //echo $html;
        //dd($html);
        return response()->json(['html'=>$html]);
        //return "Tess!!!!!!!!";
    }

    public function reportACP(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $acp = DB::table('academic_publication')
                   ->whereBetween('acp_proceed_date',[$st_date,$end_date])
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('acp_proceed_date')
                   ->get();
            $data = array(
                'tasks' => $acp
            );
            return view('pages.Academic-pub.report',$data);
        }
        return view('pages.Academic-pub.report');
    }

    public function printACP(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $acp = DB::table('academic_publication')
                   ->whereBetween('acp_proceed_date',[$st_date,$end_date])
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('acp_proceed_date')
                   ->get();
            $this->rpt = new ReportformController;
            $this->rpt->createReportACP($acp,$st_date,$end_date);
        }
    }

    /*********************************************************************/
    /*                     Acedemic service                              */
    /*********************************************************************/

    public function indexAS(){
        $dTable = DB::table('academic_service')
                  ->where('personnel_id',Auth::user()->id)
                  ->orderBy('as_start_date', 'desc')
                  ->get();
        $datas = array(
            'datas' => $dTable,
            'page'  => 'academic-service'
        );
        return view('pages.Academic-service.table',$datas);
    }

    public function viewAS($id){
        $datas;
        if(isset($id)){
            $task = DB::table('academic_service')
                        ->where('as_id',$id)
                        ->first();
        }

        $html="";
        $html =$html.'<table class="tb-view-modal" border="0" width="100%">'.
                '<tr>
                    <td width="30%" valign="top"><b>รหัสงาน</b></td><td valign="top">'.$task->as_id.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ชื่องาน</b></td><td valign="top">'.$task->as_name.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ประเภทงาน</b></td><td valign="top">'.f::getCategoryNameAS($task->as_category).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>วันที่เริ่ม</b></td><td valign="top">'.f::dateThaiFull($task->as_start_date).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>วันที่สิ้นสุด</b></td><td valign="top">'.f::dateThaiFull($task->as_end_date).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>เอกสารแนบ</b></td><td valign="top">'.f::createTagLinkFile($task->as_id).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>เพิ่มโดย</b></td><td valign="top">'.f::getFullName($task->personnel_id).'</td>
                </tr>
                ';
        
        $html =$html."</table>";
        //echo $html;
        //dd($html);
        return response()->json(['html'=>$html]);
        //return "Tess!!!!!!!!";
    }

    public function addFormAS(){
        return view('pages.Academic-service.addForm');
    }

    public function editFormAS($id=''){
        if($id !=''){
            if(f::checkPermise($id,'academic_service','as_id')){
                $task = DB::table('academic_service')
                            ->where('as_id',$id)
                            ->first();
                if(!isset($task)){ return view('errors.404');}
                $document = DB::table('documents')
                                ->where('task_id',$id)
                                ->get();
                $datas = array(
                    'task' => $task,
                    'documents' => $document
                );
                return view('pages.Academic-service.editForm',$datas);
            }
        }
    }

    public function reportAS(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $acs = DB::table('academic_service')
                   ->whereBetween('as_start_date',[$st_date,$end_date])
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('as_start_date')
                   ->get();
            $data = array(
                'tasks' => $acs
            );
            return view('pages.Academic-service.report',$data);
        }
        return view('pages.Academic-service.report');
    }

    public function printAS(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $tasks = DB::table('academic_service')
                   ->whereBetween('as_start_date',[$st_date,$end_date])
                   ->where('personnel_id',Auth::user()->id)
                   ->orderBy('as_start_date')
                   ->get();
            $this->rpt = new ReportformController;
            $this->rpt->createReportACS($tasks,$st_date,$end_date);  
        }
    }

   
    /*********************************************************************/
    /*                               Training                            */
    /*********************************************************************/
    public function indexTRN(){
        $dTable = DB::table('training')
                ->where('personnel_id',Auth::user()->id)
                ->orwhere('coTeacher','like','%'.Auth::user()->id.'%')
                ->orderBy('trn_start', 'desc')
                ->get();
        $datas = array(
            'datas' => $dTable,
            'page'  => 'training'
        );
        return view('pages.Training.table',$datas);
    }

    public function addFormTRN(){
        return view('pages.Training.addForm');
    }

    public function editFormTRN($id=''){
        if($id !=''){
            if(f::checkPermise($id,'training','trn_id')){
                $task = DB::table('training')
                            ->where('trn_id',$id)
                            ->first();
                if(!isset($task)){ return view('errors.404');}
                $document = DB::table('documents')
                                ->where('task_id',$id)
                                ->get();
                $coTeacher = DB::table('training')->select('coTeacher')
                            ->where('trn_id',$id)->first();
                
                $supTch = array();
                if(!$coTeacher->coTeacher==""){
                    $supTchId = explode(",",$coTeacher->coTeacher);
                    foreach($supTchId as $id){
                        $user= DB::table('users')->where('id',$id)->first();
                        array_push($supTch,$user);
                    }
                }
                $datas = array(
                    'task' => $task,
                    'documents' => $document,
                    'coTeacher' =>$supTch
                );
                return view('pages.Training.editForm',$datas);
            }
            else return view('pages.permise');
        }
    }

    public function viewTRN($id){
        $datas;
        if(isset($id)){
            $task = DB::table('training')
                        ->where('trn_id',$id)
                        ->first();
        }

        
        $html="";
        $html =$html.'<table class="tb-view-modal" border="0" width="100%">'.
                '<tr>
                    <td width="30%" valign="top"><b>รหัสงาน</b></td><td valign="top">'.$task->trn_id.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>ชื่องาน</b></td><td valign="top">'.$task->trn_name.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>สถานที่</b></td><td valign="top">'.$task->trn_address.'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>วันที่เริ่มอบรม</b></td><td valign="top">'.f::dateThaiFull($task->trn_start).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>วันที่สิ้นสุด</b></td><td valign="top">'.f::dateThaiFull($task->trn_end).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>จำนวนผบุคลากรผู้เข้าร่วมฝึกอบรม</b></td><td valign="top">'.f::countCoTeacher($task->coTeacher).'</td>
                </tr>
                <tr>
                    <td width="30%" valign="top"><b>เอกสารแนบ</b></td><td>'.f::createTagLinkFile($task->trn_id).'</td>
                </tr>
                <tr>
                <td width="30%" valign="top"><b>เพิ่มโดย</b></td><td valign="top">'.f::getFullName($task->personnel_id).'</td>
                </tr>
                ';
        
        $html =$html."</table>";
        //echo $html;
        //dd($html);
        return response()->json(['html'=>$html]);
        //return "Tess!!!!!!!!";
    }

    public function reportTRN(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $trn = DB::table('training')
                   ->whereBetween('trn_start',[$st_date,$end_date])
                   ->where('coTeacher','like','%'.Auth::user()->id.'%')
                   ->orderBy('trn_start')
                   ->get();
            $data = array(
                'tasks' => $trn
            );
            return view('pages.Training.report',$data);
        }
        return view('pages.Training.report');
    }

    public function printTRN(){
        if(isset($_GET['startTime']) and isset($_GET['endTime'])){
            $st_date = f::dateFormatDB($_GET['startTime']);
            $end_date = f::dateFormatDB($_GET['endTime']);
            $tasks = DB::table('training')
                   ->whereBetween('trn_start',[$st_date,$end_date])
                   ->where('coTeacher','like','%'.Auth::user()->id.'%')
                   ->orderBy('trn_start')
                   ->get();
        $this->rpt = new ReportformController;
        $this->rpt->createReportTRN($tasks,$st_date,$end_date);  
            
        }
    }


    


    public function showUserDocs($task){
        $tb =  f::getTableName($task);
        $docs = DB::table($tb->table)
                ->join('documents',$tb->table.".".$tb->id,'=','documents.task_id')
                ->where($tb->table.".".$tb->personnel_id,Auth::user()->id)
                ->orderBy('documents.lastest_updated','desc')
                ->get();
        $data = array(
            "taskName" => $tb->name,
            "docs" => $docs
        );
        //dd($docs);
        return view('pages.docs',$data);
    }

 

 


    /*************  Action before to page    *************/
    public function testdrive(){
      $f = Storage::disk('google')->put('drive.txt', 'test Drive');
      dd($f);
    }
    
    public function testupload(){
      return view('pages.test.upload');
    }

    public function getDataTable($tableName){
       return DB::table($tableName)
                  ->where('personnel_id',Auth::user()->id)
                  ->get();
    }

    public function createFolder($foldername){
       // dd($foldername);
        $this->client = new \Google_Client();
        $this->client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $this->client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
        $this->service = new \Google_Service_Drive($this->client);
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => $foldername,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array($this->rootFolderId)
        ]);
        $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);
        return $folder->id;
    }

    public function getFileById($taskid){
        $files= DB::table('documents')
                    ->where('task_id',$taskid)
                    ->get();
        return $files;
    }

    public function getFileByUid($uid,$task,Request $req){
        $html = "";
        $tb =  f::getTableName($task);
        $stDate = f::dateFormatDB($req->dateStart);//'2018-04-01';
        $endDate = f::dateFormatDB($req->dateEnd);//'2018-09-30';
        $taskList = f::getTaskListforReport($tb,$stDate,$endDate,$uid);
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
