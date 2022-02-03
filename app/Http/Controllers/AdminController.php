<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Model\Admin;
use App\Model\AuthenticationToken;

class AdminController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/store",
     *     tags={"Admin"},
     *     summary="Store New data admin response",
     *     description="Store New data admin response",
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
            $res['detail'] = "Success Storing New Admin Data.";
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
     *     summary="Updating data admin response",
     *     description="Updating data admin response
           <br>#Leave it <strong>blank</strong> if you don't want to change the data",
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
     *     description="Username ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="Password ...",
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
    public function update(Request $request){
        $this->validate($request,[
            'id_admin'=>'required',
            'username'=>'nullable|unique:admin',
            'email'=>'nullable|unique:admin',
            'role'=>'nullable|numeric',
            'password'=>'nullable',
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
        $password = $request->input('password') ?? null;
        $email = $request->input('email') ?? null;
        $role = $request->input('role') ?? null;

        if($username !== null){
            $admin->username = $username;
        }

        if($password !== null){
            $admin->password = Hash::make($password);
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
            $res['detail'] = "Success Updating Admin Data.";
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
     *     summary="Deleting data admin response",
     *     description="Deleting data admin response",
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
            $res['detail'] = "Success Deleting Admin Data.";
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
     *     summary="Restoring / Undelete data in deleted section admin response",
     *     description="Restoring / Undelete data in deleted section admin response",
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
            $res['detail'] = "Success Restoring / Undelete Admin Data.";
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
            $str_token = Str::random(100);
            $expired = Carbon::now()->endOfDay();
            $find_token = AuthenticationToken::where('user_auth', 'admin_'.$admin->id_admin)
                ->first();

            if($find_token !== null){
                $find_token->auth_token = $str_token;
                $find_token->expired = $expired;
                $find_token->save();
            }else{
                $auth_token = new AuthenticationToken();
                $auth_token->user_auth = "admin_".$admin->id_admin;
                $auth_token->auth_token = $str_token;
                $auth_token->expired = $expired;
                $auth_token->save();
            }

            $admin['auth_token'] = $str_token;

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
            $res['detail'] = "Success Return Admin Data.";
            $res['totaldata'] = $admin->count();
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
    // SHOW DELETED
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/admin/showDeleted",
     *     tags={"Admin"},
     *     summary="Show Deleted all data admin response",
     *     description="Show Deleted all data admin response",
     *     operationId="showDeletedAdmin",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showDeleted(){
        $admin = Admin::where('soft_delete', 1)
            ->orderBy('id_admin', 'DESC')
            ->get();

        if(count($admin) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Return Admin Data.";
            $res['totaldata'] = $admin->count();
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
        $res['detail'] = "Success Return Admin Data.";
        $res['totaldata'] = $admin->count();
        $res['data'] = $admin;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/admin/showByFilter",
     *     tags={"Admin"},
     *     summary="Show data admin by Filter admin response",
     *     description="Show data admin by Filter admin response",
     *     operationId="showByFilterAdmin",
     * 
     *   @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ...",
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
     *     name="role",
     *     in="formData",
     *     description="Role ...",
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
        $id_admin = $request->input('id_admin');
        $username = $request->input('username');
        $email = $request->input('email');
        $role = $request->input('role');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $admin = Admin::query()
        ->when($request->has('id_admin'), function ($query) use ($id_admin) {
            $query->where('admin.id_admin',$id_admin);
        })
        ->when($request->has('username'), function ($query) use ($username) {
            $query->where('admin.username','like','%'.$username.'%');
        })
        ->when($request->has('email'), function ($query) use ($email) {
            $query->where('admin.email','like','%'.$email.'%');
        })
        ->when($request->has('role'), function ($query) use ($role) {
            $query->where('admin.role',$role);
        })
        ->where('admin.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('admin.id_admin '.$order)
        ->distinct('admin.id_admin')
        ->get([
            'admin.id_admin',
            'admin.email',
            'admin.username',
            'admin.role',
            'admin.soft_delete',
            'admin.created_at as created_at',
            'admin.updated_at as updated_at'
        ]);

        
        if(count($admin) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Admins data';
            $res['totaldata'] = $admin->count();
            $res['data'] = $admin;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $admin;

            return response($res, 200);
        }
    }
}
