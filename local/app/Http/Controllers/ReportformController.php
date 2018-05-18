<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\func as f;
use App\constantsValue as val;
use App\Http\Controllers\PDFController ;
use \Input as Input;
use Illuminate\Support\Facades\Storage;
use Session;
use Auth;

class ReportformController extends Controller
{
    //
    protected $pdf;

    public function __construct(){
        $this->pdf= new PDFController;
    }

    public function createReportTRN($tasks,$st_date,$end_date,$selectLvl='',$selectId=''){
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
        $html='';
        $html =$html.'<p style="font-size:18px;text-align:center"><b>รายงานเข้ารับฝึกอบรม</b></p>
                        <p style="font-size:16px;display:inline-block" >'
                            .$title_centent.
                        '<table border="1" cellpadding="2" width="100%" class="tb-report">
                            <tr style="background-color: #99b3e6;">
                                <th width="8%" style="vertical-align:middle;text-align:center;padding:8px"> <br><br>ลำดับที่</th>'.
                                '<th width="49%" style="text-align:center"> <br><br>งานเข้ารับฝึกอบรม</th>'.
                                '<th width="16%" style="text-align:center"> <br><br>วันที่เริ่มอบรม</th>
                                <th width="16%" style="text-align:center"> <br><br>วันที่สิ้นสุด</th>
                                <th width="11%" style="text-align:center"> จำนวน <br>บุคลากรที่<br>เข้าร่วม</th>
                            </tr>';
                            foreach( $tasks as $key => $task ){
            $html =$html.'<tr>
                            <th width="8%" style="text-align:center;padding:8px">'.($key+1).'</th>
                            <th width="49%" style="text-align:laft"> '.$task->trn_name.'</th>
                            <th width="16%" style="text-align:center"> '.f::dateDBtoBE($task->trn_start).'</th>
                            <th width="16%" style="text-align:center"> '.f::dateDBtoBE($task->trn_end).'</th>
                            <th width="11%" style="text-align:center"> '.f::countCoTeacher($task->coTeacher).'</th>
                        </tr>';
                            }
            $html =$html.'</table>';
            //echo strlen($html)."<br>";
            $html = preg_replace('#(?(?!<!--.*?-->)(?: {2,}|[\r\n\t]+)|(<!--.*?-->))#s', '$1', $html);
            //echo $html;
           $this->pdf->setHeaderContent('ผลงานนักศึกษา');
           $this->pdf->createPDF($html);
    }

    public function createReportACS($tasks,$st_date,$end_date,$selectLvl='',$selectId=''){
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
        
        $html="";
        $html=$html.'<p style="font-size:18px;text-align:center" ><b>รายงานบริการวิชาการและอื่นๆ</b></p>
                <p style="font-size:16px;display:inline-block;margin-left:-10px;" >'
                .$title_centent.
                '<table border="1" cellpadding="3" width="100%" class="tb-report" >
                <tr style="background-color: #99b3e6;">
                    <th width="8%" style="vertical-align:middle;text-align:center">ลำดับที่</th>
                    <th width="72%" style="vertical-align:middle;text-align:center">งานบริการวิชาการและอื่นๆ</th>
                    <th width="20%" style="vertical-align:middle;text-align:center">วันที่ดำเนินการ</th>
                </tr>';
            for($i =0;$i<count($typeAS);$i++){
            $isFirst = true;$sub = 0; 
             if($isFirst){ 
                $html=$html.'<tr valign="top">
                    <th width="8%" style="vertical-align:middle;text-align:center"> '.($i+1).'</th>
                    <th width="72%" style="vertical-align:middle;line-height: 1;">'.$typeAS[$i].'</th>
                    <th width="20%" style="vertical-align:middle;text-align:center"></th>
                    </tr>';
              $isFirst = false; 
            }
            foreach($tasks as $key => $task){
            if(($i+1) == $task->as_category){
                $sub++;
                $html=$html.'<tr >
                  <td valign="top" border="0" ></td>
                  <td valign="top" style="line-height: 1;"> '.($i+1).".".$sub." ".$task->as_name.'</td>
                  <td valign="top" align="center">'.f::dateDBtoBE($task->as_start_date).'</td>
                </tr>';
              }
            }
            if(!$isFirst){
                $html=$html.'<tr cellpadding="-2">
                <td valign="top" colspan="2"></td>
                <td valign="top"></td>
                <td valign="top"></td>
                </tr>';
            }
          }
          $html=$html.'</table>';

          $html = preg_replace('#(?(?!<!--.*?-->)(?: {2,}|[\r\n\t]+)|(<!--.*?-->))#s', '$1', $html);
          //echo strlen($html)."<br>";
         $this->pdf->setHeaderContent('งานบริการวิชาการและอื่นๆ');
         $this->pdf->createPDF($html);
    }

    public function createReportRSD($tasks,$st_date,$end_date,$selectLvl='',$selectId=''){
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
        
        $html="";
        $html=$html.'<p style="font-size:18px;text-align:center" ><b>รายงานงานวิจัยและพัฒนาสิ่งประดิษฐ์</b></p>
                <p style="font-size:16px;display:inline-block;margin-left:-10px;" >'
                .$title_centent;
            
        $html=$html.'<table border="1" cellpadding="2"  width="100%" class="tb-report table-hover">';
                      for($i =0;$i<count($rsd_cat);$i++){
                      $isFirst = true;$sub = 0;
                        if($isFirst){ 
        $html=$html.          '<tr style="background-color: #99b3e6;" >
                                <td colspan="4" style="padding-left:10px"> '.($i+1).". ".$rsd_cat[$i]['name'].'</td>
                              </tr>
                              <tr style="text-align:center;" >
                                  <td width="8%">ลำดับที่</td>
                                  <td width="63%">'.$rsd_cat[$i]['full'].'</td>
                                  <td width="11%" >ภาคที่ได้รับการอนุมัติ</td>
                                  <td width="18%">วันที่ดำเนินการ</td>
                              </tr>';
                        $isFirst = false; 
                        }
                        foreach($tasks as $key => $task){
                          if(($i+1) == $task->rsd_category){
                            $sub++; 
        $html=$html.          '<tr>
                              <td align="center" valign="top" >'.($i+1).".".$sub.'</td>
                              <td valign="top" style="line-height: 1;"> เรื่อง '.$task->rsd_name.'<br> '.val::getRSDNameRole($task->rsd_user_role).'</td>
                              <td valign="top" align="center" >'.$task->rsd_semester.'</td>
                              <td valign="top" align="center" >'.f::dateDBtoBE($task->rsd_proceed_date).'</td>
                            </tr>';
                          }
                        }
                      }
        $html=$html.'</table>';

          $html = preg_replace('#(?(?!<!--.*?-->)(?: {2,}|[\r\n\t]+)|(<!--.*?-->))#s', '$1', $html);
          //echo strlen($html)."<br>";
         $this->pdf->setHeaderContent('งานวิจัยและพัฒนาสิ่งประดิษฐ์');
         $this->pdf->createPDF($html);
    }

    public function createReportACD($tasks,$st_date,$end_date,$selectLvl='',$selectId=''){
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
        
        $html="";
        $html=$html.'<p style="font-size:18px;text-align:center" ><b>รายงานงานพัฒนาวิชาการ</b></p>
                <p style="font-size:16px;display:inline-block;margin-left:-10px;" >'
                .$title_centent;

        $html=$html.       '<table border="1" cellpadding="2"  width="100%" class="tb-report table-hover">
                            <tr style="background-color: #99b3e6;">
                            <td width="7%"  align="center">ลำดับที่</td>
                            <td width="58%" align="center">งานพัฒนาวิชาการ แต่ละเรื่อง<br>ไม่เกิน 3 ภาคการศึกษาปกติ</td>
                            <td width="11%" align="center">ภาคที่ได้รับการอนุมัติ</td>
                            <td width="9%" align="center">จำนวน หน่วยกิต/สัปดาห์</td>
                            <td width="16%" align="center">วันที่ดำเนินการ</td>
                            </tr>';
                            for($i =0;$i<count($acd_cat);$i++){
                                $isFirst = true;$sub = 0; 
                                if($isFirst) {
        $html=$html.                    '<tr >
                                        <td valign="top" align="center">'.($i+1).'</td>
                                        <td valign="top" style="line-height: 1;"><b>'.$acd_cat[$i+1]["name"].'</b></td>
                                        <td valign="top" ></td>
                                        <td valign="top" ></td>
                                        <td valign="top" ></td>
                                        </tr>';
                                        $isFirst = false; 
                                }
                                foreach($tasks as $key => $task){
                                    if(($i+1) == $task->acd_category) {
                                     $sub++; 
        $html=$html.                    '<tr>
                                        <td valign="top" ></td>
                                        <td valign="top" style="line-height: 1;" > '. $task->acd_name.'</td>
                                        <td valign="top" align="center" style="line-height: 1;">'.$task->acd_semester.'</td>
                                        <td valign="top" align="center" style="line-height: 1;">'. $task->acd_creditPerWeek	.'</td>
                                        <td valign="top" align="center" style="line-height: 1;" >'.f::dateDBtoBE($task->acd_proceed_date).'</td>
                                    </tr>';
                                    }
                                }
                            }
        $html=$html.'</table>';           
            
        

          $html = preg_replace('#(?(?!<!--.*?-->)(?: {2,}|[\r\n\t]+)|(<!--.*?-->))#s', '$1', $html);
          //echo strlen($html)."<br>";
         $this->pdf->setHeaderContent('รายงานงานพัฒนาวิชาการ');
         $this->pdf->createPDF($html);
    }

    public function createReportACP($tasks,$st_date,$end_date,$selectLvl='',$selectId=''){
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
        
        $html='';
        $html=$html.'<p style="font-size:18px;text-align:center" ><b>รายงานงานเผยแพร่ผลงานทางวิชาการ</b></p>
                <p style="font-size:16px;display:inline-block;margin-left:-10px;" >'
                .$title_centent;

        $html=$html.'<table border="1" cellpadding="2"   width="100%" class="tb-report table-hover">
                        <tr style="background-color: #99b3e6;">
                            <td width="8%" align="center">ลำดับที่</td>
                            <td width="72%" align="center">งานเผยแพร่ผลงานทางวิชาการ</td>
                            <td width="20%" align="center">วันที่ตอบรับ/นำเสนอ</td>
                        </tr>';
                        for($i =0;$i<count($acp_cat);$i++) {
                             $isFirst = true;$sub = 0; 
                             if($isFirst) { 
        $html=$html.                '<tr>
                                      <td valign="top" align="center">'.($i+1).'.</td>
                                      <td valign="top" > '.$acp_cat[$i].'</td>
                                      <td valign="top" align="center"></td>
                                    </tr>';
                                $isFirst = false; 
                              }
                              foreach($tasks as $key => $task) {
                                if(($i+1) == $task->acp_task_type) {
                                   $sub++; 
        $html=$html.             '<tr>
                                    <td valign="top" ></td>
                                    <td valign="top" style="line-height: 1;" > 
                                      ชื่อ'.val::ACPCategory($task->acp_category).' '.$task->acp_name.'<br> 
                                      เรื่อง '.$task->acp_title;
                                      if($task->acp_user_role!==99) $html=$html.'<br> - '.val::ACPRole($task->acp_user_role); 
        $html=$html.                '</td>
                                    <td valign="top" align="center">'.f::dateDBtoBE($task->acp_proceed_date).'</td>
                                  </tr>';
                                }
                              }
                              if($sub==0) {
        $html=$html.            '<tr>
                                  <td height="20" valign="top" colspan="2"></td>
                                  <td valign="top"></td>
                                  <td valign="top"></td>
                                </tr>';
                              }
                           }

        $html=$html.'</table>';
       
        

          $html = preg_replace('#(?(?!<!--.*?-->)(?: {2,}|[\r\n\t]+)|(<!--.*?-->))#s', '$1', $html);
          //echo strlen($html)."<br>";
         $this->pdf->setHeaderContent('รายงานงานเผยแพร่ผลงานทางวิชาการ');
         $this->pdf->createPDF($html);
    }
    
    public function createReportSTP($tasks,$st_date,$end_date,$selectLvl='',$selectId=''){
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
            $title_centent ='<b>ผู้ปฏิบัติงาน </b>: '.f::getFullName(Auth::user()->id).'<br>
            <b>ระหว่างวันที่ </b>: '.f::dateThaiFull($st_date)." - ".f::dateThaiFull($end_date).'</p>';
        }
        
        $html="";
        $html=$html.'<p style="font-size:18px;text-align:center" ><b>รายงานผลงานนักศึกษา</b></p>
                <p style="font-size:16px;display:inline-block;margin-left:-10px;" >'
                .$title_centent;
        $html=$html.'<table border="1" cellpadding="2"  width="100%" class="tb-report table-hover">
                        <tr style="background-color: #99b3e6;">
                            <td width="8%" align="center">ลำดับที่</td>
                            <td width="55%" align="center">ผลงานนักศึกษา</td>
                            <td width="20%" align="center">วันที่ดำเนินการ</td>
                            <td width="17%" align="center">จำนวนนักศึกษา</td>
                        </tr>';
                       foreach( $tasks as $key => $task ){
        $html =$html.   '<tr>
                            <td width="8%" style="text-align:center;padding:8px">'.($key+1).'</td>
                            <td width="55%" style="text-align:laft"> '.$task->stp_name.'</td>
                            <td width="20%" style="text-align:center"> '.f::dateDBtoBE($task->stp_proceed_date).'</td>
                            <td width="17%" style="text-align:center"> '.($key+1).'</td>
                        </tr>';
                        }

        $html=$html.'</table>';
       
        

          $html = preg_replace('#(?(?!<!--.*?-->)(?: {2,}|[\r\n\t]+)|(<!--.*?-->))#s', '$1', $html);
          //echo strlen($html)."<br>";
         $this->pdf->setHeaderContent('รายงานผลงานนักศึกษา');
         $this->pdf->createPDF($html);
    }

}
