<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Input as Input;
use Illuminate\Support\Facades\Storage;
use Session;
use DB;
use Illuminate\Support\Facades\Validator;
use Fpdf;

class TestController extends Controller
{
    //
    protected $client;
    protected $folder_id;
    protected $rootFolderId='1DuIEUjTttUWWpBm38wpOpoJH77ACAyHq';
    protected $service;
    protected $ClientId     = '469973196394-jou944jankcuvmemknpljcoqm41l10a7.apps.googleusercontent.com';
    protected $ClientSecret = 'MWym5Pa4F5LKRZpEM-Zct21b';
    protected $refreshToken = '1/lV5seWDbtlM3aswKhB5rs04lRUNthOd2gUTCB15Tl6Q';

    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setClientId($this->ClientId);
        $this->client->setClientSecret($this->ClientSecret);
        $this->client->refreshToken($this->refreshToken);
        $this->service = new \Google_Service_Drive($this->client);

    }

    public function createFolder(){
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => 'drive5555555',
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array($this->rootFolderId)
        ]);
        $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);
        return $folder->id;
    }

    public function uploadBar(){
        return view('pages.test.indexbar');
    }
    public function upload(Request $request){
        $file = request()->file('file');
        //dd($file);
        $folderId = '0Bz-pmuR0EpcvRzAwQ1dRRklaYlE';
        $fileMetadata = new \Google_Service_Drive_DriveFile([
          'name' => $file->getClientOriginalName(),
          'parents' => array($folderId)
        ]);
        $fileget = $this->service->files->create($fileMetadata, array(
          'data' => file_get_contents($request->file('file')->getRealPath()),
          'mimeType' => $file->getMimeType(),
          'uploadType' => 'multipart',
          'fields' => 'id'));
        printf("File ID: %s\n", $fileget->id);
        /*$file = request()->file('file');
        //dd($file);
        Storage::cloud()->put($file->getClientOriginalName(), file_get_contents($request->file('file')->getRealPath()));*/
    }

    public function delete($fileid="id"){
        $this->service->files->delete($fileid);
        echo $fileid ." is deleted.";
    }
    public function trycatch(){
        try{
            $data = DB::table('tbl')->get();
            dd($data);
        }catch (\Exception $e) {
            echo "error!!";
        }
    }

    public function filetag(){
        return view('pages.test.filetag');
    }
    public function filetagreq(Request $request){
        dd($request);
    }

    public function myform()
    {
    	return view('pages.test.my-form');
    }

    /**
     * Display a listing of the myformPost.
     *
     * @return \Illuminate\Http\Response
     */
    public function myformPost(Request $request){

    	$validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ]);

        if ($validator->passes()) {

			return response()->json(['success'=>'Added new records.']);
        }

    	return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function mySelect(){
        return view('pages.test.testSelect');
    }
    public function reqSelect(Request $req){
        dd($req);
    }

    public function editSelect($id){
        $sup = DB::table('tb_test')->select('sup')->where('id','1')->first();
        //dd($sup);
        $supTch = array();
        $supTchId = explode(",",$sup->sup);
        foreach($supTchId as $id){
            $user= DB::table('users')->where('id',$id)->first();
            array_push($supTch,$user);
        }
        $data = array(
            'supTch' => $supTch
        );
        //dd($data);
        return view('pages.test.showSupTch',$data);
    }

    public function saveedittch(Request $req){
        dd($req);
    }

    public function printpr(){
        return view('pages.test.printpr');
    }
    public function printpr2(){
        return view('pages.test.testprint2');
    }
    public function pdfH(){
        Fpdf::AddPage();
        Fpdf::SetFont('Courier', 'B', 18);
        Fpdf::Cell(50, 25, 'Hello World!');
        Fpdf::Output();
    }

    public function imgfromdrive(){
    
        return view('pages.test.imgfromdrive');
    }

    public function csvForm(){
    
        return view('pages.test.csvForm');
    }
    public function csvextract(Request $req){
    
       //dd($req);
       if (!empty(request()->file('csv'))) {
           $file =  request()->file('csv');
           $name = $file->getClientOriginalName();
            echo "file selected: ".$name;
            $row = 1;
            if (($handle = fopen($file->getPathName(), "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    if($row>1){
                        echo $data[1] . "<br />\n";
                    }
                    $row++;
                }
                fclose($handle);
            }
            
        } else {
                echo "No file selected <br />";
        }
    }
    

    
    

}
