<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Admin;

class AdminController extends Controller
{
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
        $username = $request->input('username');
        $password = $request->input('password');
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
}
