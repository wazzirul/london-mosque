<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Admin;
use App\Model\AuthenticationToken;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Session;

class LoginDocsController extends Controller
{
    //
    
    public function login_proses(Request $request){
        $username1 = "londonmosqueapis";
        $password1 = "1234londonmosqueapis5678";
        
        $username = $request->input('username');
        $password = $request->input('password');
        if($username !== $username1 && $password !== $password1){
            $admin = Admin::where('username', $username)
            ->orWhere('email', $username)
            ->first();

            if($admin === null){
                return redirect('/login')->with('failed','Wrong Username and Password');
            }else if($admin->soft_delete === 1){
                return redirect('/login')->with('failed','Wrong Username and Password');
            }

            $checkPassword = Hash::check($request->password, $admin->password);

            if($checkPassword){
                $str_token = Str::random(100);
                $expired = Carbon::now()->endOfDay();

                $auth_token = new AuthenticationToken();
                $auth_token->user_auth = "api";
                $auth_token->auth_token = $str_token;
                $auth_token->expired = $expired;
                $auth_token->save();

                session()->put('login',TRUE);
                Session::put('auth_token', $str_token);
                
                return redirect('/api/documentation');
            }else{
                return redirect('/login')->with('failed','Wrong Username and Password');
            }
        }elseif($username == $username1 && $password == $password1){
            $str_token = Str::random(100);
            $expired = Carbon::now()->endOfDay();

            $auth_token = new AuthenticationToken();
            $auth_token->user_auth = "api";
            $auth_token->auth_token = $str_token;
            $auth_token->expired = $expired;
            $auth_token->save();

            AuthenticationToken::where('user_auth', 'api')
                ->where('expired', '<=', $expired->copy()->subDays(3))
                ->forceDelete();

            session()->put('login',TRUE);
            Session::put('auth_token', $str_token);

            return redirect('/api/documentation');
        }else{
            return redirect('/login')->with('failed','Wrong Username and Password');
        }
    }

    public function login(){
        if(session()->has('login')){
            return redirect('/api/documentation');
        }else{
            return view('login');
        }
    }

    public function logout(){
        session()->flush();
        return redirect('/login')->with('alert','Logout Success');
    }
}
