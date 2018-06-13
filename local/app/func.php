<?php
 
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use App\constantsValue as val;
class func extends Model
{
    

    public static function genId($lastId){
        date_default_timezone_set('Asia/Bangkok');
        $idtask;
        $yy = substr(substr(date('Y-m-d'),0,4)+543,2,4);
        if(empty($lastId) or $yy!=substr($lastId,0,2)) $idtask = '1001';
        else $idtask = substr($lastId,2)+1;
        return  $yy.$idtask ;
    }

    public static function cutStr($str,$len,$tail){
        
        if(mb_strlen($str) >$len)
            return mb_substr($str,0,$len).$tail;
        return $str;
    }
    
    public static function createTagLinkFile($taskID){
        $documents = DB::table('documents')
                    ->where('task_id',$taskID)
                    ->get();
        $result="";
        foreach($documents as $document )
            $result = $result."<span class='label label-info label-tag' ><a style='color:black' onClick='window.open(\"https://drive.google.com/open?id=".$document->doc_id."\")'>".$document->doc_name."</a></span><br>";
        return $result;
    }
    public static function test(){
        return "<span class='label label-info label-tag' >555</span><br>";
    }
    public static function dateFormatDB($dateHtml){
        $dateArr = explode("/",$dateHtml);
        return ($dateArr[2]-543)."-".$dateArr[1]."-".$dateArr[0];
    }
    public static function dateDBtoBC($dateHtml){
        $dateArr = explode("-",$dateHtml);
        return $dateArr[2]."/".$dateArr[1]."/".$dateArr[0];
    }
    public static function dateDBtoBE($dateHtml){
        $dateArr = explode("-",$dateHtml);
        return $dateArr[2]."/".$dateArr[1]."/".($dateArr[0]+543);
    }
    public static function dateFormatBC($dateHtml){
        $dateArr = explode("/",$dateHtml);
        return $dateArr[0]."/".$dateArr[1]."/".($dateArr[2]-543);
    }
 

    public static function dateThai($date){
        $thai_month_arr=array(
            "0"=>"",
            "1"=>"มกราคม",
            "2"=>"กุมภาพันธ์",
            "3"=>"มีนาคม",
            "4"=>"เมษายน",
            "5"=>"พฤษภาคม",
            "6"=>"มิถุนายน", 
            "7"=>"กรกฎาคม",
            "8"=>"สิงหาคม",
            "9"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"                 
        );
        $dd = mb_substr($date,8,2)+0;
        $mm = mb_substr($date,5,2)+0;
        $yyyy = mb_substr($date,0,4)+543;
        return $dd." ".$thai_month_arr[$mm];
    }
    public static function dateThaiFull($date){
        $thai_month_arr=array(
            "0"=>"",
            "1"=>"มกราคม",
            "2"=>"กุมภาพันธ์",
            "3"=>"มีนาคม",
            "4"=>"เมษายน",
            "5"=>"พฤษภาคม",
            "6"=>"มิถุนายน", 
            "7"=>"กรกฎาคม",
            "8"=>"สิงหาคม",
            "9"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"                 
        );
        $dd = mb_substr($date,8,2)+0;
        $mm = mb_substr($date,5,2)+0;
        $yyyy = mb_substr($date,0,4)+543;
        return $dd." ".$thai_month_arr[$mm]." ".$yyyy;
    }
    public static function yearThai($date){
        $yyyy = mb_substr($date,0,4)+543;
        return $yyyy;
    }

    public static function userList(){
        return DB::table('users')
                   ->orderBy('name', 'ASC') 
                   ->orderBy('lastname', 'ASC') 
                   ->get();
    }

    public static function getFullName($id){
        $user = DB::table('users')->where('id',$id)->first();
        return $user->title_name."&nbsp;".$user->name." &nbsp;&nbsp;".$user->lastname;
    }

    public static function getDepartment(){
        $dp = DB::table('departments')->get();
        return $dp;
    }
    public static function getFaculty($depid = ''){
        if($depid==''){
            return DB::table('facultys')->get();
        }else{
            $dp = DB::table('departments')
            ->join('facultys', 'departments.faculty_id', '=', 'facultys.faculty_id')
            ->where('department_id',$depid)->first();
            return $dp;
        } 
            
    }
    public static function getFacultyId($depId){
        $fac = DB::table('departments')->select('faculty_id')->where('department_id',$depId)->first();
        return $fac->faculty_id;
    }
    public static function getDeparmentName($id){
        $dp = DB::table('departments')->select('department_name')->where('department_id',$id)->first();
        return $dp->department_name;
    }
    public static function getFacultyName($id){
        $dp = DB::table('departments')
                ->select('faculty_name')
                ->join('facultys', 'departments.faculty_id', '=', 'facultys.faculty_id')
                ->where('department_id',$id)->first();
        return $dp->faculty_name;
    }
    public static function getFacultyNameUseFacId($id){
        $fac = DB::table('facultys')
                ->select('faculty_name')
                ->where('faculty_id',$id)->first();
        return $fac->faculty_name;
    }
    public static function getInstitutionName(){
        return "มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ";
    }

    public static function countCoTeacher($coTch){
        if($coTch=="") return 1;
        $teacher = explode(",",$coTch);
        return count($teacher);
    }
    public static function checkPermise($tskid,$table,$field){
        $user = DB::table($table)->where($field,$tskid)->first();
        if(isset($user)){
            if($user->personnel_id==Auth::user()->id) return true;
            else return false;
        }
        return true;
    }

    public static function getCategoryNameAS($categoryId){
        $catName = val::getTypeNameAS();
        return $catName[$categoryId-1];
    }

    public static function getImgFileType($fileType){
        $fileImg = array(
            ['type'=>'text/plain','typeImg'=>'txt.png'],
            ['type'=>'image/gif','typeImg'=>'jpg.png'],
            ['type'=>'text/css','typeImg'=>'css.png'],
            ['type'=>'text/x-c++','typeImg'=>'css.png'],
            ['type'=>'text/csv','typeImg'=>'csv.png'],
            ['type'=>'application/msword','typeImg'=>'doc.png'],
            ['type'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document','typeImg'=>'doc.png'],
            ['type'=>'text/html','typeImg'=>'html.png'],
            ['type'=>'application/octet-stream','typeImg'=>'exe.png'],
            ['type'=>'application/iso-image','typeImg'=>'iso.png'],
            ['type'=>'application/javascript','typeImg'=>'javascript.png'],
            ['type'=>'image/jpeg','typeImg'=>'jpg.png'],
            ['type'=>'application/json','typeImg'=>'json-file.png'],
            ['type'=>'application/pdf','typeImg'=>'pdf.png'],
            ['type'=>'image/png','typeImg'=>'png.png'],
            ['type'=>'application/vnd.ms-powerpoint','typeImg'=>'ppt.png'],
            ['type'=>'application/vnd.openxmlformats-officedocument.presentationml.presentation','typeImg'=>'ppt.png'],
            ['type'=>'application/vnd.ms-excel','typeImg'=>'xls.png'],
            ['type'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','typeImg'=>'xls.png']
            
        );
        for($i =0 ;$i<count($fileImg);$i++){
           if($fileType==$fileImg[$i]['type']){
               return '<img width="40" src="'.url('img/ico-file/'.$fileImg[$i]["typeImg"]).'">';
            }
        }
       return '<img width="40" src="'.url('img/ico-file/file.png').'">';
    }
    
    public static function getFileById($taskid){
        $files= DB::table('documents')
                ->where('task_id',$taskid)
                ->get();
        $html ="";
        foreach($files as $key => $file){
            $html=$html."- <a class='rp-link-file' onClick='window.open(\"https://drive.google.com/open?id=".$file->doc_id."\")' title='".$file->doc_name."'>".self::cutStr($file->doc_name,18,"...")."</a><br>";
        }
        return $html;
    }

    public static function getTableName($task){
        $table = val::getTableAttr();
        foreach($table as $key => $tb){
            if($task==$key){
                return (object) $tb;
            }
        }

    }

    public static function getTaskListforReport($tb,$stdate,$enddate,$uid){
        if($tb->table == "research_devinvention"){
            $rsd = DB::table('research_devinvention')
                        ->select($tb->id." as id", 'rsd_proceed_date','rsd_end_proceed_date')
                        ->where('personnel_id',$uid)
                        ->orderBy($tb->st_date)
                        ->get();
                $tsk = array();
                $tsk = self::checkRSDduration($rsd,$stdate,$enddate);
                return $tsk;
            
        }
        elseif($tb->table == "training"){
            return DB::table($tb->table)
                        ->select($tb->id." as id" )
                        ->whereBetween($tb->st_date,[$stdate,$enddate])
                        ->where('coTeacher','like','%'.$uid.'%')
                        ->orderBy($tb->st_date)
                        ->get();
        }
        else{
            return DB::table($tb->table)
            ->select($tb->id." as id" )
            ->whereBetween($tb->st_date,[$stdate,$enddate])
            ->where('personnel_id',$uid)
            ->orderBy($tb->st_date)
            ->get();
        }
    }
    public static function getSTPListforReport($tb,$stdate,$enddate,$level){
        return DB::table($tb->table)
               ->select($tb->id." as id" )
               ->whereBetween($tb->st_date,[$stdate,$enddate])
               ->where('department_id',$uid)
               ->orderBy($tb->st_date)
               ->get();
    }
    public static function getTaskListforFaculty($tb,$stdate,$enddate,$facid){
        if($tb->table !="research_devinvention"){
        return DB::table($tb->table)
                ->select($tb->id." as id" )
                ->leftjoin('users',$tb->table.'.personnel_id','=','users.id')
                ->leftjoin('departments','users.department_id','=','departments.department_id')
                ->whereBetween($tb->st_date,[$stdate,$enddate])
                ->where('departments.faculty_id',$facid)
                ->orderBy($tb->st_date)
                ->get();
        }
        else{
            $tasks =  DB::table($tb->table)
                    ->select($tb->id." as id", 'rsd_proceed_date','rsd_end_proceed_date')
                    ->leftjoin('users',$tb->table.'.personnel_id','=','users.id')
                    ->leftjoin('departments','users.department_id','=','departments.department_id')
                    ->where('departments.faculty_id',$facid)
                    ->orderBy($tb->st_date)
                    ->get();
            $tasks = self::checkRSDduration($tasks,$stdate,$enddate);
            return $tasks;
        }      
    }
    public static function getTaskListforDepartment($tb,$stdate,$enddate,$depid){
        if($tb->table !="research_devinvention"){
        return DB::table($tb->table)
                ->select($tb->id." as id" )
                ->leftjoin('users',$tb->table.'.personnel_id','=','users.id')
                ->whereBetween($tb->st_date,[$stdate,$enddate])
                ->where('users.department_id',$depid)
                ->orderBy($tb->st_date)
                ->get();
        }
        else{
            $tasks =  DB::table($tb->table)
                    ->select($tb->id." as id", 'rsd_proceed_date','rsd_end_proceed_date')
                    ->leftjoin('users',$tb->table.'.personnel_id','=','users.id')
                    ->where('users.department_id',$depid)
                    ->orderBy($tb->st_date)
                    ->get();
            $tasks = self::checkRSDduration($tasks,$stdate,$enddate);
            return $tasks;
        }      
        
    }
    public static function getFileData($taskid){
        $files= DB::table('documents')
                    ->where('task_id',$taskid)
                    ->get();
        return $files;
    }

    public static function showFileinModal($fileId,$order){
        $file = DB::table('documents')
                ->where('doc_id',$fileId)
                ->first();
        return "<tr id='showfile".$order."' onClick='window.open(\"https://drive.google.com/open?id=".$file->doc_id."\")'  ><td class='text-center'>".self::getImgFileType($file->doc_type)."</td><td>".substr($file->doc_name,11)."</td><td>".self::getTaskName($file->task_id)."</td></tr>";
    }

    public static function showFileinModal2($fileId,$order){
        $file = DB::table('documents')
                ->where('doc_id',$fileId)
                ->first();
        return "<tr id='showfile".$order."' onClick='window.open(\"https://drive.google.com/open?id=".$file->doc_id."\")'  ><td class='text-center'>".self::getImgFileType($file->doc_type)."</td><td>".substr($file->doc_name,11)."</td></tr>";
    }

    public static function getTaskName($task_id){
        $a = array();
        if(mb_substr($task_id,0,3)=="RSD") {
            $a = [
                'table' => 'research_devinvention',
                'id' => 'rsd_id',
                'name' => 'rsd_name'
            ];
        }
        if(mb_substr($task_id,0,3)=="ACD") {
            $a = [
                'table' => 'academic_development',
                'id' => 'acd_id',
                'name' => 'acd_name'
            ];
        }
        if(mb_substr($task_id,0,3)=="ACP") {
            $a = [
                'table' => 'academic_publication',
                'id' => 'acp_id',
                'name' => 'acp_title'
            ];
        }
        if(mb_substr($task_id,0,3)=="TRN") {
            $a = [
                'table' => 'training',
                'id' => 'trn_id',
                'name' => 'trn_name'
            ];
        }
        if(mb_substr($task_id,0,3)=="ACS") {
            $a = [
                'table' => 'academic_service',
                'id' => 'as_id',
                'name' => 'as_name'
            ];
        }
        $name = DB::table($a['table'])
                ->select($a['name']." as name")
                ->where($a['id'],$task_id)
                ->first();
        return $name->name;
    }

    public static function isOtherPosition($u_pos){
        $pos = val::getUserPosition();
        for($i=0;$i<count($pos);$i++){
            if($pos[$i]==$u_pos) return false;
        }
        return true;
    }
    public static function isOtherTitleName($u_title_name){
        $ttname = val::getTitleName();
        for($i=0;$i<count($ttname);$i++){
            if($ttname[$i]==$u_title_name) return false;
        }
        return true;
    }

    public static function getAllDepSameUserFac($depId){
        $dep = DB::table('departments')->select('faculty_id')->where('department_id',$depId)->first();
        return DB::table('departments')->where('faculty_id',$dep->faculty_id)->get();
    }

    public static function countUserTask($uid){
        $table =val::getTableAttr();
        $arr =array();
        foreach($table as $key => $tb){
            if($tb['table']=="training"){
                $cnt = DB::table($tb['table'])->where('coTeacher','like','%'.$uid.'%')->count();
                array_push($arr,$cnt);
            }
            elseif($tb['table']=="student_portfolio"){
            }
            else{
                $cnt = DB::table($tb['table'])->where('personnel_id',$uid)->count();
                array_push($arr,$cnt);
            }
        }
        return $arr;
    }
    public static function countAllTask(){
        $table =val::getTableAttr();
        $arr =array();
        foreach($table as $key => $tb){
            $cnt = DB::table($tb['table'])->count();
            array_push($arr,$cnt);
        }
        return $arr;
    }

    public static function countDoc($uid,$task){
        $tb = func::getTableName($task);
        $task_id = DB::table($tb->table)->select($tb->id." as id")->where('personnel_id',$uid)->get();
        $count=0;
        foreach($task_id as $t){
            $count += DB::table('documents')->where('task_id',$t->id)->count();
        }
        return $count;
    }
    public static function countAllDoc($task){
        $tb = func::getTableName($task);
        $task_id = DB::table($tb->table)->select($tb->id." as id")->get();
        $count=0;
        foreach($task_id as $t){
            $count += DB::table('documents')->where('task_id',$t->id)->count();
        }
        return $count;
    }
    public static function countALLAlbum($task){
        $tb = func::getTableName($task);
        $task_id = DB::table($tb->table)->select($tb->id." as id")->get();
        $count=0;
        foreach($task_id as $t){
            $count += DB::table('albums')->where('task_id',$t->id)->count();
        }
        return $count;
    }

    public static function genFacId(){
        $lastId = DB::table('facultys')
                  ->select('faculty_id')
                  ->orderBy('faculty_id', 'desc')
                  ->first();
        if(isset($lastId)){
            $id ='f';
            $newid = substr($lastId->faculty_id,1,3)+1;
            for($i=3;$i>0;$i--){
                if(strlen($newid)>$i) $id = $id."0";
            }
            return $id.$newid;
        }
        else return 'f001';
    }

    public static function genDepId(){
        $lastId = DB::table('departments')
                  ->select('department_id')
                  ->orderBy('department_id', 'desc')
                  ->first();
        if(isset($lastId)){
            $id ='d';
            $newid = substr($lastId->department_id,1,6)+1;
            for($i=6;$i>0;$i--){
                if(strlen($newid)<$i) $id = $id."0";
            }
            return $id.$newid;
        }
        else return 'd000001';
    }
    
    public static function albumName($id){
        $album = DB::table('albums')
                 ->select('album_name')
                 ->where('album_id',$id)
                 ->first();
        return $album->album_name;
    }
    public static function superviserList($spvList){
        $spvArr = explode(",",$spvList);
        $html = '';
        for($i=0;$i<count($spvArr);$i++){
            $spv = DB::table('users')->where('id',$spvArr[$i])->first();
            $html = $html."<p>".($i+1).". ".$spv->title_name." ".$spv->name." ".$spv->lastname."</p>";
        }
        return $html;
    }
    public static function stdList($stdList){
        $stdArr = explode(",",$stdList);
        (count($stdArr)>10)? $len = 10: $len =count($stdArr);
        $html = '';
        for($i=0;$i<$len;$i++){
            $html = $html."<p>".($i+1).". ".$stdArr[$i]."</p>";
        }
        
        return $html;
    }
    public static function getImageAlbum($album_id){
        $img = DB::table('images')
               ->select('image_id')
               ->where('album_id',$album_id)
               ->first();
        return 'https://drive.google.com/uc?id='. $img->image_id; 
    }

    public static function csvExtractStd($csv){
        $stdList = array();
        $name = $csv->getClientOriginalName();
        $row = 1;
        if (($handle = fopen($csv->getPathName(), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                if($row>1){
                    array_push($stdList,$data[1]);
                }
                $row++;
            }
            fclose($handle);
        }
       return  $stdList;
    }

    public static function countImg($albumid){
        return DB::table('images')
                ->where('album_id',$albumid)
                ->count();
    }   
    public static function getAlumid($id){
        $album = DB::table('albums')->where('album_id',$id)->first();
        if(isset($album)) return $album->album_id;
    }

    public static function checkRSDduration($rsd,$st_date,$end_date){
        $tsk = array();
        foreach($rsd as $r){
            $check = false;
            // ถ้าวันเริ่มอยู่ในช่วง
            if($r->rsd_proceed_date >= $st_date and $r->rsd_proceed_date <= $end_date){
                $check = true;
                //echo $r->rsd_proceed_date.' - '.$r->rsd_end_proceed_date ." = อยู่ในช่วง<br>";
            }
            // ถ้าวันเริ่ม "ไม่" อยู่ในช่วง
            else{
                // ถ้าวันเริ่ม < ในช่วงเริ่มต้น
                if($r->rsd_proceed_date < $st_date){
                    // วันสิ้นสุดต้องมากกว่า ช่วงเริ่มต้น
                    if($r->rsd_end_proceed_date >= $st_date ){
                        //echo $r->rsd_proceed_date.' - '.$r->rsd_end_proceed_date ." = อยู่ในช่วง<br>";
                        $check = true;
                    }  
                    else{
                        //echo $r->rsd_proceed_date.' - '.$r->rsd_end_proceed_date ." = ไม่อยู่ในช่วง<br>";
                        $check = false;
                    } 
                }
            }
            if($check){
                array_push($tsk,$r);
            }
        }
        return $tsk;
    }

    public static function isOfficer(){
        if(Auth::user()->user_level=="support" and Auth::user()->isadm_stp == "yes") return true;
        else return false;
    }

    public static function getYearSemester(){
        $y = date("Y")+543;
        $ymin = $y-5;
        $yArr = array();
        for($i=0;$i<10;$i++){
            $yArr[$i]= $ymin+$i;
        }
        return $yArr;
    }
}
