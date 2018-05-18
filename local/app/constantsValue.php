<?php
/************************************************************************ */
/**************** SET VALUE PROMPT TO USE WITH DATABASE ***************** */
/************************************************************************ */
namespace App;

use Illuminate\Database\Eloquent\Model;

class constantsValue extends Model
{
    //
    public static function getTypeNameAS(){
        $type = [
            'ที่ปรึกษาวิชาชีพ/ งานทดสอบ/ วิเคราะห์/ งานออกแบบ/ สร้าง/ ซ่อมแซม/ งานฝึกอบรม/ วิทยากร',
            'ผู้ทรงคุณวุฒิพิจารณาผลงานทางวิชาการ/ บทความทางวิชาการ/ กรรมการสอบวิทยานิพนธ์',
            'คณะกรรมการระดับภาควิชา/ ระดับคณะ/ ระดับมหาวิทยาลัย/ กรรมการภายนอกเเละภาระงานอื่นๆ(งานประกันคุณภาพการศึกษาและหรืองานจัดการความรู้)'
        ];
        return $type;
    }

    public static function getTableAttr(){
        $tb = array(
            'research-devinv'=>[  
                "name" => "งานวิจัยและพัฒนาสิ่งประดิษฐ์",
                "table"=>"research_devinvention",
                "id" => "rsd_id",
                "st_date" =>"rsd_proceed_date",
                "personnel_id" => "personnel_id"
            ],
            'academic-dev'=>[  
                "name" => "งานพัฒนาวิชาการ",
                "table"=>"academic_development",
                "id" => "acd_id",
                "st_date" =>"acd_proceed_date",
                "personnel_id" => "personnel_id"
            ],
            'academic-pub'=>[  
                "name" => "งานเผยแพร่ผลงานทางวิชาการ",
                "table"=>"academic_publication",
                "id" => "acp_id",
                "st_date" =>"acp_proceed_date",
                "personnel_id" => "personnel_id"
            ],
            'academic-service'=>[  
                "name" => "งานบริการวิชาการและอื่นๆ",
                "table"=>"academic_service",
                "id" => "as_id",
                "st_date" =>"as_start_date",
                "personnel_id" => "personnel_id"
            ],
            'training'=>[
                "name" => "งานเข้ารับฝึกอบรม",
                "table"=>"training",
                "id" => "trn_id",
                "st_date" =>"trn_start",
                "personnel_id" => "personnel_id"
            ],
            'std-portfolio'=>[
                "name" => "ผลงานนักศึกษา",
                "table"=>"student_portfolio",
                "id" => "stp_id",
                "st_date" =>"stp_proceed_date",
                "personnel_id" => "save_by_personnel_id"
            ]
        );
        return $tb;
    } 

    public static function getUserPosition(){
        $pos = array(
            "คณบดี",
            "หัวหน้าภาควิชา",
            "ผู้ประสานงานฝ่ายวิชาการ",
            "ผู้ประสานงานฝ่ายวิจัยและบริการวิชาการ",
            "อาจารย์ประจำภาควิชา"
        );
        return $pos;
    }

    public static function userLevel(){
        $level = array(
            'admin' => "ผู้ดูแลระบบ",
            'dean' => 'คณบดี',
            'headofDp' => 'หัวหน้าภาควิชา',
            'teacher' => 'อาจารย์/บุคลากร',
            'support' => 'ฝ่ายสนับสนุน'
        );
        return (object)$level;
    }

    public static function getTitleName(){
        $name = array(
            'อาจารย์',
            'นาย',
            'นางสาว',
            'นาง',
            'ผศ.',
            'รศ.',
            'ศ.',
            'ดร.',
            'ผศ.ดร.',
            'รศ.ดร.',
            'ศ.ดร.'
        );
        return $name;
    }

    public static function getEstimateTime1(){
        $monthofFirstEstimateStart = 10;
        $monthofFirstEstimateEnd = 3;
        $chkCurrentMonth = date("m");
        $y = date('Y')+543;
        if($chkCurrentMonth >= $monthofFirstEstimateStart) {
            return "1 ตุลาคม ".$y." - 31 มีนาคม ".($y+1);
        }
        else if($chkCurrentMonth <= $monthofFirstEstimateEnd){
            return "1 ตุลาคม ".($y-1)." - 31 มีนาคม ".$y;
        }
        else return  "1 ตุลาคม ".($y-1)." - 31 มีนาคม ".$y;
        
    }
    
    public static function getEstimateTime2(){
        $monthofFirstEstimateStart = 4;
        $monthofFirstEstimateEnd = 9;
        $chkCurrentMonth = date("m");
        $y = date('Y')+543;
        if($chkCurrentMonth >= $monthofFirstEstimateStart and $chkCurrentMonth <= $monthofFirstEstimateEnd) {
            return "1 เมษายน ".$y." - 30 กันยายน ".$y;
        }
        else if($chkCurrentMonth > $monthofFirstEstimateEnd){
            return "1 เมษายน ".($y+1)." - 30 กันยายน ".($y+1);
        }
        else return  "1 เมษายน ".($y)." - 30 กันยายน ".($y);
        
    }
   
    public static function startyearEstimateTime1(){
        $monthofFirstEstimateStart = 10;
        $monthofFirstEstimateEnd = 3;
        $chkCurrentMonth = date("m");
        $y = date('Y');
        if($chkCurrentMonth >= $monthofFirstEstimateStart) {
            return "1/".$monthofFirstEstimateStart."/".$y;
        }
        else return  "1/".$monthofFirstEstimateStart."/".($y-1);
    }
    public static function endyearEstimateTime1(){
        $monthofFirstEstimateStart = 10;
        $monthofFirstEstimateEnd = 3;
        $chkCurrentMonth = date("m");
        $y = date('Y');
        if($chkCurrentMonth >= $monthofFirstEstimateStart ) {
            return "31/".$monthofFirstEstimateEnd."/".($y+1);
        }
        else return  "31/".$monthofFirstEstimateEnd."/".($y);
    }

    public static function startyearEstimateTime2(){
        $monthofFirstEstimateStart = 4;
        $monthofFirstEstimateEnd = 9;
        $chkCurrentMonth = date("m");
        $y = date('Y');
        if($chkCurrentMonth >= $monthofFirstEstimateStart and $chkCurrentMonth <= $monthofFirstEstimateEnd) {
            return "1/".$monthofFirstEstimateStart."/".$y;
        }
        else if($chkCurrentMonth > $monthofFirstEstimateEnd){
            return "1/".$monthofFirstEstimateStart."/".($y+1);
        }
        else return  "1/".$monthofFirstEstimateStart."/".$y;
    }
    public static function endyearEstimateTime2(){
        $monthofFirstEstimateStart = 4;
        $monthofFirstEstimateEnd = 9;
        $chkCurrentMonth = date("m");
        $y = date('Y');
        if($chkCurrentMonth >= $monthofFirstEstimateStart and $chkCurrentMonth <= $monthofFirstEstimateEnd) {
            return "30/".$monthofFirstEstimateEnd."/".$y;
        }
        else if($chkCurrentMonth > $monthofFirstEstimateEnd){
            return "30/".$monthofFirstEstimateEnd."/".($y+1);
        }
        else return  "30/".$monthofFirstEstimateEnd."/".$y;
    }

    public static function getRSDNameRole($id){
        $role = [
            'หัวหน้าโครงการ' ,'ผู้ร่วมวิจัย','ผู้ร่วมโครงการ'
        ];
        return $role[$id-1];
    }
    public static function getRSDCategory($id=''){
        $rsd_category = [
            'งานวิจัย' ,'งานพัฒนาสิ่งประดิษฐ์/งานสร้างสรรค์'
        ];
        if($id=='') return $rsd_category;
        return $rsd_category[$id-1];
    }
    public static function getAllRSDCategory(){
        $rsd_category = [
            "0" => ["name"=>"งานวิจัย" ,"full" => "งานวิจัย/พัฒนาสิ่งประดิษฐ์/งานควบคุมวิทยานิพนธ์และโครงงานพิเศษ/งานสร้างสรรค์"],
            "1" => ["name"=>"งานพัฒนาสิ่งประดิษฐ์/งานสร้างสรรค์" ,"full" => "งานวิจัย/พัฒนาสิ่งประดิษฐ์/งานควบคุมวิทยานิพนธ์และโครงงานพิเศษ/งานสร้างสรรค์"]
        ];
        return $rsd_category;
    }

    public static function getACDCategory($id){
        $acd_category = [
            'งานแต่ง/เรียบเรียงตำรา 3 หน่วยกิตขึ้นไป' ,
            'งานแต่ง/เรียบเรียงตำราน้อยกว่า 3 หน่วยกิต',
            'เอกสารประกอบการสอน/เอกสารคำสอน/หนังสือประกอบวิชา'
        ];
        return $acd_category[$id-1];
    }

    public static function ACDCategory(){
        $acd_category = [
            "1" => ["name"=>"งานแต่ง/เรียบเรียงตำรา 3 หน่วยกิตขึ้นไป","hr"=>6],
            "2" => ["name"=>"งานแต่ง/เรียบเรียงตำราน้อยกว่า 3 หน่วยกิต","hr"=>4],
            "3" => ["name"=>"เอกสารประกอบการสอน/เอกสารคำสอน/หนังสือประกอบวิชา","hr"=>4]
        ];
        return $acd_category;
    }

    public static function ACPtaskCategory($key=''){
        $acp_category = [
            'ผลงานวิจัย' ,
            'ผลงานพัฒนาสิ่งประดิษฐ์/งานสร้างสรรค์',
            'บทความทางวิชาการ'
        ];
        if($key==''){
            return $acp_category;
        }
        else{
            return $acp_category[$key-1];
        }
        
    }

    public static function ACPCategory($key=''){
        $acp_category = [
            "11" => ["name"=>"วารสารวิชาการนานาชาติ"],
            "12" => ["name"=>"งานประชุมวิชาการนานาชาติ"],
            "13" => ["name"=>"วารสารวิชาการในประเทศ"],
            "14" => ["name"=>"งานประชุมวิชาการในประเทศ"],
            "21" => ["name"=>"วารสารวิชาการนานาชาติ"],
            "22" => ["name"=>"งานประชุมวิชาการนานาชาติ"],
            "23" => ["name"=>"วารสารวิชาการในประเทศ"],
            "24" => ["name"=>"งานประชุมวิชาการในประเทศ"],
            "25" => ["name"=>"นำไปใช้ประโยชน์ในชุมชน องค์การต่างๆ สังคมหรือประเทศ"],
            "31" => ["name"=>"วารสารวิชาการนานาชาติ"],
            "32" => ["name"=>"วารสารวิชาการในประเทศ"],
        ];
        if($key==''){
            return $acp_category;
        }
        else{
            return $acp_category[$key]['name'];
        }
        
        
    }

    public static function ACPRole($key=''){
        $acp_role = [
            "99" => ["name"=>"-"],
            "11" => ["name"=>"หัวหน้าโครงการ/นักวิจัย"],
            "12" => ["name"=>"ผู้ร่วมวิจัย"],
            "21" => ["name"=>"หัวหน้าโครงการ"],
            "22" => ["name"=>"ผู้ร่วมโครงการ"],
            
        ];
        if($key==''){
            return $acp_role;
        }
        else{
            return $acp_role[$key]['name'];
        }
        
    }



}
