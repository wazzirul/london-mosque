<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Model\Facilities;

class FacilitiesController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/facilities/store",
     *     tags={"Facilities"},
     *     summary="Storing New data facilities response",
     *     description="Storing New data facilities response",
     *     operationId="storeFacilities",
     * 
     *  @SWG\Parameter(
     *     name="name_facilities",
     *     in="formData",
     *     description="Name Facilities ...",
     *     required=true,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="icon_facilities",
     *     in="formData",
     *     description="Icon Facilities (base64) ...",
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
            'name_facilities'=>'required',
            'icon_facilities'=>'required',
        ]);  

        $name_facilities = $request->input('name_facilities');

        $image = $request->icon_facilities; //base64 string from frontend  
        $cloudinary = cloudinary()->uploadFile($image, [
            "folder" => "londonmosques-svg/",
            "format" => "svg",
        ]);

        $data = Facilities::create([
            'name_facilities' => $name_facilities,
            'public_id' => $cloudinary->getPublicId(),
            'icon_facilities' => $cloudinary->getSecurePath(),
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Storing New Facilities Data.";
            $res['data'] = $data;

            return response($res, 200);
        } else {  
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Error upload file to cloud server.";

            return response($res, 200);
        }  
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/facilities/update",
     *     tags={"Facilities"},
     *     summary="Updating data facilities response",
     *     description="Updating data facilities response
           <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="name_facilities",
     *     in="formData",
     *     description="Name Facilities ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="icon_facilities",
     *     in="formData",
     *     description="Icon Facilities (base64) ...",
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
            'id_facilities'=>'required',
            'name_facilities'=>'nullable',
            'icon_facilities'=>'nullable',
        ]);  

        $id_facilities = $request->input('id_facilities');
        $facility = Facilities::where('id_facilities', $id_facilities)
            ->first();

        if($facility === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facilities for that ID not found.";

            return response($res, 200);
        }

        $name_facilities = $request->input('name_facilities') ?? null;
        $icon_facilities = $request->input('icon_facilities') ?? null;

        if($name_facilities !== null){
            $facility->name_facilities = $name_facilities;
        }

        if($icon_facilities !== null){
            $image = $request->icon_facilities; //base64 string from frontend  
            cloudinary()->destroy($facility->public_id);

            $cloudinary = cloudinary()->upload($image, [
                "folder" => "londonmosques-svg/",
            ]);

            $facility->public_id = $cloudinary->getPublicId();
            $facility->icon_facilities = $cloudinary->getSecurePath();
        }

        $data = $facility->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Updating Facilities Data.";
            $res['data'] = $facility;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/facilities/delete",
     *     tags={"Facilities"},
     *     summary="Deleting data facilities response",
     *     description="Deleting data facilities response",
     *     operationId="deleteFacilities",
     * 
     *   @SWG\Parameter(
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
    public function delete(Request $request){
        $this->validate($request,[
            'id_facilities'=>'required|numeric',
        ]);  

        $id_facilities = $request->input('id_facilities');
        $facility = Facilities::where('id_facilities', $id_facilities)
            ->first();

        if($facility === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facility for that ID not found.";

            return response($res, 200);
        }

        $facility->soft_delete = 1;

        $data = $facility->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Deleting Facilities Data.";
            $res['data'] = $facility;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/facilities/unDelete",
     *     tags={"Facilities"},
     *     summary="Restoring data in deleted sections facilities response",
     *     description="Restoring data in deleted sections facilities response",
     *     operationId="unDeleteFacilities",
     * 
     *   @SWG\Parameter(
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
    public function unDelete(Request $request){
        $this->validate($request,[
            'id_facilities'=>'required|numeric',
        ]);  

        $id_facilities = $request->input('id_facilities');
        $facility = Facilities::where('id_facilities', $id_facilities)
            ->first();

        if($facility === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facility for that ID not found.";

            return response($res, 200);
        }else if($facility->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facility is active.";

            return response($res, 200);
        }

        $facility->soft_delete = 0;

        $data = $facility->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Restoring Facilities Data.";
            $res['data'] = $facility;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/facilities/show",
     *     tags={"Facilities"},
     *     summary="Show all data facilities response",
     *     description="Show all data facilities response",
     *     operationId="showFacilities",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $facility = Facilities::where('soft_delete', 0)
            ->orderBy('id_facilities', 'DESC')
            ->get();

        if(count($facility) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Returning Facilities Data.";
            $res['totaldata'] = $facility->count();
            $res['data'] = $facility;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $facility;

            return response($res, 200);
        }
    }


     //////////////////////////////////////////////////////////////////////////////////////
    // SHOW DELETED
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/facilities/showDeleted",
     *     tags={"Facilities"},
     *     summary="Show Deleted all data facilities response",
     *     description="Show Deleted all data facilities response",
     *     operationId="showDeletedFacilities",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showDeleted(){
        $facility = Facilities::where('soft_delete', 1)
            ->orderBy('id_facilities', 'DESC')
            ->get();

        if(count($facility) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Returning Facilities Data.";
            $res['totaldata'] = $facility->count();
            $res['data'] = $facility;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $facility;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/facilities/showById",
     *     tags={"Facilities"},
     *     summary="Show data facilities by ID facilities response",
     *     description="Show data facilities by ID facilities response",
     *     operationId="showByIdFacilities",
     * 
     *   @SWG\Parameter(
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
    public function showById(Request $request){
        $this->validate($request,[
            'id_facilities'=>'required|numeric',
        ]);  

        $id_facilities = $request->input('id_facilities');
        $facility = Facilities::where('id_facilities', $id_facilities)
            ->first();

        if($facility === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facility for that ID not found.";

            return response($res, 200);
        }else if($facility->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Facility was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Success Returning Facilities Data.";
        $res['totaldata'] = $facility->count();
        $res['data'] = $facility;

        return response($res, 200);
    }
    



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/facilities/showByFilter",
     *     tags={"Facilities"},
     *     summary="Show data facilities by Filter facilities response",
     *     description="Show data facilities by Filter facilities response",
     *     operationId="showByFilterFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="name_facilities",
     *     in="formData",
     *     description="Name Facilities ...",
     *     required=false,
     *     type="string"
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
        $id_facilities = $request->input('id_facilities');
        $name_facilities = $request->input('name_facilities');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $facility = Facilities::query()
        ->when($request->has('id_facilities'), function ($query) use ($id_facilities) {
            $query->where('facilities.id_facilities',$id_facilities);
        })
        ->when($request->has('name_facilities'), function ($query) use ($name_facilities) {
            $query->where('facilities.name_facilities','like','%'.$name_facilities.'%');
        })
        ->where('facilities.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('facilities.id_facilities '.$order)
        ->distinct('facilities.id_facilities')
        ->get([
            'facilities.id_facilities',
            'facilities.name_facilities',
            'facilities.public_id',
            'facilities.icon_facilities',
            'facilities.soft_delete',
            'facilities.created_at as created_at',
            'facilities.updated_at as updated_at'
        ]);

        
        if(count($facility) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Facilities data';
            $res['totaldata'] = $facility->count();
            $res['data'] = $facility;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $facility;

            return response($res, 200);
        }
    }
}
