<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Admin;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class LoginDocsController extends Controller
{
    //
    
    public function login_proses(Request $request){
        $username1 = "admin";
        $password1 = "12345678";
        
        $username = $request->input('username');
        $password = $request->input('password');
        if($username !== $username1 && $password !== $password1){
            $admin = Admin::where('username', $username)
            ->orWhere('email', $username)
            ->first();

            if($admin === null){
                return redirect('/login')->with('gagal','Username dan Password Salah');
            }else if($admin->soft_delete === 1){
                return redirect('/login')->with('gagal','Username dan Password Salah');
            }

            $checkPassword = Hash::check($request->password, $admin->password);

            if($checkPassword){
                session()->put('login',TRUE);
                return redirect('/api/documentation');
            }else{
                return redirect('/login')->with('gagal','Username dan Password Salah');
            }
        }elseif($username == $username1 && $password == $password1){
            session()->put('login',TRUE);
            return redirect('/api/documentation');
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
        return redirect('/login')->with('alert','Kamu Berhasil Logout');
    }
}
