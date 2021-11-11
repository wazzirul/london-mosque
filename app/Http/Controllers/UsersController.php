<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\Users;

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
            'username'=>'required',
            'email'=>'required|email',
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
        $password = Hash::make($request->input('password')) ?? null;
        $email = $request->input('email') ?? null;

        if($username !== null){
            $user->username = $username;
        }

        if($password !== null){
            $user->password = $password;
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
        $res['data'] = $user;

        return response($res, 200);
    }
}
