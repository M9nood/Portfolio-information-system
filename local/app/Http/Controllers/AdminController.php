<?php

namespace App\Http\Controllers;
use Session;
use Auth;
use DB;
use \Input as Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\func as f;
use App\constantsValue as val;

class AdminController extends Controller
{
    //
    protected $client;
    protected $folder_id;
    protected $rootFolderId='1DuIEUjTttUWWpBm38wpOpoJH77ACAyHq';
    protected $service;

    public function __construct()
    {
        
        //$this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(empty(Auth::user())) return redirect('/');
            if(Auth::user()->user_level!="admin"){
                return redirect('/permise');
            }  
            return $next($request);
        });
        
    }

    public function indexAdmin(){
        $docs = DB::table('documents')
                ->orderBy('lastest_updated','desc')
                ->limit(10)
                ->get();
        //dd($docs);
        return view('admin.index',array("docs"=>$docs));
    }
    /********************************************************/
    /*                      User Manage                     */
    /********************************************************/
    public function indexUserManage(){
        //$users = DB::table('users')->get();
        $users = DB::table('users')->where('user_level','<>','admin')->where('active','yes')->get();
        $datas = array(
            'users' => $users
        );
        return view('admin.user-manage.table',$datas);
    } 

    public function viewUser($uid){
        if($uid !=''){
            $users = DB::table('users')->where('id',$uid)->first();
            $datas = array(
                'user' => $users
            );
            return view('admin.user-manage.view',$datas);
        }
    }

    public function addUser(){
        return view('admin.user-manage.addForm');
    }

    public function saveUser(Request $request){
       $msg = [
        'name.required' => "จำเป็นต้องกรอกช่อง ชื่อ ",
        'lname.required' => "จำเป็นต้องกรอกช่อง นามสกุล ",
        'shortname.required' => "จำเป็นต้องกรอกช่อง ชื่อย่อภาษาอังกฤษ ",
        'shortname.min' => "ชื่อย่อต้องประกอบด้วยอักษรภาษาอังกฤษ 3 ตัว ",
        'shortname.max' => "ชื่อย่อต้องประกอบด้วยอักษรภาษาอังกฤษ 3 ตัว ",
        'email.required' => "จำเป็นต้องกรอกช่อง อีเมล ",
        'department.required' => "จำเป็นต้องกรอกช่อง ชื่อภาควิชา ",
        'faculty.required' => "จำเป็นต้องกรอกช่อง ชื่อคณะ ",
        'userlevel.required' => "จำเป็นต้องกรอกช่อง สถานะผู้ใช้ ",
      ];

      $rule = [
        'name' => 'required',
        'lname' => 'required',
        'shortname' => 'required|min:3|max:3',
        'email' => 'required',  
        'department' => 'required', 
        'faculty' => 'required',
        'userlevel' => 'required',        
      ];

      $validator = Validator::make($request->all(),$rule,$msg);

      if ($validator->passes()) {
       try{
        $pos = val::getUserPosition();
        $ttName = val::getTitleName();


        $oldUid = DB::table('users')
                ->select('id')
                ->orderBy('id', 'desc')
                ->first();
        $newUid = f::genId(substr($oldUid->id,3));
        $newUid = "PIS".$newUid;
        if($request->otherttname=="on") $ttname = $request->otherttnametxt;
        else $ttname = $ttName[$request->ttname];

        if($request->other=="on") $position = $request->otherPostxt;
        else $position = $pos[$request->position];

        (isset($request->isadmSTP)) ? $isadm_stp=$request->isadmSTP :$isadm_stp="no";

        DB::table('users')->insert([
            'id' => $newUid ,
            'title_name' => $ttname,
            'name' => $request->name,
            'lastname' => $request->lname,
            'short_name_en' => strtoupper($request->shortname),
            'email' => strtolower($request->email),
            'department_id' => $request->department,
            'user_position_name' => $position ,
            'user_level' =>$request->userlevel ,
            'drive_folder_id' => '',
            'remember_token' => '',
            'isadm_stp' => $isadm_stp,
            'created_at' => date('Y-m-d H:i:s') ,
            'updated_at' => date('Y-m-d H:i:s'),
            'active' => 'yes'
        ]);
        }catch (\Exception $e) {
            return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
        }
        return response()->json(['success'=>'บันทึกข้อมูลผู้ใช้เรียบร้อยเเล้ว.']);
      }  
      else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function editUser($uid){
        if($uid !=''){
            $users = DB::table('users')->where('id',$uid)->first();
            $datas = array(
                'user' => $users
            );
            return view('admin.user-manage.editForm',$datas);
        }
    }

    public function saveeditUser(Request $request){
        $msg = [
            'name.required' => "จำเป็นต้องกรอกช่อง ชื่อ ",
            'lname.required' => "จำเป็นต้องกรอกช่อง นามสกุล ",
            'shortname.required' => "จำเป็นต้องกรอกช่อง ชื่อย่อภาษาอังกฤษ ",
            'shortname.min' => "ชื่อย่อต้องประกอบด้วยอักษรภาษาอังกฤษ 3 ตัว ",
            'shortname.max' => "ชื่อย่อต้องประกอบด้วยอักษรภาษาอังกฤษ 3 ตัว ",
            'email.required' => "จำเป็นต้องกรอกช่อง อีเมล ",
            'department.required' => "จำเป็นต้องกรอกช่อง ชื่อภาควิชา ",
            'faculty.required' => "จำเป็นต้องกรอกช่อง ชื่อคณะ ",
            'userlevel.required' => "จำเป็นต้องกรอกช่อง สถานะผู้ใช้ ",
          ];
    
          $rule = [
            'name' => 'required',
            'lname' => 'required',
            'shortname' => 'required|min:3|max:3',
            'email' => 'required',  
            'department' => 'required', 
            'faculty' => 'required',
            'userlevel' => 'required',        
          ];
    
          $validator = Validator::make($request->all(),$rule,$msg);
    
          if ($validator->passes()) {
            try{
            $pos = val::getUserPosition();
            $ttName = val::getTitleName();

            if($request->otherttname=="on") $ttname = $request->otherttnametxt;
            else $ttname = $ttName[$request->ttname];

            if($request->other=="on") $position = $request->otherPostxt;
            else $position = $pos[$request->position];
    
            (isset($request->isadmSTP)) ? $isadm_stp=$request->isadmSTP :$isadm_stp="no";
    
            DB::table('users')
                ->where('id',$request->id)    
                ->update([
                'title_name' => $ttname,
                'name' => $request->name,
                'lastname' => $request->lname,
                'short_name_en' => strtoupper($request->shortname),
                'email' => strtolower($request->email),
                'department_id' => $request->department,
                'user_position_name' => $position ,
                'user_level' =>$request->userlevel ,
                'isadm_stp' => $isadm_stp,
                'updated_at' => date('Y-m-d H:i:s'),
                'active' => 'yes'
                ]);
            }catch (\Exception $e) {
                return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
            }
            return response()->json(['success'=>'บันทึกข้อมูลผู้ใช้เรียบร้อยเเล้ว.']);
          }  
          else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteUser($id=''){
        if($id!=''){
            $u = DB::table('users')->where('id',$id)->first();
            if($u->user_level!="admin" and Auth::user()->user_level=="admin"){
                try{
                    // try{
                    //     $this->deleteFolder($u->drive_folder_id);
                    // }catch( \Exception $e ){
                        
                    // }
                
                    // $table = val::getTableAttr();
                    // foreach($table as $key => $tb){
                    //     $tasks = DB::table($tb['table'])->select($tb['id']." as id")->where('personnel_id',$u->id)->get();
                    //     foreach($tasks as $tsk){
                    //         DB::table('documents')->where('task_id',$tsk->id)->delete();
                    //     }
                    //     DB::table($tb['table'])->where('personnel_id',$u->id)->delete();
                    // }
                    // DB::table('users')->where('id',$u->id)->delete();
                    // return redirect('admin/user-manage');

                    DB::table('users')->where('id',$id)
                                      ->update([
                                        'active'=>'no'
                                      ]);
                    return redirect('admin/user-manage');

                }catch( \Exception $e ){
                    echo "เกิดข้อผิดพลาดบางประการ จึงไม่สามารถลบผู้ใช้ได้";
                }
            }
            else{
                echo "ไม่สามารถลบผู้ดูแลระบบได้ หรือคุณไม่มีสิทธ์ลบผู้ใช้";
            }
          
          
        }
        
  
    }

    /********************************************************/
    /*                  Departmnet Manage                   */
    /********************************************************/

    public function indexDep(){
        $dp = f::getDepartment();
        return view('admin.dep-manage.table',['deps'=>$dp]);
    }

    public function addDep(){
        return view('admin.dep-manage.addForm');
    }

    public function saveDep(Request $request){
        //dd($request);
        $msg = [
            'dep_name.required' => "จำเป็นต้องกรอกช่อง ชื่อภาควิชา ",
            'faculty_id.required' => "กรุณาเลือกคณะ "
          ];
    
          $rule = [
            'dep_name' => 'required',
            'faculty_id' => 'required'     
          ];

        $validator = Validator::make($request->all(),$rule,$msg);
          
        if ($validator->passes()) {
            try{
                $dep_id = f::genDepId();
                DB::table('departments')->insert([
                    'department_id' =>$dep_id,
                    'department_name'=>$request->dep_name,
                    'faculty_id' =>$request->faculty_id,
                ]);
            }catch (\Exception $e) {
                return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
            }
            return response()->json(['success'=>'บันทึกข้อมูลผู้ใช้เรียบร้อยเเล้ว.']);
        }
        else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function editDep($id=''){
        if($id !=''){
            $dep = DB::table('departments')->where('department_id',$id)->first();
            $datas = array(
                'dep' => $dep
            );
            return view('admin.dep-manage.editForm',$datas);
        }
    }

    public function saveeditDep(Request $request){
        $msg = [
            'dep_id.required' => "จำเป็นต้องกรอกช่อง รหัสภาควิชา ",
            'dep_name.required' => "จำเป็นต้องกรอกช่อง ชื่อภาควิชา ",
            'faculty_id.required' => "กรุณาเลือกคณะ "
          ];
    
          $rule = [
            'dep_id' => 'required',
            'dep_name' => 'required',
            'faculty_id' => 'required'     
          ];

        $validator = Validator::make($request->all(),$rule,$msg);
          
        if ($validator->passes()) {
            try{
                DB::table('departments')
                    ->where('department_id',$request->dep_id)       
                    ->update([
                    'department_name'=>$request->dep_name,
                    'faculty_id' =>$request->faculty_id
                    ]);
            }catch (\Exception $e) {
                return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
            }
            return response()->json(['success'=>'บันทึกข้อมูลผู้ใช้เรียบร้อยเเล้ว.']);
        }
        else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteDep($id=''){
        if($id!=''){
            if(Auth::user()->user_level=="admin"){
                DB::table('departments')->where('department_id',$id)->delete();
                return redirect('admin/dep-manage');
            }
            else{
                echo "ไม่สามารถลบผู้ดูแลระบบได้ หรือคุณไม่มีสิทธ์ลบผู้ใช้";
            }
        }
        else echo "เกิดข้อผิดพลาดบางประการ จึงไม่สามารถลบได้";
    }

    /********************************************************/
    /*                  Faculty Manage                   */
    /********************************************************/

    public function indexFac(){
        $fac = f::getFaculty();
        return view('admin.fac-manage.table',['fac'=>$fac]);
    }

    public function addFac(){
        return view('admin.fac-manage.addForm');
    }

    public function saveFac(Request $request){
        //dd($request);
        $msg = [
            'fac_name.required' => "จำเป็นต้องกรอกช่อง ชื่อคณะ ",
          ];
    
          $rule = [
            'fac_name' => 'required'
          ];

        $validator = Validator::make($request->all(),$rule,$msg);
          
        if ($validator->passes()) {
            try{
                $fac_id = f::genFacId();
                DB::table('facultys')->insert([
                    'faculty_id' =>$fac_id,
                    'faculty_name'=>$request->fac_name
                ]);
            }catch (\Exception $e) {
                return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
            }
            return response()->json(['success'=>'บันทึกข้อมูลผู้ใช้เรียบร้อยเเล้ว.']);
        }
        else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function editFac($id=''){
        if($id !=''){
            $fac = DB::table('facultys')->where('faculty_id',$id)->first();
            $datas = array(
                'fac' => $fac
            );
            return view('admin.fac-manage.editForm',$datas);
        }
    }

    public function saveeditFac(Request $request){
        $msg = [
            'fac_id.required' => "จำเป็นต้องกรอกช่อง รหัสคณะ ",
            'fac_name.required' => "จำเป็นต้องกรอกช่อง ชื่อคณะ ",
          ];
    
          $rule = [
            'fac_id' => 'required',
            'fac_name' => 'required'
          ];

        $validator = Validator::make($request->all(),$rule,$msg);
          
        if ($validator->passes()) {
            try{
                DB::table('facultys')
                    ->where('faculty_id',$request->fac_id)       
                    ->update([
                    'faculty_name'=>$request->fac_name
                    ]);
            }catch (\Exception $e) {
                return response()->json(['errException'=>"เกิดข้อผิดพลาดบางอย่างในการบันทึกข้อมูล โปรดลองอีกครั้ง"]);
            }
            return response()->json(['success'=>'บันทึกข้อมูลผู้ใช้เรียบร้อยเเล้ว.']);
        }
        else return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function deleteFac($id=''){
        if($id!=''){
            if(Auth::user()->user_level=="admin"){
                DB::table('facultys')->where('faculty_id',$id)->delete();
                return redirect('admin/fac-manage');
            }
            else{
                echo "ไม่สามารถลบผู้ดูแลระบบได้ หรือคุณไม่มีสิทธ์ลบผู้ใช้";
            }
        }
        else echo "เกิดข้อผิดพลาดบางประการ จึงไม่สามารถลบได้";
    }

   



    public function showDocs($task){
        $tb =  f::getTableName($task);
        $docs = DB::table($tb->table)
                ->join('documents',$tb->table.".".$tb->id,'=','documents.task_id')
                ->orderBy('documents.lastest_updated','desc')
                ->get();
        $data = array(
            "taskName" => $tb->name,
            "docs" => $docs
        );
        //dd($docs);
        return view('admin.docs',$data);
    }

    public function showAlbums(){
        $albums= DB::table('albums')
            ->orderBy('lastest_updated', 'desc')
            ->get();
        $datas = array(
                'albums' => $albums,
                'page'  => 'std-portfolio'
            );
        return view('admin.album.index',$datas);
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
        return view('admin.album.gallery',$datas);

    }




    public function testReport(){
        if(isset($_GET['startTime'])){
            $start = $_GET['startTime'];
            $end = ($_GET['endTime']!="") ? $_GET['endTime'] : date('Y-m-d');
            dd($end);
        }
        return view('admin.user-manage.report');

    }

    public function checkUser(Request $request){
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error'=>'<span style="color:#ff9900"><i class="fa fa-exclamation" aria-hidden="true"></i> กรุณากรอกอีเมลแอดเดรสให้ถูกต้อง</span> ']);
        }
        $cntemail = DB::table('users')->where('email',strtolower($request->email))->first();
        $email = explode("@",$request->email);
        if($email[1]!="fitm.kmutnb.ac.th"){
            return response()->json(['error'=>'<span style="color:#ff9900"><i class="fa fa-exclamation" aria-hidden="true"></i> กรุณากรอกอีเมลของคณะ</span> ']);
        }

        if(empty($cntemail))
            return response()->json(['success'=>'<span style="color:#39ac39"><i class="fa fa-check" aria-hidden="true"></i> สามรถใช้อีเมลแอดเดรสนี้ได้</span>']);
        else {

            return response()->json(['error'=>'<span style="color:#ff1a1a"><i class="fa fa-times" aria-hidden="true"></i> อีเมลแอดเดรสนี้ถูกใช้ในระบบแล้ว</span> ']);
        } 
    }

    public function checkOriginalEmail($uid,Request $request){
        $user = DB::table('users')->select('email')->where('id',$uid)->first();
        if($user->email == strtolower($request->email)){
            return response()->json(['success'=>'']);
        }
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['error'=>'<span style="color:#ff9900"><i class="fa fa-exclamation" aria-hidden="true"></i> กรุณากรอกอีเมลแอดเดรสให้ถูกต้อง</span> ']);
        }
        $cntemail = DB::table('users')->where('email',strtolower($request->email))->first();
        if(empty($cntemail))
            return response()->json(['success'=>'<span style="color:#39ac39"><i class="fa fa-check" aria-hidden="true"></i> สามรถใช้อีเมลแอดเดรสนี้ได้</span>']);
        else  return response()->json(['error'=>'<span style="color:#ff1a1a"><i class="fa fa-times" aria-hidden="true"></i> อีเมลแอดเดรสนี้ถูกใช้ในระบบแล้ว</span> ']);
    }

    public function deleteFolder($folderId){
        $this->client = new \Google_Client();
        $this->client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $this->client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
        $this->service = new \Google_Service_Drive($this->client);
       
        $folder = $this->service->files->delete($folderId);
    }

}
