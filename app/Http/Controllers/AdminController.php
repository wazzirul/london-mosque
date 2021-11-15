<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\Admin;

class AdminController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/store",
     *     tags={"Admin"},
     *     summary="Menambah data admin response",
     *     description="Menambah data admin response",
     *     operationId="storeAdmin",
     * 
     *   @SWG\Parameter(
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
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="role",
     *     in="formData",
     *     description="Role Admin (1 = Superadmin, 2 = Normal Admin) ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function store(Request $request){
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required',
            'email'=>'required|email',
            'role'=>'nullable|numeric',
        ]);  

        $username = $request->input('username');
        $password = Hash::make($request->input('password'));
        $email = $request->input('email');
        $role = $request->input('role') ?? 2;

        $data = Admin::create([
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'role' => $role,
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
     *   path="/admin/update",
     *     tags={"Admin"},
     *     summary="Mengedit data admin response",
     *     description="Mengedit data admin response",
     *     operationId="updateAdmin",
     * 
     *   @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ...",
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
     *   @SWG\Parameter(
     *     name="role",
     *     in="formData",
     *     description="Role Admin (1 = Superadmin, 2 = Normal Admin) (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function update(Request $request){
        $this->validate($request,[
            'id_admin'=>'required',
            'username'=>'nullable|unique:admin',
            'email'=>'nullable|unique:admin',
            'role'=>'nullable|numeric',
        ]);  

        $id_admin = $request->input('id_admin');
        $admin = Admin::where('id_admin', $id_admin)
            ->first();

        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $username = $request->input('username') ?? null;
        $password = Hash::make($request->input('password')) ?? null;
        $email = $request->input('email') ?? null;
        $role = $request->input('role') ?? null;

        if($username !== null){
            $admin->username = $username;
        }

        if($password !== null){
            $admin->password = $password;
        }

        if($email !== null){
            $admin->email = $email;
        }

        if($role !== null){
            $admin->role = $role;
        }

        $data = $admin->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $admin;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/delete",
     *     tags={"Admin"},
     *     summary="Menghapus data admin response",
     *     description="Menghapus data admin response",
     *     operationId="deleteAdmin",
     * 
     *   @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ...",
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
            'id_admin'=>'required|numeric',
        ]);  

        $id_admin = $request->input('id_admin');
        $admin = Admin::where('id_admin', $id_admin)
            ->first();

        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $admin->soft_delete = 1;

        $data = $admin->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $admin;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/unDelete",
     *     tags={"Admin"},
     *     summary="Mengembalikan data terhapus data admin response",
     *     description="Mengembalikan data terhapus data admin response",
     *     operationId="unDeleteAdmin",
     * 
     *   @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ...",
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
            'id_admin'=>'required|numeric',
        ]);  

        $id_admin = $request->input('id_admin');
        $admin = Admin::where('id_admin', $id_admin)
            ->first();

        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }else if($admin->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin is active.";

            return response($res, 200);
        }

        $admin->soft_delete = 0;

        $data = $admin->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $admin;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // LOGIN
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/login",
     *     tags={"Admin"},
     *     summary="Login data admin response",
     *     description="Login data admin response",
     *     operationId="loginAdmin",
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
        $admin = Admin::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['login'] = "Declined";
            $res['detail'] = "Data Admin not found.";

            return response($res, 200);
        }else if($admin->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['login'] = "Declined";
            $res['detail'] = "Admin was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $password = Hash::check($request->password, $admin->password);
        if($password){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['login'] = "Granted";
            $res['data'] = $admin;

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
     *   path="/admin/show",
     *     tags={"Admin"},
     *     summary="Show all data admin response",
     *     description="Show all data admin response",
     *     operationId="showAdmin",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $admin = Admin::where('soft_delete', 0)
            ->orderBy('id_admin', 'DESC')
            ->get();

        if(count($admin) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $admin;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $admin;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/showById",
     *     tags={"Admin"},
     *     summary="Show data admin by ID admin response",
     *     description="Show data admin by ID admin response",
     *     operationId="showByIdAdmin",
     * 
     *   @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ...",
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
            'id_admin'=>'required|numeric',
        ]);  

        $id_admin = $request->input('id_admin');
        $admin = Admin::where('id_admin', $id_admin)
            ->first();

        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }else if($admin->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Admin was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $admin;

        return response($res, 200);
    }
}
