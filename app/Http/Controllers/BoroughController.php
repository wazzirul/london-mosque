<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\Borough;
use App\Model\Districts;

class BoroughController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/store",
     *     tags={"Borough"},
     *     summary="Menambah data borough response",
     *     description="Menambah data borough response",
     *     operationId="storeBorough",
     * 
     *  @SWG\Parameter(
     *     name="name_borough",
     *     in="formData",
     *     description="Name Borough ...",
     *     required=true,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Latitude ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude ...",
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
            'name_borough'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $name_borough = $request->input('name_borough');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $data = Borough::create([
            'name_borough' => $name_borough,
            'latitude' => $latitude,
            'longitude' => $longitude,
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
     *   path="/borough/update",
     *     tags={"Borough"},
     *     summary="Mengedit data borough response",
     *     description="Mengedit data borough response",
     *     operationId="updateBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="name_borough",
     *     in="formData",
     *     description="Name Borough (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Latitude (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude (Kosongi jika tidak ingin merubah data)...",
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
            'id_borough'=>'required',
            'latitude'=>'nullable',
            'longitude'=>'nullable',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }

        $name_borough = $request->input('name_borough') ?? null;
        $latitude = $request->input('latitude') ?? null;
        $longitude = $request->input('longitude') ?? null;

        if($name_borough !== null){
            $borough->name_borough = $name_borough;
        }

        if($latitude !== null){
            $borough->latitude = $latitude;
        }

        if($longitude !== null){
            $borough->longitude = $longitude;
        }

        $data = $borough->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $borough;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/delete",
     *     tags={"Borough"},
     *     summary="Menghapus data borough response",
     *     description="Menghapus data borough response",
     *     operationId="deleteBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Faclities ...",
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
            'id_borough'=>'required|numeric',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }

        $borough->soft_delete = 1;

        $data = $borough->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $borough;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/borough/show",
     *     tags={"Borough"},
     *     summary="Show all data borough response",
     *     description="Show all data borough response",
     *     operationId="showBorough",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $borough = Borough::where('soft_delete', 0)
            ->orderBy('id_borough', 'DESC')
            ->get();

        if(count($borough) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $borough;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $borough;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/showById",
     *     tags={"Borough"},
     *     summary="Show data borough by ID borough response",
     *     description="Show data borough by ID borough response",
     *     operationId="showByIdBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
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
            'id_borough'=>'required|numeric',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }else if($borough->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Borough was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $districts = Districts::where('id_borough', $id_borough)
            ->where('soft_delete', 0)
            ->get();

        $borough->districts = $districts;

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $borough;

        return response($res, 200);
    }
}
