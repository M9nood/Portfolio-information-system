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


class StudentPortfolioController extends Controller
{
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

     /*********************************************************************/
    /*                          Student Portfolio                        */
    /*********************************************************************/

    public function indexSTP(){
        $dTable = DB::table('student_portfolio')
                ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
                ->where('users.department_id',Auth::user()->department_id)
                ->orderBy('stp_proceed_date', 'desc')
                ->get();
        //dd($dTable );
        $datas = array(
            'datas' => $dTable,
            'page'  => 'std-portfolio'
        );
        return view('pages.Student-port.table',$datas);
    }

    public function viewSTP($id){
        if($id !=''){
            $stp = DB::table('student_portfolio')
                    ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
                    ->where('users.department_id',Auth::user()->department_id)
                    ->where('stp_id',$id)->first();
            if(isset($stp)){
                $datas = array(
                    'stp' => $stp
                );
                return view('pages.Student-port.view',$datas);
            }
            else echo "คุณไม่มีสิทธิเข้าถึงผลงานนี้.";
        }
    }

    public function addFormSTP(){
        if(Auth::user()->isadm_stp!="yes"){
            return redirect('/permise');
        } 
        else
            return view('pages.Student-port.addForm');
    }

    public function editFormSTP($id=''){
        if(Auth::user()->isadm_stp!="yes"){
            return redirect('/permise');
        } 
        else {
            if($id !=''){
                    $task = DB::table('student_portfolio')
                                ->where('stp_id',$id)
                                ->first();
                    $img = DB::table('images')
                                    ->where('album_id',$task->album_id)
                                    ->get();
                    $std = DB::table('student_portfolio')->select('student_name_list')
                                 ->where('stp_id',$id)->first();
                    
                    $superviser = DB::table('student_portfolio')->select('superviser_id')
                                ->where('stp_id',$id)->first();
                    
                    
                    $supTch = array();
                    if(!$superviser->superviser_id==""){
                        $supTchId = explode(",",$superviser->superviser_id);
                        foreach($supTchId as $id){
                            $user= DB::table('users')->where('id',$id)->first();
                            array_push($supTch,$user);
                        }
                    }
                    $stdlist = explode(",",$std->student_name_list);
                    $datas = array(
                        'task' => $task,
                        'images' => $img,
                        'stdlist' => $stdlist,
                        'superviser' =>$supTch
                    );
                    //dd($datas);
                    return view('pages.Student-port.editForm',$datas);
                
            }
            else echo "คุณไม่มีสิทธิเข้าถึงผลงานนี้.";
        }
        
    }

    public function stdList($id=''){
        $std = DB::table('student_portfolio')->select('student_name_list')
                   ->where('stp_id',$id)->first();
        $stdlist = explode(",",$std->student_name_list);
        echo '
            <link href="'.url("css/custom-style.css").'" rel="stylesheet">
            <div align="center">
                <h3 style="font-family:THSarabunNew;font-size:24px;font-weight: bold;">รายชื่อนักศึกษา</h3>
             <div>';
        echo '<table width="100%" border=2  cellspacing=0>
             <thead>
                <tr style="font-family:THSarabunNew;font-size:20px;font-weight: bold;">
                    <td style="padding:5px 10px" align="center" width="20%">ลำดับที่</td>
                    <td style="padding:5px 10px" align="center">ชื่อ - สกุล</td>
                </tr>  
             </thead>
             <tbody>';
        foreach($stdlist as $key=>$std){
        echo '<tr style="font-family:THSarabunNew;font-size:20px;">
                <td align="center">'.($key+1).'</td>
                <td style="padding:5px 10px">'.$std.'</td>
              </tr>';
        }
        echo '</tbody></table >';
        
    }

    public function reportSTP(Request $request){
        if(isset($request->fac) and isset($_GET['dep'])){
            if(isset($_GET['startTime']) and isset($_GET['endTime'])){
                $st_date = f::dateFormatDB($request->startTime);
                $end_date = f::dateFormatDB($request->endTime);
                $selectLvl='';
                $selectId='';
                if(isset($_GET['dep']) and $_GET['dep'] !='' ){
                    $stp = DB::table('student_portfolio')
                        ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
                        ->where('users.department_id',$_GET['dep'])
                        ->whereBetween('stp_proceed_date',[$st_date,$end_date])
                        ->orderBy('stp_proceed_date')
                        ->get();
                        $selectLvl ='dep';
                        $selectId=$_GET['dep'];
                }
                else{
                    $stp = DB::table('student_portfolio')
                        ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
                        ->join('departments','departments.department_id','=','users.department_id')
                        ->where('departments.faculty_id',$request->fac)
                        ->whereBetween('stp_proceed_date',[$st_date,$end_date])
                        ->orderBy('stp_proceed_date')
                        ->get();
                        $selectLvl ='fac';
                        $selectId=$request->fac;
                }
                $data = array(
                    'tasks' => $stp,
                    'selectLvl' =>$selectLvl,
                    'selectId'=>$selectId,
                );
                return view('pages.Student-port.report',$data);
            }
        }
        return view('pages.Student-port.report');
    }

    public function printSTP(Request $request){
        if(isset($request->fac) and isset($_GET['dep'])){
            if(isset($_GET['startTime']) and isset($_GET['endTime'])){
                $st_date = f::dateFormatDB($request->startTime);
                $end_date = f::dateFormatDB($request->endTime);
                $selectLvl='';
                $selectId='';
                if(isset($_GET['dep']) and $_GET['dep'] !='' ){
                    $stp = DB::table('student_portfolio')
                        ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
                        ->where('users.department_id',$_GET['dep'])
                        ->whereBetween('stp_proceed_date',[$st_date,$end_date])
                        ->orderBy('stp_proceed_date')
                        ->get();
                    $selectLvl ='dep';
                    $selectId=$_GET['dep'];
                }
                else{
                    $stp = DB::table('student_portfolio')
                        ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
                        ->join('departments','departments.department_id','=','users.department_id')
                        ->where('departments.faculty_id',$request->fac)
                        ->whereBetween('stp_proceed_date',[$st_date,$end_date])
                        ->orderBy('stp_proceed_date')
                        ->get();
                        $selectLvl ='fac';
                        $selectId=$request->fac;
                }
                $this->rpt = new ReportformController;
                $this->rpt->createReportSTP($stp,$st_date,$end_date,$selectLvl,$selectId);  
            }
        }
        return view('pages.Student-port.report');
    }
    

    public function getAlbumforReport($level,Request $req){
        $html = "";
        $tb =  f::getTableName('std-portfolio');
        $stDate = '2017-10-01';//f::dateFormatDB($req->dateStart);// ;
        $endDate = '2018-03-31';//f::dateFormatDB($req->dateEnd);////
        $taskList = f::getSTPListforReport($tb,$stDate,$endDate,$level);
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

    public function showAlbums(){
        $albums= DB::table('albums')
            ->join('student_portfolio','albums.album_id','=','student_portfolio.album_id')
            ->join('users','users.id','=','student_portfolio.save_by_personnel_id')
            ->where('users.department_id',Auth::user()->department_id)
            ->orderBy('stp_proceed_date', 'desc')
            ->get();
        $datas = array(
                'albums' => $albums,
                'page'  => 'std-portfolio'
            );
        return view('pages.album.index',$datas);
    }
    
    public function showAlbum($albumid){
        $album = DB::table('albums')
                ->where('album_id',$albumid)
                ->first();
        $imgs = DB::table('images')
                ->where('album_id',$albumid)
                ->get();
        $datas = array(
            'album' => $album ,
            'imgs' => $imgs,
            'page'  => 'std-portfolio'
        );
        return view('pages.album.gallery',$datas);

    }

}
