<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Model\SuggestMosque\SuggestMosque;
use App\Model\SuggestMosque\SuggestMosqueFacilities;
use App\Model\SuggestMosque\SuggestMosqueImage;
use App\Model\SuggestMosque\SuggestMosqueLog;
use App\Model\SuggestMosque\SuggestMosqueSalahTimes;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueFacilities;
use App\Model\Mosque\MosqueImage;
use App\Model\Mosque\MosqueSalahTime;

use App\Model\Users;
use App\Model\Admin;

class SuggestMosqueDeclineController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // Decline Function
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/name/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline name mosque suggest mosque response",
     *     description="Decline name mosque suggest mosque response",
     *     operationId="declineNameSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function nameDecline(Request $request){
        $this->validate($request,[
            'id_suggest_mosque'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_admin = $request->input('id_admin');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Name Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $suggestMosque->name_mosque_approve_status = 2;
        $suggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Name Suggest Mosque Declined.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/address/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline address mosque suggest mosque response",
     *     description="Decline address mosque suggest mosque response",
     *     operationId="declineAddressSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function addressDecline(Request $request){
        $this->validate($request,[
            'id_suggest_mosque'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_admin = $request->input('id_admin');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Address Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $suggestMosque->address_approve_status = 2;
        $suggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Address Suggest Mosque Declined.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/latitude/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline latitude mosque suggest mosque response",
     *     description="Decline latitude mosque suggest mosque response",
     *     operationId="declineLatitudeSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function latitudeDecline(Request $request){
        $this->validate($request,[
            'id_suggest_mosque'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_admin = $request->input('id_admin');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Latitude Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $suggestMosque->latitude_approve_status = 2;
        $suggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Latitude Suggest Mosque Declined.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/longitude/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline longitude mosque suggest mosque response",
     *     description="Decline longitude mosque suggest mosque response",
     *     operationId="declineLongitudeSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function longitudeDecline(Request $request){
        $this->validate($request,[
            'id_suggest_mosque'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_admin = $request->input('id_admin');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Longitude Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $suggestMosque->longitude_approve_status = 2;
        $suggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Longitude Suggest Mosque Declined.";

        return response($res, 200);
    }




    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline facilities mosque suggest mosque response",
     *     description="Decline facilities mosque suggest mosque response",
     *     operationId="declineFacilitiesSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function facilitiesDecline(Request $request){
        $this->validate($request,[
            'id_suggest_mosque'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_admin = $request->input('id_admin');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        SuggestMosqueFacilities::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('mosque_facilities_approve_status', 0)
            ->where('soft_delete', 0)
            ->update([
                'mosque_facilities_approve_status' => 2
            ]);

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Facilities Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Facilities Suggest Mosque Declined.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/images/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline images mosque suggest mosque response",
     *     description="Decline images mosque suggest mosque response",
     *     operationId="declineImagesSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function imagesDecline(Request $request){
        $this->validate($request,[
            'id_suggest_mosque'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_admin = $request->input('id_admin');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        SuggestMosqueImage::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('mosque_image_approve_status', 0)
            ->where('soft_delete', 0)
            ->update([
                'mosque_image_approve_status' => 2
            ]);

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Images Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Images Suggest Mosque Declined.";

        return response($res, 200);
    }




    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/decline",
     *     tags={"Decline Suggest Mosque"},
     *     summary="Decline salah times mosque suggest mosque response",
     *     description="Decline salah times mosque suggest mosque response",
     *     operationId="declineSalahTimesSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Salah Times ",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin ",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function salahTimesDecline(Request $request){
        $this->validate($request,[
            'id_suggest_salah_times'=>'required|numeric',
            'id_admin'=>'required|numeric',
        ]);  

        $id_suggest_salah_times = $request->input('id_suggest_salah_times');
        $id_admin = $request->input('id_admin');

        $suggestSalahTimes = SuggestMosqueSalahTimes::find($id_suggest_salah_times);
        if($suggestSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }

        $admin = Admin::find($id_admin);
        if($admin === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Admin for that ID not found.";

            return response($res, 200);
        }

        $suggestMosque = SuggestMosque::find($suggestSalahTimes->id_suggest_mosque);
        $id_mosque = $suggestMosque->id_mosque;

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Images Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $suggestSalahTimes->salah_times_approve_status = 2;
        $suggestSalahTimes->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Salah Times Suggest Mosque Declined.";

        return response($res, 200);
    }
}
