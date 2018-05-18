<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Input as Input;
use Illuminate\Support\Facades\Storage;
use Session;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use App\func as f;

class ActionController extends Controller
{
    //
    protected $client;
    protected $folder_id;
    protected $rootFolderId = '1DuIEUjTttUWWpBm38wpOpoJH77ACAyHq';
    protected $service;
    protected $root_stp_folder = '13WhT3n7rdjKI0HlJmkbwpVO-BhINQRGb';

    public function __construct(){
        $this->client = new \Google_Client();
        $this->client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $this->client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
        $this->service = new \Google_Service_Drive($this->client);
        date_default_timezone_set('Asia/Bangkok');
    }

    public function upload(Request $request){

      $file = request()->file('file');
      //dd($file);
      $folderId = '0Bz-pmuR0EpcvZENCYm82dGRGX3M';
      $fileMetadata = new \Google_Service_Drive_DriveFile([
        'name' => $file->getClientOriginalName(),
        'parents' => array($folderId)
      ]);
      $fileget = $this->service->files->create($fileMetadata, array(
        'data' => file_get_contents($request->file('file')->getRealPath()),
        'mimeType' => $file->getMimeType(),
        'uploadType' => 'multipart',
        'fields' => 'id'));
      dd($fileget);

    }

    public function testupfile2(){

        $folderId = '0Bz-pmuR0EpcvZENCYm82dGRGX3M';
        $fileMetadata = new \Google_Service_Drive_DriveFile([
          'name' => 'Hoo.txt',
          'parents' => array($folderId)
        ]);
        $file = $this->service->files->create($fileMetadata, array(
          'data' => "Haha you rest success",
          'mimeType' => 'text/plain',
          'uploadType' => 'multipart',
          'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
    }

    /*********************************************************************/
    /*                             Action Academic Service               */
    /*********************************************************************/
    public function saveFormAS(Request $request){
      //dd($request);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }


      $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่องาน ",
        "category.required" => "กรุณาเลือกประเภทงาน",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่ม ",
        'dateEnd.required' => "จำเป็นต้องกรอกช่อง วันที่สิ้นสุด ",
      ];

      $rule = [
        'taskName' => 'required',
        'category' => 'required',
        'dateStart' => 'required',
        'dateEnd' => 'required',
      ];

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
        $file = request()->file('file');
        $docId = array();

        $year = substr(substr(date('Y-m-d'),0,4)+543,2,4);
        $headtask = "ACS".$year;
        $headfile = "ACS-".$year."-".Auth::user()->short_name_en;
        try{
        $oldid = DB::table('academic_service')
                  ->select('as_id as id')
                  ->orderBy('as_id', 'desc')
                  ->first();
        $idtask;
        if(empty($oldid)) $idtask = $headtask.'1001';
        else {
          $idtask = f::genId(substr($oldid->id,3));
          $idtask = "ACS".$idtask;
        }

        DB::table('academic_service')->insert([
          'as_id' => $idtask,
          'as_name' => $request->taskName,
          'as_category' => $request->category,
          'as_start_date' => f::dateFormatDB($request->dateStart),
          'as_end_date' => f::dateFormatDB($request->dateEnd),
          'personnel_id' => Auth::user()->id,
          'lastest_updated' => date('Y-m-d H:i:s')
        ]);
        if(!empty(request()->file('file'))){
          $position=0;
          $docId = $this->pushFile2Drive($file,$dlt,$headfile);
          for($key=0;$key<count($file);$key++){
            $founded = false;
            for($n=0;$n<count($dlt);$n++){
              if($key==$dlt[$n]) $founded = true;
            }
            if(!$founded) {
              DB::table('documents')->insert([
                'doc_id' => $docId[$position]['id'],
                'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                'doc_type' => $file[$key]->getMimeType(),
                'task_id' => $idtask,
                'lastest_updated' => date('Y-m-d H:i:s')
              ]);
              $position++;
            }
          }

        }

      }catch (\Exception $e) {
        return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
      }
      return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
      }

     else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function saveEditAS(Request $request){
            $dlt = array();
            if(isset($request->dltFile)){
              $dlt = explode(",",$request->dltFile);
            }

            $msg = [
              'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่องาน ",
              "category.required" => "กรุณาเลือกประเภทงาน",
              'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่ม ",
              'dateEnd.required' => "จำเป็นต้องกรอกช่อง วันที่สิ้นสุด ",
            ];

            $rule = [
              'taskName' => 'required',
              'category' => 'required',
              'dateStart' => 'required',
              'dateEnd' => 'required',
            ];
            $headfile = "ACS-".substr($request->id,3,2)."-".Auth::user()->short_name_en;
            $validator = Validator::make($request->all(),$rule,$msg);
            if ($validator->passes()) {
              //dd($request);
              if(!empty($request->chkchange)){

                $oldFileId = DB::table('documents')
                             ->select('doc_id')
                             ->where('task_id',$request->id)
                             ->get();
                //dd($oldFileId);
                // ลบไฟล์เก่าบน ไดร์ฟ
                try{
                foreach($oldFileId as $files){
                  $this->deleteFileFromDrive($files->doc_id);
                }
                // ลบไฟล์เก่าในตาราง

                  DB::table('documents')->where('task_id',$request->id)->delete();
                }catch (\Exception $e) {
                  return response()->json(['errException'=>'ERROR! delete file.']);
                }

                try{
                // เช็คว่ามีไฟล์ไหม ถ้ามีเพิ่มบนไดร์ฟ และ ตาราง
                $file = request()->file('file');
                $docId = array();
                if(!empty(request()->file('file'))){
                  $position=0;
                  $docId = $this->pushFile2Drive($file,$dlt,$headfile);
                  for($key=0;$key<count($file);$key++){
                    $founded = false;
                    for($n=0;$n<count($dlt);$n++){
                      if($key==$dlt[$n]) $founded = true;
                    }
                    if(!$founded) {
                      DB::table('documents')->insert([
                        'doc_id' => $docId[$position]['id'],
                        'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                        'doc_type' => $file[$key]->getMimeType(),
                        'task_id' => $request->id,
                        'lastest_updated' => date('Y-m-d H:i:s')
                      ]);
                      $position++;
                    }
                  }
                }
                }catch (\Exception $e) {
                  return response()->json(['errException'=>'ERROR! insert file.']);
                }
              }


              try{
              // แก้ไขข้อมูล
              DB::table('academic_service')
                  ->where('as_id',$request->id)
                  ->update([
                    'as_name' => $request->taskName,
                    'as_category' => $request->category,
                    'as_start_date' => f::dateFormatDB($request->dateStart),
                    'as_end_date' => f::dateFormatDB($request->dateEnd),
                    'personnel_id' => Auth::user()->id,
                    'lastest_updated' => date('Y-m-d H:i:s')
                  ]);
                }catch (\Exception $e) {
                  return response()->json(['errException'=>'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง']);
                }
              return response()->json(['success'=>'บันทึกข้อมูลเรียบร้อย']);
            }
            else {
              return response()->json(['error'=>$validator->errors()->all()]);
            }
    }
    
    public function deleteAS($id=''){
      if($id!=''){
        $fileId = DB::table('documents')
                  ->select('doc_id as id')
                  ->where('task_id',$id)
                  ->get();
        foreach($fileId as $file ){
          try{
            $this->deleteFileFromDrive($file->id);
          }catch( \Exception $e ){
            echo "can not delete file";
          }
        }
        try{
          DB::table('documents')->where('task_id',$id)->delete();
          DB::table('academic_service')->where('as_id',$id)->delete();
        }catch( \Exception $e ){
          echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล');</script>";
        }
        //dd($fileId);
        return redirect('academic-service');
      }

    }

    /*********************************************************************/
    /*                            Action Training                        */
    /*********************************************************************/
    public function saveFormTRN(Request $request){
      $dlt = array();
      $coTeacher=Auth::user()->id;
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }
      if(isset($request->coTeacher)) $coTeacher = $coTeacher.",".$request->coTeacher;

      $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่องาน ",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่ม ",
        'dateEnd.required' => "จำเป็นต้องกรอกช่อง วันที่สิ้นสุด ",
        'location.required' => "จำเป็นต้องกรอกช่อง สถานที่",
      ];

      $rule = [
        'taskName' => 'required',
        'dateStart' => 'required',
        'dateEnd' => 'required',
        'location' => 'required',

      ];

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
        $file = request()->file('file');
        $docId = array();

        $year = substr(substr(date('Y-m-d'),0,4)+543,2,4);
        $headtask = "TRN".$year;
        $headfile = "TRN-".$year."-".Auth::user()->short_name_en;
        try{
        $oldid = DB::table('training')
                  ->select('trn_id as id')
                  ->orderBy('trn_id', 'desc')
                  ->first();
        $idtask;
        if(empty($oldid)) $idtask = $headtask.'1001';
        else {
          $idtask = f::genId(substr($oldid->id,3));
          $idtask = "TRN".$idtask;
        }
        DB::table('training')->insert([
          'trn_id' => $idtask,
          'trn_name' => $request->taskName,
          'trn_address' => $request->location,
          'trn_start' => f::dateFormatDB($request->dateStart),
          'trn_end' => f::dateFormatDB($request->dateEnd),
          'personnel_id' => Auth::user()->id,
          'coTeacher' => $coTeacher,
          'lastest_updated' => date('Y-m-d H:i:s')
        ]);
        if(!empty(request()->file('file'))){
          $position=0;
          $docId = $this->pushFile2Drive($file,$dlt,$headfile);
          //dd($docId);
          for($key=0;$key<count($file);$key++){
            $founded = false;
            for($n=0;$n<count($dlt);$n++){
              if($key==$dlt[$n]) $founded = true;
            }
            if(!$founded) {
              DB::table('documents')->insert([
                'doc_id' => $docId[$position]['id'],
                'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                'doc_type' => $file[$key]->getMimeType(),
                'task_id' => $idtask,
                'lastest_updated' => date('Y-m-d H:i:s')
              ]);
              $position++;
            }
          }

        }

      }catch (\Exception $e) {
        return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
      }
      return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
     }
      else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function saveEditTRN(Request $request){
      $dlt = array();
      $coTeacher=Auth::user()->id;
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }
      if(isset($request->coTeacher)) $coTeacher = $coTeacher.",".$request->coTeacher;

      $headfile = "TRN-".substr($request->id,3,2)."-".Auth::user()->short_name_en;

      $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่องาน ",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่ม ",
        'dateEnd.required' => "จำเป็นต้องกรอกช่อง วันที่สิ้นสุด ",
        'location.required' => "จำเป็นต้องกรอกช่อง สถานที่",
      ];

      $rule = [
        'taskName' => 'required',
        'dateStart' => 'required',
        'dateEnd' => 'required',
        'location' => 'required',

      ];
      $validator = Validator::make($request->all(),$rule,$msg);
      if ($validator->passes()) {

        if(!empty($request->chkchange)){

          $oldFileId = DB::table('documents')
                       ->select('doc_id')
                       ->where('task_id',$request->id)
                       ->get();
          //dd($oldFileId);
          // ลบไฟล์เก่าบน ไดร์ฟ
          try{
          foreach($oldFileId as $files){
            $this->deleteFileFromDrive($files->doc_id);
          }

          // ลบไฟล์เก่าในตาราง

            DB::table('documents')->where('task_id',$request->id)->delete();
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! delete file.']);
          }

          // เช็คว่ามีไฟล์ไหม ถ้ามีเพิ่มบนไดร์ฟ และ ตาราง
          $file = request()->file('file');
          $docId = array();
          try{
          if(!empty(request()->file('file'))){
            $position=0;
            $docId = $this->pushFile2Drive($file,$dlt,$headfile);
            for($key=0;$key<count($file);$key++){
              $founded = false;
              for($n=0;$n<count($dlt);$n++){
                if($key==$dlt[$n]) $founded = true;
              }
              if(!$founded) {
                DB::table('documents')->insert([
                  'doc_id' => $docId[$position]['id'],
                  'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                  'doc_type' => $file[$key]->getMimeType(),
                  'task_id' => $request->id,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);
                $position++;
              }
            }
          }
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! insert file.']);
          }
        }

        // แก้ไขข้อมูล
        try{
        DB::table('training')
            ->where('trn_id',$request->id)
            ->update([
              'trn_name' => $request->taskName,
              'trn_address' => $request->location,
              'trn_start' => f::dateFormatDB($request->dateStart),
              'trn_end' => f::dateFormatDB($request->dateEnd),
              'personnel_id' => Auth::user()->id,
              'coTeacher' => $coTeacher,
              'lastest_updated' => date('Y-m-d H:i:s')
            ]);
          }catch (\Exception $e) {
            return response()->json(['errException'=>'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง']);
          }
        return response()->json(['success'=>'บันทึกข้อมูลเรียบร้อย']);
      }
      else {
        return response()->json(['error'=>$validator->errors()->all()]);
      }
    }

    public function deleteTRN($id=''){
      if($id!=''){
        $fileId = DB::table('documents')
                  ->select('doc_id as id')
                  ->where('task_id',$id)
                  ->get();
        foreach($fileId as $file ){
          try{
            $this->deleteFileFromDrive($file->id);
          }catch( \Exception $e ){
            echo "can not delete file";
          }
        }
        try{
          DB::table('documents')->where('task_id',$id)->delete();
          DB::table('training')->where('trn_id',$id)->delete();
        }catch( \Exception $e ){
          echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล');</script>";
        }
        //dd($fileId);
        return redirect('training');
      }

    }

    /*********************************************************************/
    /*                     Action Research dev-invention                 */
    /*********************************************************************/

    public function saveFormRSD(Request $request){
      //dd($request);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }

      $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อเรื่อง ",
        "category.required" => "กรุณาเลือกประเภทงาน",
        "role.required" => "กรุณาเลือกหน้าที่",
        "semester.required" => "กรุณาเลือกภาคการศึกษาที่ได้รับการอนุมัติ",
        "yearSemester.required" => "กรุณาระบุปีการศึกษาที่ได้รับการอนุมัติ",
        "yearSemester.numeric" => "ปีการศึกษาที่ได้รับการอนุมัติ จะต้องเป็นตัวเลขเท่านั้น",
        "yearSemester.digits" => "ปีการศึกษาที่ได้รับการอนุมัติ จะต้องเป็นตัวเลข 4 หลักเท่านั้น",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่มดำเนินการ ",
        'dateEnd.required' => "จำเป็นต้องกรอกช่อง วันที่สิ้นสุดสัญญา ",
        
      ];

      $rule = [
        'taskName' => 'required',
        'category' => 'required',
        'role' => 'required',
        'semester' => 'required',
        'yearSemester' => 'required|numeric|digits:4',
        'dateStart' => 'required',
        'dateEnd' => 'required',
      ];

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
        $file = request()->file('file');
        $docId = array();

        $year = substr(substr(date('Y-m-d'),0,4)+543,2,4);
        $headtask = "RSD".$year;
        $headfile = "RSD-".$year."-".Auth::user()->short_name_en;

          $oldid = DB::table('research_devinvention')
                    ->select('rsd_id as id')
                    ->orderBy('rsd_id', 'desc')
                    ->first();
          $idtask;
          if(empty($oldid)) $idtask = $headtask.'1001';
          else {
            $idtask = f::genId(substr($oldid->id,3));
            $idtask = "RSD".$idtask;
          }
          try{
          DB::table('research_devinvention')->insert([
            'rsd_id' => $idtask,
            'rsd_name' => $request->taskName,
            'rsd_category' => $request->category,
            'rsd_user_role' => $request->role,
            'rsd_semester' => $request->semester."/".$request->yearSemester,
            'rsd_proceed_date' => f::dateFormatDB($request->dateStart),
            'rsd_end_proceed_date' => f::dateFormatDB($request->dateEnd),
            'personnel_id' => Auth::user()->id,
            'lastest_updated' => date('Y-m-d H:i:s')
          ]);
          if(!empty(request()->file('file'))){
            $position=0;
            $docId = $this->pushFile2Drive($file,$dlt,$headfile);
            //dd($docId);
            for($key=0;$key<count($file);$key++){
              $founded = false;
              for($n=0;$n<count($dlt);$n++){
                if($key==$dlt[$n]) $founded = true;
              }
              if(!$founded) {
                DB::table('documents')->insert([
                  'doc_id' => $docId[$position]['id'],
                  'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                  'doc_type' => $file[$key]->getMimeType(),
                  'task_id' => $idtask,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);
                $position++;
              }
            }

          }
        }catch (\Exception $e) {
          return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
        }

      return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
      }
     else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function saveEditRSD(Request $request){
      //dd($request);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }

      $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อเรื่อง ",
        "category.required" => "กรุณาเลือกประเภทงาน",
        "role.required" => "กรุณาเลือกหน้าที่",
        "semester.required" => "กรุณาเลือกภาคการศึกษาที่ได้รับการอนุมัติ",
        "yearSemester.required" => "กรุณาระบุปีการศึกษาที่ได้รับการอนุมัติ",
        "yearSemester.numeric" => "ปีการศึกษาที่ได้รับการอนุมัติ จะต้องเป็นตัวเลขเท่านั้น",
        "yearSemester.digits" => "ปีการศึกษาที่ได้รับการอนุมัติ จะต้องเป็นตัวเลข 4 หลักเท่านั้น",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่มดำเนินการ ",
        'dateEnd.required' => "จำเป็นต้องกรอกช่อง วันที่สิ้นสุดสัญญา ",
        
      ];

      $rule = [
        'taskName' => 'required',
        'category' => 'required',
        'role' => 'required',
        'semester' => 'required',
        'yearSemester' => 'required|numeric|digits:4',
        'dateStart' => 'required',
        'dateEnd' => 'required',
      ];

      $headfile = "RSD-".substr($request->id,3,2)."-".Auth::user()->short_name_en;
      $validator = Validator::make($request->all(),$rule,$msg);
      if ($validator->passes()) {
        //dd($request);
        if(!empty($request->chkchange)){

          $oldFileId = DB::table('documents')
                       ->select('doc_id')
                       ->where('task_id',$request->id)
                       ->get();
          //dd($oldFileId);
          // ลบไฟล์เก่าบน ไดร์ฟ
          try{
          foreach($oldFileId as $files){
            $this->deleteFileFromDrive($files->doc_id);
          }
          // ลบไฟล์เก่าในตาราง

            DB::table('documents')->where('task_id',$request->id)->delete();
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! delete file.']);
          }

          try{
          // เช็คว่ามีไฟล์ไหม ถ้ามีเพิ่มบนไดร์ฟ และ ตาราง
          $file = request()->file('file');
          $docId = array();
          if(!empty(request()->file('file'))){
            $position=0;
            $docId = $this->pushFile2Drive($file,$dlt,$headfile);
            for($key=0;$key<count($file);$key++){
              $founded = false;
              for($n=0;$n<count($dlt);$n++){
                if($key==$dlt[$n]) $founded = true;
              }
              if(!$founded) {
                DB::table('documents')->insert([
                  'doc_id' => $docId[$position]['id'],
                  'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                  'doc_type' => $file[$key]->getMimeType(),
                  'task_id' => $request->id,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);
                $position++;
              }
            }
          }
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! insert file.']);
          }
        }


        try{
        // แก้ไขข้อมูล
        DB::table('research_devinvention')
            ->where('rsd_id',$request->id)
            ->update([
              'rsd_name' => $request->taskName,
              'rsd_category' => $request->category,
              'rsd_user_role' => $request->role,
              'rsd_semester' => $request->semester."/".$request->yearSemester,
              'rsd_proceed_date' => f::dateFormatDB($request->dateStart),
              'rsd_end_proceed_date' => f::dateFormatDB($request->dateEnd),
              'personnel_id' => Auth::user()->id,
              'lastest_updated' => date('Y-m-d H:i:s')
            ]);
          }catch (\Exception $e) {
            return response()->json(['errException'=>'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง']);
          }
        return response()->json(['success'=>'บันทึกข้อมูลเรียบร้อย']);
      }
      else {
        return response()->json(['error'=>$validator->errors()->all()]);
      }
    }

    public function deleteRSD($id=''){
      if($id!=''){
        $fileId = DB::table('documents')
                  ->select('doc_id as id')
                  ->where('task_id',$id)
                  ->get();
        foreach($fileId as $file ){
          try{
            $this->deleteFileFromDrive($file->id);
          }catch( \Exception $e ){
            echo "can not delete file";
          }
        }
        try{
          DB::table('documents')->where('task_id',$id)->delete();
          DB::table('research_devinvention')->where('rsd_id',$id)->delete();
        }catch( \Exception $e ){
          echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล');</script>";
        }
        //dd($fileId);
        return redirect('research-devinv');
      }

    }


    /*********************************************************************/
    /*                     Action Academic dev                           */
    /*********************************************************************/

    public function saveFormACD(Request $request){
      //dd($request);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }

       $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อเรื่อง ",
        "category.required" => "กรุณาเลือกประเภทงาน",
        "acd_subject.required" => "กรุณาระบุว่า ตำรา/เอกสาร ประกอบวิชา",
        "yearSemester.required" => "กรุณาระบุปีการศึกษาที่เริ่มทำ",
        "yearSemester.numeric" => "ภาคการศึกษาที่เริ่มทำ จะต้องเป็นตัวเลขเท่านั้น",
        "yearSemester.digits" => "ภาคการศึกษาที่เริ่มทำ จะต้องเป็นตัวเลข 4 หลักเท่านั้น",
        "credit.required" => "กรุณาระบุหน่วยกิต",
        "credit.numeric" => "หน่วยกิต จะต้องเป็นตัวเลขเท่านั้น",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่มปฏิบัติ ",
      ];

      $rule = [
        'taskName' => 'required',
        'category' => 'required',
        'acd_subject' => 'required',
        'yearSemester' => 'required|numeric|digits:4',
        'credit' => 'required|numeric',
        'dateStart' => 'required',
      ];
      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
        $file = request()->file('file');
        $docId = array();

        $year = substr(substr(date('Y-m-d'),0,4)+543,2,4);
        $headtask = "ACD".$year;
        $headfile = "ACD-".$year."-".Auth::user()->short_name_en;

          $oldid = DB::table('academic_development')
                    ->select('acd_id as id')
                    ->orderBy('acd_id', 'desc')
                    ->first();
          $idtask;
          if(empty($oldid)) $idtask = $headtask.'1001';
          else {
            $idtask = f::genId(substr($oldid->id,3));
            $idtask = "ACD".$idtask;
          }
          try{
          DB::table('academic_development')->insert([
            'acd_id' => $idtask,
            'acd_name' => $request->taskName,
            'acd_category' => $request->category,
            'acd_subject' => $request->acd_subject,
            'acd_semester' => $request->semester.'/'.$request->yearSemester,
            'acd_proceed_date' => f::dateFormatDB($request->dateStart),
            'acd_creditPerWeek'=>$request->credit,
            'personnel_id' => Auth::user()->id,
            'lastest_updated' => date('Y-m-d H:i:s')
          ]);
          if(!empty(request()->file('file'))){
            $position=0;
            $docId = $this->pushFile2Drive($file,$dlt,$headfile);
            //dd($docId);
            for($key=0;$key<count($file);$key++){
              $founded = false;
              for($n=0;$n<count($dlt);$n++){
                if($key==$dlt[$n]) $founded = true;
              }
              if(!$founded) {
                DB::table('documents')->insert([
                  'doc_id' => $docId[$position]['id'],
                  'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                  'doc_type' => $file[$key]->getMimeType(),
                  'task_id' => $idtask,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);
                $position++;
              }
            }

          }
        }catch (\Exception $e) {
          return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
        }

      return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
      }
     else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function saveEditACD(Request $request){
      //dd($request);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }

      $msg = [
        'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อเรื่อง ",
        "category.required" => "กรุณาเลือกประเภทงาน",
        "acd_subject.required" => "กรุณาระบุว่า ตำรา/เอกสาร ประกอบวิชา",
        "yearSemester.required" => "กรุณาระบุปีการศึกษาที่เริ่มทำ",
        "yearSemester.numeric" => "ภาคการศึกษาที่เริ่มทำ จะต้องเป็นตัวเลขเท่านั้น",
        "yearSemester.digits" => "ภาคการศึกษาที่เริ่มทำ จะต้องเป็นตัวเลข 4 หลักเท่านั้น",
        "credit.required" => "กรุณาระบุหน่วยกิต",
        "credit.numeric" => "หน่วยกิต จะต้องเป็นตัวเลขเท่านั้น",
        'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่เริ่มปฏิบัติ ",
      ];

      $rule = [
        'taskName' => 'required',
        'category' => 'required',
        'acd_subject' => 'required',
        'yearSemester' => 'required|numeric|digits:4',
        'credit' => 'required|numeric',
        'dateStart' => 'required',
      ];

      $headfile = "ACD-".substr($request->id,3,2)."-".Auth::user()->short_name_en;
      $validator = Validator::make($request->all(),$rule,$msg);
      if ($validator->passes()) {
        //dd($request);
        if(!empty($request->chkchange)){

          $oldFileId = DB::table('documents')
                       ->select('doc_id')
                       ->where('task_id',$request->id)
                       ->get();
          //dd($oldFileId);
          // ลบไฟล์เก่าบน ไดร์ฟ
          try{
          foreach($oldFileId as $files){
            $this->deleteFileFromDrive($files->doc_id);
          }
          // ลบไฟล์เก่าในตาราง

            DB::table('documents')->where('task_id',$request->id)->delete();
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! delete file.']);
          }

          try{
          // เช็คว่ามีไฟล์ไหม ถ้ามีเพิ่มบนไดร์ฟ และ ตาราง
          $file = request()->file('file');
          $docId = array();
          if(!empty(request()->file('file'))){
            $position=0;
            $docId = $this->pushFile2Drive($file,$dlt,$headfile);
            for($key=0;$key<count($file);$key++){
              $founded = false;
              for($n=0;$n<count($dlt);$n++){
                if($key==$dlt[$n]) $founded = true;
              }
              if(!$founded) {
                DB::table('documents')->insert([
                  'doc_id' => $docId[$position]['id'],
                  'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                  'doc_type' => $file[$key]->getMimeType(),
                  'task_id' => $request->id,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);
                $position++;
              }
            }
          }
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! insert file.']);
          }
        }


        try{
        // แก้ไขข้อมูล
        DB::table('academic_development')
            ->where('acd_id',$request->id)
            ->update([
              'acd_name' => $request->taskName,
              'acd_category' => $request->category,
              'acd_subject' => $request->acd_subject,
              'acd_semester' => $request->semester.'/'.$request->yearSemester,
              'acd_proceed_date' => f::dateFormatDB($request->dateStart),
              'acd_creditPerWeek'=>$request->credit,
              'personnel_id' => Auth::user()->id,
              'lastest_updated' => date('Y-m-d H:i:s')
            ]);
          }catch (\Exception $e) {
            return response()->json(['errException'=>'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง']);
          }
        return response()->json(['success'=>'บันทึกข้อมูลเรียบร้อย']);
      }
      else {
        return response()->json(['error'=>$validator->errors()->all()]);
      }
    }

    public function deleteACD($id=''){
      if($id!=''){
        $fileId = DB::table('documents')
                  ->select('doc_id as id')
                  ->where('task_id',$id)
                  ->get();
        foreach($fileId as $file ){
          try{
            $this->deleteFileFromDrive($file->id);
          }catch( \Exception $e ){
            echo "can not delete file";
          }
        }
        try{
          DB::table('documents')->where('task_id',$id)->delete();
          DB::table('academic_development')->where('acd_id',$id)->delete();
        }catch( \Exception $e ){
          echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล');</script>";
        }
        //dd($fileId);
        return redirect('academic-dev');
      }

    }


    /*********************************************************************/
    /*                     Action Academic Pub                           */
    /*********************************************************************/

    public function saveFormACP(Request $request,$type){
      //dd($type);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }

      if($type == 1 or $type == 2){
        $msg = [
          'taskNameType.required' => "จำเป็นต้องระบุ ชนิดวารสาร",
          'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อวารสาร ",
          'title.required' => "จำเป็นต้องกรอกช่อง เรื่อง ",
          'role.required' => "จำเป็นต้องระบุ หน้าที่ ",
          'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่ปฏิบัติงาน ",
        ];
        $rule = [
          'taskNameType' => 'required',
          'taskName' => 'required',
          'title'=> 'required',
          'role'=> 'required',
          'dateStart' => 'required',
        ];
      }
      elseif($type == 3){
        $msg = [
          'taskNameType.required' => "จำเป็นต้องระบุ ชนิดวารสาร",
          'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อวารสาร ",
          'title.required' => "จำเป็นต้องกรอกช่อง เรื่อง ",
          'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่ปฏิบัติงาน ",
        ];
        $rule = [
          'taskNameType' => 'required',
          'taskName' => 'required',
          'title'=> 'required',
          'dateStart' => 'required',
        ];
      }

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
          $file = request()->file('file');
          $docId = array();

          $year = substr(substr(date('Y-m-d'),0,4)+543,2,4);
          $headtask = "ACP".$year;
          $headfile = "ACP-".$year."-".Auth::user()->short_name_en;

            $oldid = DB::table('academic_publication')
                      ->select('acp_id as id')
                      ->orderBy('acp_id', 'desc')
                      ->first();
            $idtask;
            if(empty($oldid)) $idtask = $headtask.'1001';
            else {
              $idtask = f::genId(substr($oldid->id,3));
              $idtask = "ACP".$idtask;
            }
            try{
              $role = 0;
              if($type == 1 or $type == 2){ $role=$request->role ; }
              if($type == 3){ $role = 99; }
              (isset($request->base)) ? $acp_base = $request->base: $acp_base = '';
                DB::table('academic_publication')->insert([
                  'acp_id' => $idtask,
                  'acp_name' => $request->taskName,
                  'acp_title' => $request->title,
                  'acp_task_type' => $type,
                  'acp_category' => $request->taskNameType,
                  'acp_base' => $acp_base,
                  'acp_user_role' => $role,
                  'acp_proceed_date' => f::dateFormatDB($request->dateStart),
                  'personnel_id' => Auth::user()->id,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);

            if(!empty(request()->file('file'))){
              $position=0;
              $docId = $this->pushFile2Drive($file,$dlt,$headfile);
              //dd($docId);
              for($key=0;$key<count($file);$key++){
                $founded = false;
                for($n=0;$n<count($dlt);$n++){
                  if($key==$dlt[$n]) $founded = true;
                }
                if(!$founded) {
                  DB::table('documents')->insert([
                    'doc_id' => $docId[$position]['id'],
                    'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                    'doc_type' => $file[$key]->getMimeType(),
                    'task_id' => $idtask,
                    'lastest_updated' => date('Y-m-d H:i:s')
                  ]);
                  $position++;
                }
              }

            }
          }catch (\Exception $e) {
            return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
          }

        return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
      }
     else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function saveEditACP(Request $request,$type){
      //dd($request);
      $dlt = array();
      if(isset($request->dltFile)){
        $dlt = explode(",",$request->dltFile);
      }

      if($type == 1 or $type == 2){
        $msg = [
          'taskNameType.required' => "จำเป็นต้องระบุ ชนิดวารสาร",
          'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อวารสาร ",
          'title.required' => "จำเป็นต้องกรอกช่อง เรื่อง ",
          'role.required' => "จำเป็นต้องระบุ หน้าที่ ",
          'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่ปฏิบัติงาน ",
        ];
        $rule = [
          'taskNameType' => 'required',
          'taskName' => 'required',
          'title'=> 'required',
          'role'=> 'required',
          'dateStart' => 'required',
        ];
      }
      elseif($type == 3){
        $msg = [
          'taskNameType.required' => "จำเป็นต้องระบุ ชนิดวารสาร",
          'taskName.required' => "จำเป็นต้องกรอกช่อง ชื่อวารสาร ",
          'title.required' => "จำเป็นต้องกรอกช่อง เรื่อง ",
          'dateStart.required' => "จำเป็นต้องกรอกช่อง วันที่ปฏิบัติงาน ",
        ];
        $rule = [
          'taskNameType' => 'required',
          'taskName' => 'required',
          'title'=> 'required',
          'dateStart' => 'required',
        ];
      }

      $headfile = "ACP-".substr($request->id,3,2)."-".Auth::user()->short_name_en;
      $validator = Validator::make($request->all(),$rule,$msg);
      if ($validator->passes()) {
        //dd($request);
        if(!empty($request->chkchange)){

          $oldFileId = DB::table('documents')
                       ->select('doc_id')
                       ->where('task_id',$request->id)
                       ->get();
          //dd($oldFileId);
          // ลบไฟล์เก่าบน ไดร์ฟ
          try{
          foreach($oldFileId as $files){
            $this->deleteFileFromDrive($files->doc_id);
          }
          // ลบไฟล์เก่าในตาราง

            DB::table('documents')->where('task_id',$request->id)->delete();
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! delete file.']);
          }

          try{
          // เช็คว่ามีไฟล์ไหม ถ้ามีเพิ่มบนไดร์ฟ และ ตาราง
          $file = request()->file('file');
          $docId = array();
          if(!empty(request()->file('file'))){
            $position=0;
            $docId = $this->pushFile2Drive($file,$dlt,$headfile);
            for($key=0;$key<count($file);$key++){
              $founded = false;
              for($n=0;$n<count($dlt);$n++){
                if($key==$dlt[$n]) $founded = true;
              }
              if(!$founded) {
                DB::table('documents')->insert([
                  'doc_id' => $docId[$position]['id'],
                  'doc_name' => $headfile."-".$file[$key]->getClientOriginalName(),
                  'doc_type' => $file[$key]->getMimeType(),
                  'task_id' => $request->id,
                  'lastest_updated' => date('Y-m-d H:i:s')
                ]);
                $position++;
              }
            }
          }
          }catch (\Exception $e) {
            return response()->json(['errException'=>'ERROR! insert file.']);
          }
        }


        try{
        // แก้ไขข้อมูล
        $role = 0;
        if($type == 1 or $type == 2){ $role=$request->role ; }
        if($type == 3){ $role = 99; }
        (isset($request->base)) ? $acp_base = $request->base: $acp_base = '';
          DB::table('academic_publication')
          ->where('acp_id',$request->id)
          ->update([
            'acp_name' => $request->taskName,
            'acp_title' => $request->title,
            'acp_task_type' => $type,
            'acp_category' => $request->taskNameType,
            'acp_base' => $acp_base,
            'acp_user_role' => $role,
            'acp_proceed_date' => f::dateFormatDB($request->dateStart),
            'personnel_id' => Auth::user()->id,
            'lastest_updated' => date('Y-m-d H:i:s')
          ]);
          }catch (\Exception $e) {
            return response()->json(['errException'=>'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง']);
          }
        return response()->json(['success'=>'บันทึกข้อมูลเรียบร้อย']);
      }
      else {
        return response()->json(['error'=>$validator->errors()->all()]);
      }
    }

    public function deleteACP($id=''){
      if($id!=''){
        $fileId = DB::table('documents')
                  ->select('doc_id as id')
                  ->where('task_id',$id)
                  ->get();
        foreach($fileId as $file ){
          try{
            $this->deleteFileFromDrive($file->id);
          }catch( \Exception $e ){
            echo "can not delete file";
          }
        }
        try{
          DB::table('documents')->where('task_id',$id)->delete();
          DB::table('academic_publication')->where('acp_id',$id)->delete();
        }catch( \Exception $e ){
          echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล');</script>";
        }
        //dd($fileId);
        return redirect('academic-pub');
      }

    }

    /*********************************************************************/
    /*                     Action Student Portfolio                      */
    /*********************************************************************/
    public function saveFormSTP(Request $request){
      //dd($request);
      $dlt = array();
      
      $msg = [
        'taskName.required' => "กรุณาระบุเรื่อง ",
        'dateStart.required' => "กรุณาระบุวันที่ทำการ ",
        //'stdList.required' => "จำเป็นต้องมีนักศึกษาอย่างน้อย 1 คน",
        'coTeacher.required' => "กรุณาระบุบุคลาการผู้ควบคุม",
      ];

      $rule = [
        'taskName' => 'required',
        //'stdList' => 'required',
        'coTeacher' => 'required',
        'dateStart' => 'required'

      ];

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
            $file = request()->file('file');
            $docId = array();
            $album_id = '';
            $stdList = '';
              $year = substr(substr(date('Y-m-d'),0,4)+543,2,4);
              $headtask = "STP".$year;
              $headfile = "STP-".$year;
            //try{
              $oldid = DB::table('student_portfolio')
                        ->select('stp_id as id')
                        ->orderBy('stp_id', 'desc')
                        ->first();
              $idtask;
              if(empty($oldid)) $idtask = $headtask.'1001';
              else {
                $idtask = f::genId(substr($oldid->id,3));
                $idtask = "STP".$idtask;
              }
              if($request->import_csv=='yes'){
                if (!empty(request()->file('csv'))) {
                  $csv =  request()->file('csv');
                  $stdList = f::csvExtractStd($csv);
                  $stdList = implode(",",$stdList);       
                } 
              }
              if($request->import_csv=='no'){
                $stdList = $request->stdList;
              }
              // if have image then create album
              if(!empty(request()->file('file'))){
                // create album return id
                $album_id ='';
                $album_drive_id = $this->createFolderAlbum($idtask."-".$request->taskName);
                if(isset($album_id)){
                  $album_id =    DB::table('albums')->insertGetId([
                                  'album_name' => $idtask."-".$request->taskName,
                                  'album_drive_id' => $album_drive_id,
                                  'task_id' =>  $idtask,
                                  'lastest_updated' => date('Y-m-d H:i:s')
                                ]);
                }
                $position=0;
                // add file to album folder
                // add to drive
                $docId = $this->pushFile2Drive($file,$dlt,$headfile,$album_drive_id);
                // add to DB
                for($key=0;$key<count($file);$key++){
                    DB::table('images')->insert([
                      'image_id' => $docId[$key]['id'],
                      'image_name' => $file[$key]->getClientOriginalName(),
                      'album_id' =>  $album_id
                    ]);
                }

              }

              DB::table('student_portfolio')->insert([
                'stp_id' => $idtask,
                'stp_name' => $request->taskName,
                'stp_description' => $request->stp_desc,
                'award' => $request->award,
                'student_name_list' => $stdList,
                'stp_proceed_date' => f::dateFormatDB($request->dateStart),
                'superviser_id' => $request->coTeacher,
                'album_id' =>$album_id,
                'save_by_personnel_id' => Auth::user()->id,
                'lastest_updated' => date('Y-m-d H:i:s')
              ]);


            // }catch (\Exception $e) {
            //   return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
            // }
      return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
     }
      else return response()->json(['error'=>$validator->errors()->all()]);

    }

    public function saveEditSTP(Request $request){
      //dd($request);
      $dlt = array();
      $msg = [
        'taskName.required' => "กรุณาระบุเรื่อง ",
        'dateStart.required' => "กรุณาระบุวันที่ทำการ ",
        //'stdList.required' => "จำเป็นต้องมีนักศึกษาอย่างน้อย 1 คน",
        'coTeacher.required' => "กรุณาระบุบุคลาการผู้ควบคุม",
      ];

      $rule = [
        'taskName' => 'required',
        //'stdList' => 'required',
        'coTeacher' => 'required',
        'dateStart' => 'required'

      ];

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
        $idtask = $request->id;
        $headfile = "STP-".substr($request->id,3,2);

        $old_album = DB::table('student_portfolio')
                        ->select('albums.album_id','albums.album_drive_id')
                        ->join('albums','student_portfolio.album_id','=','albums.album_id')
                        ->where('student_portfolio.stp_id',$request->id)
                        ->first();
        $new_album_id = $old_album->album_id;  
        // เช็คว่ามีการกดเปลี่ยน รายชื่อไหม 
        if($request->change_std=="yes"){
          // ถ้ามีการ import ไฟล์
          if($request->import_csv=='yes'){
            if (!empty(request()->file('csv'))) {
              $csv =  request()->file('csv');
              $stdList = f::csvExtractStd($csv);
              $stdList = implode(",",$stdList);       
            } 
          }
          // ไม่มีการ import ไฟล์
          else{
            $stdList = $request->stdList ;
          }
        } 
        // เมื่อไม่มีการกดเปลี้ยนรายชื่อ เอารายชื่อเดิมไปใส่
        else{
          $dataStp = DB::table('student_portfolio')
                     ->select('student_name_list')
                     ->where('stp_id',$request->id)
                     ->first();
          $stdList =   $dataStp->student_name_list;       
        }              
        // have new  album
        if($request->changeAlbum == "yes"){
          try{
            // ลบอัลบั้มเก่า บน กูเกิลไดร์ฟ
            $this->deleteFileFromDrive($old_album->album_drive_id);
            // ลบอัลบั้มเก่าในตาราง
            DB::table('images')->where('album_id',$old_album->album_id)->delete();
            DB::table('albums')->where('album_id',$old_album->album_id)->delete();
          }catch (\Exception $e) {
            return response()->json(['errException'=>$e->getMessage()]);
          }

          try{
          // เช็คว่ามีไฟล์ไหม ถ้ามีเพิ่มบนไดร์ฟ และ ตาราง
          $file = request()->file('file');
          $docId = array();
          // if have image then create album
            if(!empty(request()->file('file'))){
              // create album return id
              $album_id ='';
              $album_drive_id = $this->createFolderAlbum($idtask."-".$request->taskName);
              if(isset($album_id)){
                $album_id =    DB::table('albums')->insertGetId([
                                'album_name' => $idtask."-".$request->taskName,
                                'album_drive_id' => $album_drive_id,
                                'task_id' =>  $idtask,
                                'lastest_updated' => date('Y-m-d H:i:s')
                              ]);
                // รหัสอัลบั้มใหม่ในดาต้าเบส
                $new_album_id = $album_id;  
              }
              $position=0;
              // add file to album folder
              // add to drive
              $docId = $this->pushFile2Drive($file,$dlt,$headfile,$album_drive_id);
              // add to DB
              for($key=0;$key<count($file);$key++){
                  DB::table('images')->insert([
                    'image_id' => $docId[$key]['id'],
                    'image_name' => $file[$key]->getClientOriginalName(),
                    'album_id' =>  $album_id
                  ]);
              }

            }
          }catch (\Exception $e) {
            return response()->json(['errException'=>$e->getMessage()]);
          }
        }

        try{
          // แก้ไขข้อมูล
          DB::table('student_portfolio')
              ->where('stp_id',$idtask)
              ->update([
                'stp_name' => $request->taskName,
                'stp_description' => $request->stp_desc,
                'award' => $request->award,
                'student_name_list' => $stdList,
                'stp_proceed_date' => f::dateFormatDB($request->dateStart),
                'superviser_id' => $request->coTeacher,
                'album_id' =>$new_album_id,
                'lastest_updated' => date('Y-m-d H:i:s')
              ]);
            }catch (\Exception $e) {
              return response()->json(['errException'=>'เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง']);
            }
        
        return response()->json(['success'=>'เพิ่มข้อมูลเรียบร้อย']);
      }
      else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteSTP($id=''){
      if($id!=''){
        $old_album = DB::table('student_portfolio')
                        ->select('albums.album_id','albums.album_drive_id')
                        ->join('albums','student_portfolio.album_id','=','albums.album_id')
                        ->where('student_portfolio.stp_id',$id)
                        ->first();
    
        try{
          // ลบอัลบั้มเก่า บน กูเกิลไดร์ฟ
          $this->deleteFileFromDrive($old_album->album_drive_id);
          // ลบอัลบั้มเก่าในตาราง
          DB::table('images')->where('album_id',$old_album->album_id)->delete();
          DB::table('albums')->where('album_id',$old_album->album_id)->delete();
          DB::table('student_portfolio')->where('stp_id',$id)->delete();
        }catch( \Exception $e ){
          echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูลจากฐานข้อมูล');</script>";
        }
        //dd($fileId);
        return redirect('/std-portfolio');
      }

    }



    public function createFolderAlbum($album_name){
      $fileMetadata = new \Google_Service_Drive_DriveFile([
          'name'     => $album_name,
          'mimeType' => 'application/vnd.google-apps.folder',
          'parents' => array($this->root_stp_folder)
      ]);
      $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);
      return $folder->id;
    }

    public function pushFile2Drive($file,$dlt,$head,$folder_id=null){
      $i=0;
      $fileId = array();
      if($folder_id == null) $parents = Auth::user()->drive_folder_id;
      else $parents = $folder_id;
      for($key=0;$key<count($file);$key++){
        $founded = false;
        for($n=0;$n<count($dlt);$n++){
          if($key==$dlt[$n]) $founded = true;
        }
        if(!$founded) {
          $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name' => $head."-".$file[$key]->getClientOriginalName(),
            'parents' => array($parents)
          ]);
          $fileget = $this->service->files->create($fileMetadata, array(
            'data' => file_get_contents($file[$key]->getRealPath()),
            'mimeType' => $file[$key]->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'
          ));
          array_push($fileId,['id'=>$fileget->id]);
        }
      }
      return $fileId;
    }

    public function deleteFileFromDrive($fileId){
      $this->service->files->delete($fileId);
    }

    public function deleteFile($fileId){
      $this->service->files->delete($fileId);
      DB::table('documents')->where('doc_id',$fileId)->delete();
    }

}
