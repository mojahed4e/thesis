<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

* /
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Adldap\Laravel\Facades\Adldap;
*/

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
	

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {		
		$this->middleware('guest')->except('logout');		
    }
	
	
	public function attemptLogin(Request $request)
	{
		
		$credentials = $request->only('email', 'password');
        $email		 = $credentials['email'];
        $password 	 = $credentials['password'];		
		
		$aUsername = preg_split('/@/', $email, -1, PREG_SPLIT_OFFSET_CAPTURE);
				
		//http://apiws/ldap/verify/{username}/{password}
		$vReqPath1 = "http://apiws/ldap/verify/".$aUsername[0][0]."/".$password;
		$vReqPath = "http://apiws2/ldap/verify/";

		$headers = [		   
		    'username:'.$aUsername[0][0],
		    'password:'.$password
		];
				
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $vReqPath,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_CUSTOMREQUEST => "GET",
		));
		$ch = curl_exec($curl);
		//use Storage;
		//Storage::put("user-log/".rand(10,100).'_user_login_log.txt', $vReqPath1."    ==== respose ====> ".$ch); 
		$aAuthreponse = json_decode($ch);
		
		if($aAuthreponse->result == 'true') {
			
			$user = User::where('email', '=', $credentials['email'])->first();
			if (empty($user)) {
				return false;
			}

			if(Auth::loginUsingId($user->id)){
				return redirect('Home');
			}			
		}
		else { 
			// the user doesn't exist in the LDAP server or the password is wrong
			// log error
			return false;
		}
	}	
	
}
