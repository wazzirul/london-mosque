<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\SuggestMosque\SuggestMosque;
use App\Model\Users;
use App\Model\Mosque;
use App\Model\Borough;
use App\Model\Districts;

class SuggestMosqueController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/store",
     *     tags={"Suggest Mosque"},
     *     summary="Menambah data suggest mosque response",
     *     description="Menambah data suggest mosque response",
     *     operationId="storeSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
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
     *     description="Latitude Mosque ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude Mosque ...",
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
            'id_user'=>'required|numeric',
            'id_mosque'=>'required|numeric',
            'id_borough'=>'required|numeric',
            'id_district'=>'required|numeric',
            'name_mosque'=>'required',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $id_user = $request->input('id_user');
        $id_mosque = $request->input('id_mosque');
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $data = SuggestMosque::create([
            'id_user' => $id_user,
            'id_mosque' => $id_mosque,
            'id_borough' => $id_borough,
            'id_district' => $id_district,
            'name_mosque' => $name_mosque,
            'address' => $address,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status_suggest_mosque' => 0
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
     *   path="/suggest-mosque/update",
     *     tags={"Suggest Mosque"},
     *     summary="Mengedit data suggest mosque response",
     *     description="Mengedit data suggest mosque response",
     *     operationId="updateSuggestMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="name_mosque",
     *     in="formData",
     *     description="Name Mosque (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="Address Mosque (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Latitude Mosque (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude Mosque (Kosongi jika tidak ingin merubah data)...",
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
            'id_suggest_mosque'=>'required|numeric',
            'id_user'=>'nullable|numeric',
            'id_mosque'=>'nullable|numeric',
            'id_borough'=>'nullable|numeric',
            'id_district'=>'nullable|numeric',
            'name_mosque'=>'nullable',
            'address'=>'nullable',
            'latitude'=>'nullable',
            'longitude'=>'nullable',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $suggestMosque = SuggestMosque::where('id_suggest_mosque', $id_suggest_mosque)
            ->first();

        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $id_user = $request->input('id_user') ?? null;
        $id_mosque = $request->input('id_mosque') ?? null;
        $id_borough = $request->input('id_borough') ?? null;
        $id_district = $request->input('id_district') ?? null;
        $name_mosque = $request->input('name_mosque') ?? null;
        $address = $request->input('address') ?? null;
        $latitude = $request->input('latitude') ?? null;
        $longitude = $request->input('longitude') ?? null;

        if($id_user !== null){
            $user = Users::find($id_user);
            if($user === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Users for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosque->id_user = $id_user;
        }

        if($id_mosque !== null){
            $mosque = Mosque::find($id_mosque);
            if($mosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Mosque for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosque->id_mosque = $id_mosque;
        }

        if($id_borough !== null){
            $borough = Borough::find($id_borough);
            if($borough === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Borough for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosque->id_borough = $id_borough;
        }

        if($id_district !== null){
            $districts = Districts::find($id_district);
            if($districts === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Districts for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosque->id_district = $id_district;
        }

        if($name_mosque !== null){
            $suggestMosque->name_mosque = $name_mosque;
        }

        if($address !== null){
            $suggestMosque->address = $address;
        }

        if($latitude !== null){
            $suggestMosque->latitude = $latitude;
        }

        if($longitude !== null){
            $suggestMosque->longitude = $longitude;
        }

        $data = $suggestMosque->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/delete",
     *     tags={"Suggest Mosque"},
     *     summary="Menghapus data suggest mosque response",
     *     description="Menghapus data suggest mosque response",
     *     operationId="deleteSuggestMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
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
            'id_suggest_mosque'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $suggestMosque = SuggestMosque::where('id_suggest_mosque', $id_suggest_mosque)
            ->first();

        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $suggestMosque->soft_delete = 1;

        $data = $suggestMosque->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/unDelete",
     *     tags={"Suggest Mosque"},
     *     summary="Mengembalikan data terhapus data suggest mosque response",
     *     description="Mengembalikan data terhapus data suggest mosque response",
     *     operationId="unDeleteSuggestMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
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
            'id_suggest_mosque'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $suggestMosque = SuggestMosque::where('id_suggest_mosque', $id_suggest_mosque)
            ->first();

        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }else if($suggestMosque->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque is active.";

            return response($res, 200);
        }

        $suggestMosque->soft_delete = 0;

        $data = $suggestMosque->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/suggest-mosque/show",
     *     tags={"Suggest Mosque"},
     *     summary="Show all data suggest mosque response",
     *     description="Show all data suggest mosque response",
     *     operationId="showSuggestMosque",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $suggestMosque = SuggestMosque::where('soft_delete', 0)
            ->orderBy('id_suggest_mosque', 'DESC')
            ->get();

        if(count($suggestMosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/showById",
     *     tags={"Suggest Mosque"},
     *     summary="Show data suggest mosque by ID suggest mosque response",
     *     description="Show data suggest mosque by ID suggest mosque response",
     *     operationId="showByIdSuggestMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
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
            'id_suggest_mosque'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $suggestMosque = SuggestMosque::where('id_suggest_mosque', $id_suggest_mosque)
            ->with(['User', 'Mosque', 'Borough', 'District'])
            ->first();

        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }else if($suggestMosque->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Suggest Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $suggestMosque;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/showByFilter",
     *     tags={"Suggest Mosque"},
     *     summary="Show data by filter suggest mosque response",
     *     description="Show data by filter suggest mosque response",
     *     operationId="showByFilterSuggestMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="status_suggest_mosque",
     *     in="formData",
     *     description="Status Suggest Mosque (0 = Not yet approved / declined, 1 = approved, 2 = declined) ...",
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
     *     name="address",
     *     in="formData",
     *     description="Address Mosque (%like) ...",
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
     *   @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude ...",
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
        $status_suggest_mosque = $request->input('status_suggest_mosque');
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $suggestMosque = SuggestMosque::query()
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('suggest_mosque.id_mosque',$id_mosque);
        })
        ->when($request->has('status_suggest_mosque'), function ($query) use ($status_suggest_mosque) {
            $query->where('suggest_mosque.status_suggest_mosque',$status_suggest_mosque);
        })
        ->when($request->has('id_borough'), function ($query) use ($id_borough) {
            $query->where('suggest_mosque.id_borough',$id_borough);
        })
        ->when($request->has('id_district'), function ($query) use ($id_district) {
            $query->where('suggest_mosque.id_district',$id_district);
        })
        ->when($request->has('name_mosque'), function ($query) use ($name_mosque) {
            $query->where('suggest_mosque.name_mosque','like','%'.$name_mosque.'%');
        })
        ->when($request->has('address'), function ($query) use ($address) {
            $query->where('suggest_mosque.address','like','%'.$address.'%');
        })
        ->when($request->has('latitude'), function ($query) use ($latitude) {
            $query->where('suggest_mosque.latitude',$latitude);
        })
        ->when($request->has('longitude'), function ($query) use ($longitude) {
            $query->where('suggest_mosque.longitude',$longitude);
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('suggest_mosque.soft_delete',$soft_delete);
        })
        ->skip($skip)
        ->take($take)
        ->with(['Mosque', 'User'])
        ->orderByRaw('suggest_mosque.id_suggest_mosque '.$order)
        ->get([
            'suggest_mosque.id_suggest_mosque',
            'suggest_mosque.id_user',
            'suggest_mosque.status_suggest_mosque',
            'suggest_mosque.id_mosque',
            'suggest_mosque.id_borough',
            'suggest_mosque.id_district',
            'suggest_mosque.name_mosque',
            'suggest_mosque.address',
            'suggest_mosque.latitude',
            'suggest_mosque.longitude',
            'suggest_mosque.soft_delete',
            'suggest_mosque.created_at as created_at',
            'suggest_mosque.updated_at as updated_at'
        ]);
        

        foreach($suggestMosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();

            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
                
            $facilities = SuggestMosqueFacilities::with('Facilities')
                ->where('id_suggest_mosque', $d->id_suggest_mosque)
                ->get();

            $suggestMosque[$index]->borough = $borough;
            $suggestMosque[$index]->district = $district;
            // $suggestMosque[$index]->salah_times = $salah_times;
            $suggestMosque[$index]->facilities = $facilities;
        }


        if(count($suggestMosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Suggest Mosque data';
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosque;

            return response($res, 200);
        }
    }
}
