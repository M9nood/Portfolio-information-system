<?php
namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Socialite;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;

class AuthController extends Controller
{

    use  ThrottlesLogins;

    protected $redirectTo = '/';
    protected $client;
    protected $folder_id;
    protected $rootFolderId='1DuIEUjTttUWWpBm38wpOpoJH77ACAyHq';
    protected $service;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        
            $user = Socialite::driver('google')->stateless()->user();
            Session::put('avatar', $user->avatar);

            $id = DB::table('users')
                        ->select('id')
                        ->where('email','=',strtolower($user->email))
                        ->where('active','=','yes')
                        ->first();
            if(!empty($id)) {
                Auth::loginUsingId($id->id);
                if(Auth::user()->drive_folder_id==null){
                    $folderId =  $this->createFolder(Auth::user()->email);
                    DB::table('users')
                        ->where('id',Auth::user()->id)
                        ->update([
                        'drive_folder_id' => $folderId
                        ]);
                }
                if(Auth::user()->user_level=="admin")
                    return redirect('/admin');
                else return redirect('/');
            }
            else{
                return redirect('login')->with('status', 'ไม่พบบัญชีผู้ใช้นี้ในระบบ');
                
            }
            
       
    }
    
    public function createFolder($foldername){
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
}

?>
