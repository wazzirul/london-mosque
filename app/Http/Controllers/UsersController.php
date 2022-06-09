<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use Carbon\Carbon;

use App\Model\Users;
use App\Model\UsersVerification;
use App\Model\AuthenticationToken;

class UsersController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/store",
     *     tags={"Users"},
     *     summary="Menambah data users response",
     *     description="Menambah data users response",
     *     operationId="storeUsers",
     * 
     *  @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email ...",
     *     required=true,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     description="Username ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="Password ...",
     *     required=true,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function store(Request $request){
        $this->validate($request,[
            'username'=>'required|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);  

        $username = $request->input('username');
        $password = Hash::make($request->input('password'));
        $email = $request->input('email');

        $data = Users::create([
            'username' => $username,
            'password' => $password,
            'email' => $email,
        ]);

        $code_verification = Str::random(25);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = 'londonmosquemailer@gmail.com'; // Email Yang Digunakan untuk Mengirim ----------------------- SETTING EMAIL TERLEBIH DULU
            $mail->Password = 'xzbiglxdktligjxp'; // Password Email Yang Digunakan Untuk Mengirim ----- SETTING EMAIL TERLEBIH DULU
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->isHTML(true);
            $mail->addAddress($email); // Email Yang Penerima Pesan.. Contoh = JagadTour@gmail.com
            $mail->setFrom('londonmosquemailer@gmail.com', 'London Mosque Mailer'); // Email Yang Mengirim Pesan .. Contoh = JagadTour2@gmail.com
            $mail->Subject = 'Sender : London Mosque Mailer';
            $mail->Body = '  
                <body>
                <h1>Click link below to verif your account</h1>
                <p>--------------------------------------------------------------</p>
                <p>'.env("APP_URL").'api/users/verif-account?u='.$email.'&c='.$code_verification.'</p>
                </body>
            ';
            $mail->send();
        } catch (\Throwable $th) {
            //throw $th;
            // $mail_trace = $th;
        }
        

        UsersVerification::create([
            'id_user' => $data->id_user,
            'code_verification' => $code_verification
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/update",
     *     tags={"Users"},
     *     summary="Mengedit data users response",
     *     description="Mengedit data users response",
     *     operationId="updateUsers",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     description="Username (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="Password (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function update(Request $request){
        $this->validate($request,[
            'id_user'=>'required',
            'username'=>'nullable|unique:users',
            'email'=>'nullable|unique:users',
        ]);  

        $id_user = $request->input('id_user');
        $user = Users::where('id_user', $id_user)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that ID not found.";

            return response($res, 200);
        }

        $username = $request->input('username') ?? null;
        $password = $request->input('password') ?? null;
        $email = $request->input('email') ?? null;

        if($username !== null){
            $user->username = $username;
        }

        if($password !== null){
            $user->password = Hash::make($password);
        }

        if($email !== null){
            $user->email = $email;
        }

        $data = $user->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $user;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/delete",
     *     tags={"Users"},
     *     summary="Menghapus data users response",
     *     description="Menghapus data users response",
     *     operationId="deleteUsers",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function delete(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $user = Users::where('id_user', $id_user)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that ID not found.";

            return response($res, 200);
        }

        $user->soft_delete = 1;

        $data = $user->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $user;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/unDelete",
     *     tags={"Users"},
     *     summary="Mengembalikan data terhapus data users response",
     *     description="Mengembalikan data terhapus data users response",
     *     operationId="unDeleteUsers",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function unDelete(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
        ]);

        $id_user = $request->input('id_user');
        $user = Users::where('id_user', $id_user)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that ID not found.";

            return response($res, 200);
        }else if($user->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User is active.";

            return response($res, 200);
        }

        $user->soft_delete = 0;

        $data = $user->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $user;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // LOGIN
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/login",
     *     tags={"Users"},
     *     summary="Login data users response",
     *     description="Login data users response",
     *     operationId="loginUsers",
     * 
     *   @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     description="Username / Email ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="Password ...",
     *     required=true,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function login(Request $request){
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required',
        ]);  

        $username = $request->input('username');
        $user = Users::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['login'] = "Declined";
            $res['detail'] = "Data User not found.";

            return response($res, 200);
        }else if($user->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['login'] = "Declined";
            $res['detail'] = "User was deleted. Please contact customer service.";

            return response($res, 200);
        }else if($user->status_verif === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['login'] = "Declined";
            $res['detail'] = "Please check email to verif your account.";

            return response($res, 200);
        }

        $password = Hash::check($request->password, $user->password);
        if($password){
            $user->status = 1;

            $str_token = Str::random(100);
            $expired = Carbon::now()->endOfDay();
            $find_token = AuthenticationToken::where('user_auth', 'user_'.$user->id_user)
                ->first();

            if($find_token !== null){
                $find_token->auth_token = $str_token;
                $find_token->expired = $expired;
                $find_token->save();
            }else{
                $auth_token = new AuthenticationToken();
                $auth_token->user_auth = "user_".$user->id_user;
                $auth_token->auth_token = $str_token;
                $auth_token->expired = $expired;
                $auth_token->save();
            }

            $user->save();
            $user['auth_token'] = $str_token;

            $res['status_code'] = 200;
            $res['success'] = true;
            $res['login'] = "Granted";
            $res['data'] = $user;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['login'] = "Declined";
            $res['detail'] = "Wrong password.";

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // LOGOUT
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/logout",
     *     tags={"Users"},
     *     summary="Logout data users response",
     *     description="Logout data users response",
     *     operationId="logoutUsers",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function logout(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $user = Users::where('id_user', $id_user)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User not found.";

            return response($res, 200);
        }else if($user->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "User was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $user->status = 0;
        $user->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $user;

        return response($res, 200);
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/users/show",
     *     tags={"Users"},
     *     summary="Show all data users response",
     *     description="Show all data users response",
     *     operationId="showUsers",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $user = Users::where('soft_delete', 0)
            ->orderBy('id_user', 'DESC')
            ->get();

        if(count($user) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['totaldata'] = $user->count();
            $res['data'] = $user;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $user;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW DELETED
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/users/showDeleted",
     *     tags={"Users"},
     *     summary="Show all deleted data users response",
     *     description="Show all deleted data users response",
     *     operationId="showDeteledUsers",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showDeleted(){
        $user = Users::where('soft_delete', 1)
            ->orderBy('id_user', 'DESC')
            ->get();

        if(count($user) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['totaldata'] = $user->count();
            $res['data'] = $user;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $user;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/showById",
     *     tags={"Users"},
     *     summary="Show data users by ID users response",
     *     description="Show data users by ID users response",
     *     operationId="showByIdUsers",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showById(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $user = Users::where('id_user', $id_user)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that ID not found.";

            return response($res, 200);
        }else if($user->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "User was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['totaldata'] = $user->count();
        $res['data'] = $user;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users/showByFilter",
     *     tags={"Users"},
     *     summary="Show data users by Filter users response",
     *     description="Show data users by Filter users response",
     *     operationId="showByFilterUsers",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     description="Username ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="status",
     *     in="formData",
     *     description="Status ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="status_verif",
     *     in="formData",
     *     description="Status Verif ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="soft_delete",
     *     in="formData",
     *     description="Soft Delete Data (Default=0 / Not Deleted) [0 = Not Deleted  ||  1 = Deleted / Archived]",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="skip",
     *     in="formData",
     *     description="Skip how many Data (Default=0)",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="take",
     *     in="formData",
     *     description="Take how many Data (Default=100)",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="order",
     *     in="formData",
     *     description="ASC / DESC (default DESC)",
     *     required=false,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showByFilter(Request $request){

        $id_user = $request->input('id_user');
        $username = $request->input('username');
        $email = $request->input('email');

        $status = $request->input('status');
        $status_verif = $request->input('status_verif');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $user = Users::query()
        ->when($request->has('id_user'), function ($query) use ($id_user) {
            $query->where('users.id_user',$id_user);
        })
        ->when($request->has('username'), function ($query) use ($username) {
            $query->where('users.username','like','%'.$username.'%');
        })
        ->when($request->has('email'), function ($query) use ($email) {
            $query->where('users.email','like','%'.$email.'%');
        })
        ->when($request->has('status'), function ($query) use ($status) {
            $query->where('users.status',$status);
        })
        ->when($request->has('status_verif'), function ($query) use ($status_verif) {
            $query->where('users.status_verif',$status_verif);
        })
        ->where('users.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('users.id_user '.$order)
        ->distinct('users.id_user')
        ->get([
            'users.id_user',
            'users.email',
            'users.username',
            'users.status',
            'users.status_verif',
            'users.soft_delete',
            'users.created_at as created_at',
            'users.updated_at as updated_at'
        ]);

        
        if(count($user) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Users data';
            $res['totaldata'] = $user->count();
            $res['data'] = $user;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $user;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // RESEND VERIFICATION CODE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\POST(
     *   path="/users/resend-verif-account",
     *     tags={"Users"},
     *     summary="Resend verification code users by Email response",
     *     description="Resend verification code users by Email response",
     *     operationId="resendVerifAccountUsers",
     * 
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email ...",
     *     required=true,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function resendVerifAccount(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
        ]);  

        $email = $request->input('email');

        $user = Users::where('email', $email)
            ->first();

        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that Email not found.";

            return response($res, 200);
        }else if($user->status_verif === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "User was verified.";

            return response($res, 200);
        }else if($user->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "User was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $verify = UsersVerification::where('id_user', $user->id_user)
            ->where('soft_delete', 0)
            ->first();

        $code_verification = Str::random(25);

        if($verify !== null){
            if(Carbon::now()->diffInMinutes(Carbon::parse($verify->updated_at)) >= 5){
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->Username = 'londonmosquemailer@gmail.com'; // Email Yang Digunakan untuk Mengirim ----------------------- SETTING EMAIL TERLEBIH DULU
                $mail->Password = 'xzbiglxdktligjxp'; // Password Email Yang Digunakan Untuk Mengirim ----- SETTING EMAIL TERLEBIH DULU
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->isHTML(true);
                $mail->addAddress($email); // Email Yang Penerima Pesan.. Contoh = JagadTour@gmail.com
                $mail->setFrom('londonmosquemailer@gmail.com', 'London Mosque Mailer'); // Email Yang Mengirim Pesan .. Contoh = JagadTour2@gmail.com
                $mail->Subject = 'Sender : London Mosque Mailer';
                $mail->Body = '  
                    <body>
                    <h1>Click link below to verif your account</h1>
                    <p>--------------------------------------------------------------</p>
                    <p>'.env("APP_URL").'api/users/verif-account?u='.$email.'&c='.$code_verification.'</p>
                    </body>
                ';
                $mail->send();

                $verify->code_verification = $code_verification;
                $verify->save();

                $res['status_code'] = 200;
                $res['success'] = true;
                $res['detail'] = "Resend code verification success.";

            }else{
                $timeToWaiting = 5 - (int)Carbon::now()->diffInMinutes(Carbon::parse($verify->updated_at));

                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Please wait ".$timeToWaiting." minutes to resend code verification again.";
                $res['time_to_waiting'] = (int)$timeToWaiting;
            }
        }else{
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = 'londonmosquemailer@gmail.com'; // Email Yang Digunakan untuk Mengirim ----------------------- SETTING EMAIL TERLEBIH DULU
            $mail->Password = 'xzbiglxdktligjxp'; // Password Email Yang Digunakan Untuk Mengirim ----- SETTING EMAIL TERLEBIH DULU
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->isHTML(true);
            $mail->addAddress($email); // Email Yang Penerima Pesan.. Contoh = JagadTour@gmail.com
            $mail->setFrom('londonmosquemailer@gmail.com', 'London Mosque Mailer'); // Email Yang Mengirim Pesan .. Contoh = JagadTour2@gmail.com
            $mail->Subject = 'Sender : London Mosque Mailer';
            $mail->Body = '  
                <body>
                <h1>Click link below to verif your account</h1>
                <p>--------------------------------------------------------------</p>
                <p>'.env("APP_URL").'api/users/verif-account?u='.$email.'&c='.$code_verification.'</p>
                </body>
            ';
            $mail->send();

            $userVerifDeleted = UsersVerification::where('id_user', $user->id_user)
                ->where('soft_delete', 1)
                ->first();

            if($userVerifDeleted !== null){
                $userVerifDeleted->soft_delete = 0;
                $userVerifDeleted->code_verification = $code_verification;
                $userVerifDeleted->save();
            }else{
                UsersVerification::create([
                    'id_user' => $user->id_user,
                    'code_verification' => $code_verification
                ]);
            }

            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Resend code verification success.";
            
        }

        return response($res, 200);
    }





    //////////////////////////////////////////////////////////////////////////////////////
    // VERIFY ACCOUNT
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\GET(
     *   path="/users/verif-account",
     *     tags={"Users"},
     *     summary="Show data users by ID users response",
     *     description="Show data users by ID users response",
     *     operationId="verifAccountUsers",
     * 
     *   @SWG\Parameter(
     *     name="u",
     *     in="query",
     *     description="Email ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="c",
     *     in="query",
     *     description="Code Verification ...",
     *     required=true,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function verifAccount(Request $request){
        $email = $request->input('u');
        $code_verification = $request->input('c');

        $user = Users::where('email', $email)
            ->first();

        if($user === null){
            return "<h2>Data User for that Email not found.</h2>";

        }else if($user->status_verif === 1){
            return "<h2>User was verified.</h2>";

        }else if($user->soft_delete === 1){
            return "<h2>User was deleted. Please contact customer service.</h2>";

        }

        $verify = UsersVerification::where('id_user', $user->id_user)
            ->where('soft_delete', 0)
            ->first();

        if($verify !== null){
            if($code_verification == $verify->code_verification){
                $user->status_verif = 1;
                $user->save();

                $verify->soft_delete = 1;
                $verify->save();
                return "<h2>Verification Success. Go to login page to go to London Mosque user dashboard.</h2>";

            }else{
                return "<h2>Wrong code verification.</h2>";

            }
        }else{
            return "<h2>Code verification was expired.</h2>";

        }
    }
}
