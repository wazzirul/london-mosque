<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
     *     summary="Menambah data facilities response",
     *     description="Menambah data facilities response",
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
     *     description="Icon Facilities ...",
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
        $icon_facilities = $request->input('icon_facilities');

        $data = Facilities::create([
            'name_facilities' => $name_facilities,
            'icon_facilities' => $icon_facilities,
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
     *   path="/facilities/update",
     *     tags={"Facilities"},
     *     summary="Mengedit data facilities response",
     *     description="Mengedit data facilities response",
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
     *     description="Name Facilities (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="icon_facilities",
     *     in="formData",
     *     description="Icon Facilities (Kosongi jika tidak ingin merubah data)...",
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
            $res['detail'] = "Data Facility for that ID not found.";

            return response($res, 200);
        }

        $name_facilities = $request->input('name_facilities') ?? null;
        $icon_facilities = $request->input('icon_facilities') ?? null;

        if($name_facilities !== null){
            $facility->name_facilities = $name_facilities;
        }

        if($icon_facilities !== null){
            $facility->icon_facilities = $icon_facilities;
        }

        $data = $facility->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
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
     *     summary="Menghapus data facilities response",
     *     description="Menghapus data facilities response",
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
     *     summary="Mengembalikan data terhapus data facilities response",
     *     description="Mengembalikan data terhapus data facilities response",
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
        $res['data'] = $facility;

        return response($res, 200);
    }
}
