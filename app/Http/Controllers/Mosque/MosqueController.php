<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Borough;
use App\Model\Districts;

use App\Model\Mosque\Mosque;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

class MosqueController extends Controller
{
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/store",
     *     tags={"Mosque"},
     *     summary="Menambah data Mosque response",
     *     description="Menambah data Mosque response",
     *     operationId="storeMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_google_place",
     *     in="formData",
     *     description="ID Mosque Google Place (Google Place / Maps ID) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="name_mosque",
     *     in="formData",
     *     description="Name Mosque ...",
     *     required=true,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="Address Mosque ...",
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
            'id_mosque_google_place'=>'nullable',
            'id_borough'=>'required|numeric',
            'id_district'=>'required|numeric',
            'name_mosque'=>'required',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $id_mosque_google_place = $request->input('id_mosque_google_place') ?? NULL;
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
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

        $data = Mosque::create([
            'id_mosque_google_place' => $id_mosque_google_place,
            'id_borough' => $id_borough,
            'id_district' => $id_district,
            'name_mosque' => $name_mosque,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }






    
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE UPDATE UPDATE UPDATE UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/update",
     *     tags={"Mosque"},
     *     summary="Mengupdate data Mosque response",
     *     description="Mengupdate data Mosque response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_mosque_google_place",
     *     in="formData",
     *     description="ID Mosque Google Place (Google Place / Maps ID) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="name_mosque",
     *     in="formData",
     *     description="Name Mosque ...",
     *     required=false,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="Address Mosque ...",
     *     required=false,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
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
            'id_mosque'=>'required|numeric',
            'id_mosque_google_place'=>'nullable',
            'id_borough'=>'numeric',
            'id_district'=>'numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $id_mosque_google_place = $request->input('id_mosque_google_place') ?? NULL;
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $data = Mosque::find($id_mosque);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
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
            $data->id_borough = $id_borough;
        }

        if($id_district !== null){
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
            $data->id_district = $id_district;
        }

        if($id_mosque_google_place !== null){
            $data->id_mosque_google_place = $id_mosque_google_place;
        }

        if($name_mosque !== null){
            $data->name_mosque = $name_mosque;
        }

        if($address !== null){
            $data->address = $address;
        }

        if($latitude !== null){
            $data->latitude = $latitude;
        }

        if($longitude !== null){
            $data->longitude = $longitude;
        }

        $data = $data->save();
        $data = Mosque::with('Borough','Districts')->find($id_mosque);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/delete",
     *     tags={"Mosque"},
     *     summary="Menghapus data Mosque response",
     *     description="Menghapus data Mosque response",
     *     operationId="deleteMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
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
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $mosque->soft_delete = 1;

        $mosque = $mosque->save();
        $data = Mosque::find($id_mosque);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/undelete",
     *     tags={"Mosque"},
     *     summary="Mengembalikan data Mosque yang terhapus response",
     *     description="Mengembalikan data Mosque yang terhapus response",
     *     operationId="undeleteMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
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
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $mosque->soft_delete = 0;   

        $mosque = $mosque->save();
        $data = Mosque::find($id_mosque);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success restoring Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque/show",
     *     tags={"Mosque"},
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
        $mosque = Mosque::where('soft_delete', 0)
        ->orderBy('id_mosque', 'DESC')
        ->get();

        
        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();

            $mosque[$index]->borough = $borough;
            $mosque[$index]->district = $district;
        }


        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $mosque;
            $res['message'] = 'Success return Mosque data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/showById",
     *     tags={"Mosque"},
     *     summary="Show all data mosque response",
     *     description="Show all data mosque response",
     *     operationId="showByIdMosques",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque",
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
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        
        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $mosque = Mosque::where('id_mosque', $id_mosque)
        ->get();

        
        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();

            $mosque[$index]->borough = $borough;
            $mosque[$index]->district = $district;
        }


        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['data'] = $mosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/showByFilter",
     *     tags={"Mosque"},
     *     summary="Show all data mosque response",
     *     description="Show all data mosque response",
     *     operationId="showByFilterMosques",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="name_mosque",
     *     in="formData",
     *     description="Name Mosque (%like) ...",
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
     *     description="Skip Berapa Data (Default=0)",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="take",
     *     in="formData",
     *     description="Take Berapa Data (Default=100)",
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
        
        $id_mosque = $request->input('id_mosque');
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $mosque = Mosque::query()
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque.id_mosque',$id_mosque);
        })
        ->when($request->has('id_borough'), function ($query) use ($id_borough) {
            $query->where('mosque.id_borough',$id_borough);
        })
        ->when($request->has('id_district'), function ($query) use ($id_district) {
            $query->where('mosque.id_district',$id_district);
        })
        ->when($request->has('name_mosque'), function ($query) use ($name_mosque) {
            $query->where('mosque.name_mosque','like','%'.$name_mosque.'%');
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('mosque.soft_delete',$soft_delete);
        })
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque.id_mosque '.$order)
        ->distinct('mosque.id_mosque')
        ->get([
            'mosque.id_mosque',
            'mosque.id_mosque_google_place',
            'mosque.id_borough',
            'mosque.id_district',
            'mosque.name_mosque',
            'mosque.address',
            'mosque.latitude',
            'mosque.longitude',
            'mosque.soft_delete',
            'mosque.created_at as created_at',
            'mosque.updated_at as updated_at'
        ]);

        
        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();

            $mosque[$index]->borough = $borough;
            $mosque[$index]->district = $district;
        }


        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['data'] = $mosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }
}
