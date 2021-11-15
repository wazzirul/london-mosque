<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Users;
use App\Model\UsersDonation;

class UsersDonationController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users-donation/store",
     *     tags={"Users Donation"},
     *     summary="Menambah data users donation response",
     *     description="Menambah data users donation response",
     *     operationId="storeUsersDonation",
     * 
     *  @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="type_donation",
     *     in="formData",
     *     description="Type Donation (1 = VISA, 2 = PAYPAL) ...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="repeated_donation",
     *     in="formData",
     *     description="Repeat Donation (0 = False, 1 = Week, 2 = Month, 3 = Year) ...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="anonymous_donation",
     *     in="formData",
     *     description="Anonymous Donation (0 = False, 1 = True) ...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     description="First Name ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="last_name",
     *     in="formData",
     *     description="Last Name ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="email_address",
     *     in="formData",
     *     description="Email Address ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="mobile_number",
     *     in="formData",
     *     description="Mobile Number ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="card_name",
     *     in="formData",
     *     description="Card Name ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="cvc",
     *     in="formData",
     *     description="CVC ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="cardholder_name",
     *     in="formData",
     *     description="Cardholer Name ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="exp",
     *     in="formData",
     *     description="Expired Date ...",
     *     required=true,
     *     type="string"
     *   ),
     * 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function store(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
            'type_donation'=>'required|numeric',
            'repeated_donation'=>'required|numeric',
            'anonymous_donation'=>'required|numeric',
            'first_name'=>'required',
            'last_name'=>'required',
            'email_address'=>'required|email',
            'mobile_number'=>'required',
            'card_name'=>'required',
            'cvc'=>'required',
            'cardholder_name'=>'required',
            'exp'=>'required',
        ]);  

        $id_user = $request->input('id_user');
        $user = Users::find($id_user);
        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that ID not found.";

            return response($res, 200);
        }

        $type_donation = $request->input('type_donation');
        $repeated_donation = $request->input('repeated_donation');
        $anonymous_donation = $request->input('anonymous_donation');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email_address = $request->input('email_address');
        $mobile_number = $request->input('mobile_number');
        $card_name = $request->input('card_name');
        $cvc = $request->input('cvc');
        $cardholder_name = $request->input('cardholder_name');
        $exp = $request->input('exp');

        $data = UsersDonation::create([
            'id_user' => $id_user,
            'type_donation' => $type_donation,
            'repeated_donation' => $repeated_donation,
            'anonymous_donation' => $anonymous_donation,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email_address' => $email_address,
            'mobile_number' => $mobile_number,
            'card_name' => $card_name,
            'cvc' => $cvc,
            'cardholder_name' => $cardholder_name,
            'exp' => $exp,
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
     *   path="/users-donation/update",
     *     tags={"Users Donation"},
     *     summary="Mengedit data users donation response",
     *     description="Mengedit data users donation response",
     *     operationId="updateUsersDonation",
     * 
     *   @SWG\Parameter(
     *     name="id_user_donation",
     *     in="formData",
     *     description="ID User Donation...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="type_donation",
     *     in="formData",
     *     description="Type Donation (1 = VISA, 2 = PAYPAL) (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="repeated_donation",
     *     in="formData",
     *     description="Repeat Donation (0 = False, 1 = Week, 2 = Month, 3 = Year) (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="anonymous_donation",
     *     in="formData",
     *     description="Anonymous Donation (0 = False, 1 = True) (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     description="First Name (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="last_name",
     *     in="formData",
     *     description="Last Name (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="email_address",
     *     in="formData",
     *     description="Email Address (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="mobile_number",
     *     in="formData",
     *     description="Mobile Number (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="card_name",
     *     in="formData",
     *     description="Card Name (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="cvc",
     *     in="formData",
     *     description="CVC (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="cardholder_name",
     *     in="formData",
     *     description="Cardholer Name (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="exp",
     *     in="formData",
     *     description="Expired Date (Kosongi jika tidak ingin merubah data)...",
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
            'id_user_donation'=>'required|numeric',
            'id_user'=>'nullable|numeric',
            'type_donation'=>'nullable|numeric',
            'repeated_donation'=>'nullable|numeric',
            'anonymous_donation'=>'nullable|numeric',
            'first_name'=>'nullable',
            'last_name'=>'nullable',
            'email_address'=>'nullable|email',
            'mobile_number'=>'nullable',
            'card_name'=>'nullable',
            'cvc'=>'nullable',
            'cardholder_name'=>'nullable',
            'exp'=>'nullable',
        ]);  

        $id_user_donation = $request->input('id_user_donation');
        $usersDonation = UsersDonation::where('id_user_donation', $id_user_donation)
            ->first();

        if($usersDonation === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users Donation for that ID not found.";

            return response($res, 200);
        }

        $id_user = $request->input('id_user') ?? null;
        $type_donation = $request->input('type_donation') ?? null;
        $repeated_donation = $request->input('repeated_donation') ?? null;
        $anonymous_donation = $request->input('anonymous_donation') ?? null;
        $first_name = $request->input('first_name') ?? null;
        $last_name = $request->input('last_name') ?? null;
        $email_address = $request->input('email_address') ?? null;
        $mobile_number = $request->input('mobile_number') ?? null;
        $card_name = $request->input('card_name') ?? null;
        $cvc = $request->input('cvc') ?? null;
        $cardholder_name = $request->input('cardholder_name') ?? null;
        $exp = $request->input('exp') ?? null;

        if($id_user !== null){
            $user = Users::find($id_user);
            if($user === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data User for that ID not found.";

                return response($res, 200);
            }
            
            $usersDonation->id_user = $id_user;
        }

        if($type_donation !== null){
            $usersDonation->type_donation = $type_donation;
        }

        if($repeated_donation !== null){
            $usersDonation->repeated_donation = $repeated_donation;
        }

        if($anonymous_donation !== null){
            $usersDonation->anonymous_donation = $anonymous_donation;
        }

        if($first_name !== null){
            $usersDonation->first_name = $first_name;
        }

        if($last_name !== null){
            $usersDonation->last_name = $last_name;
        }

        if($email_address !== null){
            $usersDonation->email_address = $email_address;
        }

        if($mobile_number !== null){
            $usersDonation->mobile_number = $mobile_number;
        }

        if($card_name !== null){
            $usersDonation->card_name = $card_name;
        }

        if($cvc !== null){
            $usersDonation->cvc = $cvc;
        }

        if($cardholder_name !== null){
            $usersDonation->cardholder_name = $cardholder_name;
        }

        if($exp !== null){
            $usersDonation->exp = $exp;
        }

        $data = $usersDonation->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users-donation/delete",
     *     tags={"Users Donation"},
     *     summary="Menghapus data users donation response",
     *     description="Menghapus data users donation response",
     *     operationId="deleteUsersDonation",
     * 
     *   @SWG\Parameter(
     *     name="id_user_donation",
     *     in="formData",
     *     description="ID User Donation ...",
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
            'id_user_donation'=>'required|numeric',
        ]);  

        $id_user_donation = $request->input('id_user_donation');
        $usersDonation = UsersDonation::where('id_user_donation', $id_user_donation)
            ->first();

        if($usersDonation === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users Donation for that ID not found.";

            return response($res, 200);
        }

        $usersDonation->soft_delete = 1;

        $data = $usersDonation->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users-donation/unDelete",
     *     tags={"Users Donation"},
     *     summary="Mengembalikan data terhapus data users donation response",
     *     description="Mengembalikan data terhapus data users donation response",
     *     operationId="unDeleteUsersDonation",
     * 
     *   @SWG\Parameter(
     *     name="id_user_donation",
     *     in="formData",
     *     description="ID User Donation ...",
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
            'id_user_donation'=>'required|numeric',
        ]);  

        $id_user_donation = $request->input('id_user_donation');
        $usersDonation = UsersDonation::where('id_user_donation', $id_user_donation)
            ->first();

        if($usersDonation === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users Donation for that ID not found.";

            return response($res, 200);
        }else if($usersDonation->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users Donation is active.";

            return response($res, 200);
        }

        $usersDonation->soft_delete = 0;

        $data = $usersDonation->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/users-donation/show",
     *     tags={"Users Donation"},
     *     summary="Show all data users donation response",
     *     description="Show all data users donation response",
     *     operationId="showUsersDonation",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $usersDonation = UsersDonation::where('soft_delete', 0)
            ->orderBy('id_user_donation', 'DESC')
            ->get();

        if(count($usersDonation) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users-donation/showById",
     *     tags={"Users Donation"},
     *     summary="Show data users donation by ID users donation response",
     *     description="Show data users donation by ID users donation response",
     *     operationId="showByIdUsersDonation",
     * 
     *   @SWG\Parameter(
     *     name="id_user_donation",
     *     in="formData",
     *     description="ID User Donation ...",
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
            'id_user_donation'=>'required|numeric',
        ]);  

        $id_user_donation = $request->input('id_user_donation');
        $usersDonation = UsersDonation::where('id_user_donation', $id_user_donation)
            ->first();

        if($usersDonation === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users Donation for that ID not found.";

            return response($res, 200);
        }else if($usersDonation->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Users Donation was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $usersDonation;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID USER
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/users-donation/showByIdUser",
     *     tags={"Users Donation"},
     *     summary="Show data users donation by ID_user users donation response",
     *     description="Show data users donation by ID_user users donation response",
     *     operationId="showByIdUserUsersDonation",
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
    public function showByIdUser(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $usersDonation = UsersDonation::where('id_user', $id_user)
            ->where('soft_delete', 0)
            ->orderBy('id_user_donation', 'DESC')
            ->get();

        if(count($usersDonation) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $usersDonation;

            return response($res, 200);
        }
    }
}
