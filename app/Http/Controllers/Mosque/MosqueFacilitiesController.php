<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Facilities;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueSalahTime;
use App\Model\Mosque\MosqueFacilities;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

class MosqueFacilitiesController extends Controller
{
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-facilities/store",
     *     tags={"Mosque Facilities"},
     *     summary="Storing new data Mosque Facilities response",
     *     description="Storing new data Mosque Facilities response",
     *     operationId="storeMosqueFacilities",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities ...",
     *     required=true,
     *     type="integer"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function store(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
            'id_facilities'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $id_facilities = $request->input('id_facilities');

        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }else if($mosque->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        
        $facilities = Facilities::where('id_facilities', $id_facilities)
            ->first();

        if($facilities === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facilities for that ID not found.";

            return response($res, 200);
        }else if($facilities->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Facilities was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $data = MosqueFacilities::create([
            'id_mosque' => $id_mosque,
            'id_facilities' => $id_facilities
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Mosque Facilities data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }



    
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Update
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-facilities/update",
     *     tags={"Mosque Facilities"},
     *     summary="Updating data Mosque Facilities response",
     *     description="Updating data Mosque Facilities response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_facilities",
     *     in="formData",
     *     description="ID Mosque Facilities ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities ...",
     *     required=false,
     *     type="integer"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function update(Request $request){
        $this->validate($request,[
            'id_mosque_facilities'=>'required|numeric',
            'id_mosque'=>'nullable|numeric',
            'id_facilities'=>'nullable|numeric',
        ]);  

        $id_mosque_facilities = $request->input('id_mosque_facilities');
        $id_mosque = $request->input('id_mosque');
        $id_facilities = $request->input('id_facilities');

        $data = MosqueFacilities::find($id_mosque_facilities);

        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Facilities for that ID not found.";

            return response($res, 200);
        }else if($data->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque Facilities was deleted. Please contact customer service.";

            return response($res, 200);
        }

        if($id_mosque !== null){
            
        $mosque = Mosque::where('id_mosque', $id_mosque)
        ->first();

            if($mosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Mosque for that ID not found.";

                return response($res, 200);
            }else if($mosque->soft_delete === 1){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Mosque was deleted. Please contact customer service.";

                return response($res, 200);
            }

            $data->id_mosque = $id_mosque;
        }

        if($id_facilities !== null){
            $facilities = Facilities::where('id_facilities', $id_facilities)
            ->first();

            if($facilities === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Facilities for that ID not found.";

                return response($res, 200);
            }else if($facilities->soft_delete === 1){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Facilities was deleted. Please contact customer service.";

                return response($res, 200);
            }

            $data->id_facilities = $id_facilities;
        }

        $data = $data->save();

        $data = MosqueFacilities::with('Mosque','Facilities')->find($id_mosque_facilities);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque Facilities data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }



    
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Delete
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-facilities/delete",
     *     tags={"Mosque Facilities"},
     *     summary="Deleting data Mosque Facilities response",
     *     description="Deleting data Mosque Facilities response",
     *     operationId="deleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_facilities",
     *     in="formData",
     *     description="ID Mosque Facilities ...",
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
            'id_mosque_facilities'=>'required|numeric',
        ]);  

        $id_mosque_facilities = $request->input('id_mosque_facilities');

        $data = MosqueFacilities::find($id_mosque_facilities);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Facilities for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 1;
        $data = $data->save();

        $data = MosqueFacilities::find($id_mosque_facilities);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque Facilities data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }





    
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Undelete
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-facilities/undelete",
     *     tags={"Mosque Facilities"},
     *     summary="Restoring / undelete data Mosque Facilities response",
     *     description="Restoring / undelete data Mosque Facilities response",
     *     operationId="undeleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_facilities",
     *     in="formData",
     *     description="ID Mosque Facilities ...",
     *     required=true,
     *     type="integer"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function undelete(Request $request){
        $this->validate($request,[
            'id_mosque_facilities'=>'required|numeric',
        ]);  

        $id_mosque_facilities = $request->input('id_mosque_facilities');

        $data = MosqueFacilities::find($id_mosque_facilities);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Facilities for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 0;
        $data = $data->save();

        $data = MosqueFacilities::find($id_mosque_facilities);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring / undelete Mosque Facilities data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque-facilities/show",
     *     tags={"Mosque Facilities"},
     *     summary="Show all data mosque response",
     *     description="Show all data mosque response",
     *     operationId="showMosques",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){ 
        $data = MosqueFacilities::where('soft_delete', 0)
        ->orderBy('id_mosque_facilities', 'DESC')
        ->get();

        $expiresAt = Carbon::now()->addDays();  

        foreach($data as $index => $d){
            $mosque = Mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();
            $facilities = Facilities::where('id_facilities', $d->id_facilities)
                ->where('soft_delete', 0)
                ->get();
            foreach ($facilities as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->icon_facilities);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $facilities[$key]->icon_facilities_url_firebase = $image;
                }else{
                    $facilities[$key]->icon_facilities_url_firebase = null;
                }
            }

            $data[$index]->mosque = $mosque;
            $data[$index]->facilities = $facilities;
        }


        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $data;
            $res['message'] = 'Success return Mosque Facilities data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-facilities/showById",
     *     tags={"Mosque Facilities"},
     *     summary="Show all data mosque Facilities response",
     *     description="Show all data mosque Facilities response",
     *     operationId="showByIdSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_facilities",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showById(Request $request){ 
        
        $this->validate($request,[
            'id_mosque_facilities'=>'required|numeric',
        ]);  

        $id_mosque_facilities = $request->input('id_mosque_facilities');
        $expiresAt = Carbon::now()->addDays();  
        
        $data = MosqueFacilities::where('id_mosque_facilities', $id_mosque_facilities)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        
        $data = MosqueFacilities::where('id_mosque_facilities', $id_mosque_facilities)
        ->get();

        foreach($data as $index => $d){
            $mosque = Mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();
            $facilities = Facilities::where('id_facilities', $d->id_facilities)
                ->where('soft_delete', 0)
                ->get();
            foreach ($facilities as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->icon_facilities);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $facilities[$key]->icon_facilities_url_firebase = $image;
                }else{
                    $facilities[$key]->icon_facilities_url_firebase = null;
                }
            }

            $data[$index]->mosque = $mosque;
            $data[$index]->facilities = $facilities;
        }

        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque Facilities data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-facilities/showByFilter",
     *     tags={"Mosque Facilities"},
     *     summary="Show all data mosque Facilities response",
     *     description="Show all data mosque Facilities response",
     *     operationId="showByFilterSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_facilities",
     *     in="formData",
     *     description="ID Mosque Facilities ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities ...",
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
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showByFilter(Request $request){ 
        
        $id_mosque_facilities = $request->input('id_mosque_facilities');
        $id_mosque = $request->input('id_mosque');
        $id_facilities = $request->input('id_facilities');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $expiresAt = Carbon::now()->addDays();  
        
        $data = MosqueFacilities::query()
        ->when($request->has('id_mosque_facilities'), function ($query) use ($id_mosque_facilities) {
            $query->where('mosque_facilities.id_mosque_facilities',$id_mosque_facilities);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque_facilities.id_mosque',$id_mosque);
        })
        ->when($request->has('id_facilities'), function ($query) use ($id_facilities) {
            $query->where('mosque_facilities.id_facilities',$id_facilities);
        })
        ->where('mosque_facilities.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque_facilities.id_mosque_facilities '.$order)
        ->distinct('mosque_facilities.id_mosque_facilities')
        ->get([
            'mosque_facilities.id_mosque_facilities',
            'mosque_facilities.id_mosque',
            'mosque_facilities.id_facilities',
            'mosque_facilities.soft_delete',
            'mosque_facilities.created_at',
            'mosque_facilities.updated_at'
        ]);

        foreach($data as $index => $d){
            $mosque = Mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();
            $facilities = Facilities::where('id_facilities', $d->id_facilities)
                ->where('soft_delete', 0)
                ->get();
            foreach ($facilities as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->icon_facilities);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $facilities[$key]->icon_facilities_url_firebase = $image;
                }else{
                    $facilities[$key]->icon_facilities_url_firebase = null;
                }
            }

            $data[$index]->mosque = $mosque;
            $data[$index]->facilities = $facilities;
        }

        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $data;

            return response($res, 200);
        }
    }
}
