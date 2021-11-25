<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\UsersDonation;
use App\Model\Donations;

class DonationsController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/donations/store",
     *     tags={"Donations"},
     *     summary="Storing New data donations response",
     *     description="Storing New data donations response",
     *     operationId="storeDonations",
     * 
     *  @SWG\Parameter(
     *     name="id_user_donation",
     *     in="formData",
     *     description="ID User Donation ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="amount_donation",
     *     in="formData",
     *     description="Amount Donation ...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="date_donation",
     *     in="formData",
     *     description="Date Donation ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="time_donation",
     *     in="formData",
     *     description="Time Donation ...",
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
            'id_user_donation'=>'required|numeric',
            'amount_donation'=>'required|numeric',
            'date_donation'=>'required',
            'time_donation'=>'required',
        ]);  

        $id_user_donation = $request->input('id_user_donation');
        $usersDonation = UsersDonation::find($id_user_donation);
        if($usersDonation === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users Donation for that ID not found.";

            return response($res, 200);
        }

        $amount_donation = $request->input('amount_donation');
        $date_donation = $request->input('date_donation');
        $time_donation = $request->input('time_donation');

        $data = Donations::create([
            'id_user_donation' => $id_user_donation,
            'amount_donation' => $amount_donation,
            'date_donation' => $date_donation,
            'time_donation' => $time_donation,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Storing New Donation Data.";
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/donations/update",
     *     tags={"Donations"},
     *     summary="Updating data donations response",
     *     description="Updating data donations response
           <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateDonations",
     * 
     *   @SWG\Parameter(
     *     name="id_donation",
     *     in="formData",
     *     description="ID Donation...",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="id_user_donation",
     *     in="formData",
     *     description="ID User Donation ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="amount_donation",
     *     in="formData",
     *     description="Amount Donation ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="date_donation",
     *     in="formData",
     *     description="Date Donation ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="time_donation",
     *     in="formData",
     *     description="Time Donation ...",
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
            'id_donation'=>'required|numeric',
            'id_user_donation'=>'nullable|numeric',
            'amount_donation'=>'nullable|numeric',
            'date_donation'=>'nullable',
            'time_donation'=>'nullable',
        ]);  

        $id_donation = $request->input('id_donation');
        $donations = Donations::where('id_donation', $id_donation)
            ->first();

        if($donations === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Donations for that ID not found.";

            return response($res, 200);
        }

        $id_user_donation = $request->input('id_user_donation') ?? null;
        $amount_donation = $request->input('amount_donation') ?? null;
        $date_donation = $request->input('date_donation') ?? null;
        $time_donation = $request->input('time_donation') ?? null;

        if($id_user_donation !== null){
            $usersDonation = UsersDonation::find($id_user_donation);
            if($usersDonation === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Users Donation for that ID not found.";

                return response($res, 200);
            }

            $donations->id_user_donation = $id_user_donation;
        }

        if($amount_donation !== null){
            $donations->amount_donation = $amount_donation;
        }

        if($date_donation !== null){
            $donations->date_donation = $date_donation;
        }

        if($time_donation !== null){
            $donations->time_donation = $time_donation;
        }

        $data = $donations->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Updating Donation Data.";
            $res['data'] = $donations;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/donations/delete",
     *     tags={"Donations"},
     *     summary="Deleting data donations response",
     *     description="Deleting data donations response",
     *     operationId="deleteDonations",
     * 
     *   @SWG\Parameter(
     *     name="id_donation",
     *     in="formData",
     *     description="ID Donation ...",
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
            'id_donation'=>'required|numeric',
        ]);  

        $id_donation = $request->input('id_donation');
        $donations = Donations::where('id_donation', $id_donation)
            ->first();

        if($donations === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Donations for that ID not found.";

            return response($res, 200);
        }

        $donations->soft_delete = 1;

        $data = $donations->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Deleting Donation Data.";
            $res['data'] = $donations;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/donations/unDelete",
     *     tags={"Donations"},
     *     summary="Restoring data in deleted sections donations response",
     *     description="Restoring data in deleted sections donations response",
     *     operationId="unDeleteDonations",
     * 
     *   @SWG\Parameter(
     *     name="id_donation",
     *     in="formData",
     *     description="ID Donation ...",
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
            'id_donation'=>'required|numeric',
        ]);  

        $id_donation = $request->input('id_donation');
        $donations = Donations::where('id_donation', $id_donation)
            ->first();

        if($donations === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Donation for that ID not found.";

            return response($res, 200);
        }else if($donations->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Donation is active.";

            return response($res, 200);
        }

        $donations->soft_delete = 0;

        $data = $donations->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Restoring Donation Data.";
            $res['data'] = $donations;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/donations/show",
     *     tags={"Donations"},
     *     summary="Show all data donations response",
     *     description="Show all data donations response",
     *     operationId="showDonations",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $donations = Donations::where('soft_delete', 0)
            ->orderBy('id_donation', 'DESC')
            ->get();

        if(count($donations) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Returning Donation Data.";
            $res['data'] = $donations;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $donations;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/donations/showById",
     *     tags={"Donations"},
     *     summary="Show data donations by ID donations response",
     *     description="Show data donations by ID donations response",
     *     operationId="showByIdDonations",
     * 
     *   @SWG\Parameter(
     *     name="id_donation",
     *     in="formData",
     *     description="ID Donation ...",
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
            'id_donation'=>'required|numeric',
        ]);  

        $id_donation = $request->input('id_donation');
        $donations = Donations::where('id_donation', $id_donation)
            ->first();

        if($donations === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Donations for that ID not found.";

            return response($res, 200);
        }else if($donations->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Donations was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Success Returning Donation Data.";
        $res['data'] = $donations;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID USERS DONATION
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/donations/showByIdUsersDonation",
     *     tags={"Donations"},
     *     summary="Show data donations by ID donations response",
     *     description="Show data donations by ID donations response",
     *     operationId="showByIdUsersDonationDonations",
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
    public function showByIdUsersDonation(Request $request){
        $this->validate($request,[
            'id_user_donation'=>'required|numeric',
        ]);  

        $id_user_donation = $request->input('id_user_donation');
        $donations = Donations::where('id_user_donation', $id_user_donation)
            ->where('soft_delete', 0)
            ->orderBy('id_donation', 'DESC')
            ->get();

        if(count($donations) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Returning Donation Data.";
            $res['data'] = $donations;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $donations;

            return response($res, 200);
        }
    }
}
