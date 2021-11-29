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

class SuggestMosqueApproveController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // Approve Function
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/name/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve name mosque suggest mosque response",
     *     description="Approve name mosque suggest mosque response",
     *     operationId="approveNameSuggestMosque",
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
    public function nameApprove(Request $request){
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
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $suggestMosque->name_mosque_approve_status = 1;
        
        $mosque->name_mosque = $suggestMosque->name_mosque;
        
        $suggestMosque->save();
        $mosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Name Suggest Mosque Approved.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/address/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve address mosque suggest mosque response",
     *     description="Approve address mosque suggest mosque response",
     *     operationId="approveAddressSuggestMosque",
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
    public function addressApprove(Request $request){
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
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $suggestMosque->address_approve_status = 1;

        $mosque->address = $suggestMosque->address;

        $suggestMosque->save();
        $mosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Address Suggest Mosque Approved.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/latitude/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve latitude mosque suggest mosque response",
     *     description="Approve latitude mosque suggest mosque response",
     *     operationId="approveLatitudeSuggestMosque",
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
    public function latitudeApprove(Request $request){
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
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $suggestMosque->latitude_approve_status = 1;

        $mosque->latitude = $suggestMosque->latitude;

        $suggestMosque->save();
        $mosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Latitude Suggest Mosque Approved.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/longitude/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve longitude mosque suggest mosque response",
     *     description="Approve longitude mosque suggest mosque response",
     *     operationId="approveLongitudeSuggestMosque",
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
    public function longitudeApprove(Request $request){
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
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $suggestMosque->longitude_approve_status = 1;

        $mosque->longitude = $suggestMosque->longitude;

        $suggestMosque->save();
        $mosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Longitude Suggest Mosque Approved.";

        return response($res, 200);
    }




    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve facilities mosque suggest mosque response",
     *     description="Approve facilities mosque suggest mosque response",
     *     operationId="approveFacilitiesSuggestMosque",
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
    public function facilitiesApprove(Request $request){
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

        $suggestFacilities = SuggestMosqueFacilities::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('mosque_facilities_approve_status', 0)
            ->where('soft_delete', 0)
            ->get();

        MosqueFacilities::where('id_mosque', $id_mosque)
            ->forceDelete();

        foreach ($suggestFacilities as $key => $value) {
            $newMosqueFacilities = new MosqueFacilities();
            $newMosqueFacilities->id_mosque = $id_mosque;
            $newMosqueFacilities->id_facilities = $value->id_facilities;
            $newMosqueFacilities->save();
        }

        SuggestMosqueFacilities::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('soft_delete', 0)
            ->update([
                'mosque_facilities_approve_status' => 1
            ]);

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Facilities Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Facilities Suggest Mosque Approved.";

        return response($res, 200);
    }



    /**
     * @SWG\Post(
     *   path="/suggest-mosque/images/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve images mosque suggest mosque response",
     *     description="Approve images mosque suggest mosque response",
     *     operationId="approveImagesSuggestMosque",
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
    public function imagesApprove(Request $request){
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

        $suggestImage = SuggestMosqueImage::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('mosque_image_approve_status', 0)
            ->where('soft_delete', 0)
            ->get();

        foreach ($suggestImage as $key => $value) {
            $newMosqueImage = new MosqueImage();
            $newMosqueImage->id_mosque = $id_mosque;
            $newMosqueImage->mosque_image_url = $value->mosque_image_url;
            $newMosqueImage->save();
        }

        SuggestMosqueImage::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('soft_delete', 0)
            ->update([
                'mosque_image_approve_status' => 1
            ]);

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Images Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Images Suggest Mosque Approved.";

        return response($res, 200);
    }




    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/approve",
     *     tags={"Approve Suggest Mosque"},
     *     summary="Approve salah times mosque suggest mosque response",
     *     description="Approve salah times mosque suggest mosque response",
     *     operationId="approveSalahTimesSuggestMosque",
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
    public function salahTimesApprove(Request $request){
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

        $findSalahTimesMosque = MosqueSalahTime::where('id_mosque', $id_mosque)
            ->where('date_salah_times', $suggestSalahTimes->date_salah_times)
            ->where('type_salah_times', $suggestSalahTimes->type_salah_times)
            ->where('soft_delete', 0)
            ->first();

        if($findSalahTimesMosque !== null){
            $findSalahTimesMosque->time_salah_times = $suggestSalahTimes->time_salah_times;
            $findSalahTimesMosque->save();
        }else{
            $newSalahTimesMosque = new MosqueSalahTime();
            $newSalahTimesMosque->id_mosque = $id_mosque;
            $newSalahTimesMosque->date_salah_times = $suggestSalahTimes->date_salah_times;
            $newSalahTimesMosque->type_salah_times = $suggestSalahTimes->type_salah_times;
            $newSalahTimesMosque->time_salah_times = $suggestSalahTimes->time_salah_times;
            $newSalahTimesMosque->save();
        }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->field_name_suggest_edit = "Salah Time Mosque of ".$mosque->name_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $suggestSalahTimes->salah_times_approve_status = 1;
        $suggestSalahTimes->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Salah Times Suggest Mosque Approved.";

        return response($res, 200);
    }
}
