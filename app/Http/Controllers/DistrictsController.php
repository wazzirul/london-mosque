<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\Borough;
use App\Model\Districts;

class DistrictsController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/districts/store",
     *     tags={"Districts"},
     *     summary="Storing new data districts response",
     *     description="Storing new data districts response",
     *     operationId="storeDistricts",
     * 
     *  @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="name_district",
     *     in="formData",
     *     description="Name District ...",
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
            'id_borough'=>'required|numeric',
            'name_district'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $id_borough = $request->input('id_borough');
        $name_district = $request->input('name_district');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

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

        $data = Districts::create([
            'id_borough' => $id_borough,
            'name_district' => $name_district,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Storing New Districts Data.";
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/districts/update",
     *     tags={"Districts"},
     *     summary="Updating data districts response",
     *     description="Updating data districts response
           <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateDistricts",
     * 
     *   @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="name_district",
     *     in="formData",
     *     description="Name District ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Latitude ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude ...",
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
            'id_district'=>'required',
            'id_borough'=>'nullable',
            'name_district'=>'nullable',
            'latitude'=>'nullable',
            'longitude'=>'nullable',
        ]);  

        $id_district = $request->input('id_district');
        $district = Districts::where('id_district', $id_district)
            ->first();

        if($district === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }

        $id_borough = $request->input('id_borough') ?? null;
        $name_district = $request->input('name_district') ?? null;
        $latitude = $request->input('latitude') ?? null;
        $longitude = $request->input('longitude') ?? null;

        if($id_borough !== null){
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

            $district->id_borough = $id_borough;
        }

        if($name_district !== null){
            $district->name_district = $name_district;
        }

        if($latitude !== null){
            $district->latitude = $latitude;
        }

        if($longitude !== null){
            $district->longitude = $longitude;
        }

        $data = $district->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Updating Districts Data.";
            $res['data'] = $district;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/districts/delete",
     *     tags={"Districts"},
     *     summary="Deleting data districts response",
     *     description="Deleting data districts response",
     *     operationId="deleteDistricts",
     * 
     *   @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
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
            'id_district'=>'required|numeric',
        ]);  

        $id_district = $request->input('id_district');
        $district = Districts::where('id_district', $id_district)
            ->first();

        if($district === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data District for that ID not found.";

            return response($res, 200);
        }

        $district->soft_delete = 1;

        $data = $district->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Deleting Districts Data.";
            $res['data'] = $district;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/districts/unDelete",
     *     tags={"Districts"},
     *     summary="Restoring data in deleted section districts response",
     *     description="Restoring data in deleted section districts response",
     *     operationId="unDeleteDistricts",
     * 
     *   @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
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
            'id_district'=>'required|numeric',
        ]);  

        $id_district = $request->input('id_district');
        $district = Districts::where('id_district', $id_district)
            ->first();

        if($district === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data District for that ID not found.";

            return response($res, 200);
        }else if($district->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data District is active.";

            return response($res, 200);
        }

        $district->soft_delete = 0;

        $data = $district->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Restoring / Undelete Districts Data.";
            $res['data'] = $district;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/districts/show",
     *     tags={"Districts"},
     *     summary="Show all data districts response",
     *     description="Show all data districts response",
     *     operationId="showDistricts",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $district = Districts::where('soft_delete', 0)
            ->orderBy('id_district', 'DESC')
            ->get();

        foreach($district as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->first();

            $district[$index]->borough = $borough;
        }

        if(count($district) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $district;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Success Returning Districts Data.";
            $res['data'] = $district;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/districts/showById",
     *     tags={"Districts"},
     *     summary="Show data district by ID district response",
     *     description="Show data district by ID district response",
     *     operationId="showByIdDistricts",
     * 
     *   @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
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
            'id_district'=>'required|numeric',
        ]);  

        $id_district = $request->input('id_district');
        $district = Districts::where('id_district', $id_district)
            ->first();

        if($district === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data District for that ID not found.";

            return response($res, 200);
        }else if($district->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "District was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $borough = Borough::where('id_borough', $district->id_borough)
            ->first();

        $district->borough = $borough;

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Success Returning Districts Data.";
        $res['data'] = $district;

        return response($res, 200);
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID Borough
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/districts/showByIdBorough",
     *     tags={"Districts"},
     *     summary="Show data district by ID district response",
     *     description="Show data district by ID district response",
     *     operationId="showByIdBoroughDistricts",
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
    public function showByIdBorough(Request $request){
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

        $district = Districts::where('id_borough', $id_borough)
            ->where('soft_delete', 0)
            ->get();

        $borough->districts = $district;

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Success Returning Districts Data.";
        $res['data'] = $borough;

        return response($res, 200);
    }
}
