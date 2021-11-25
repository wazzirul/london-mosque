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
use App\Model\SuggestMosque\SuggestMosqueSpecialSalahTimes;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueFacilities;
use App\Model\Mosque\MosqueImage;
use App\Model\Mosque\MosqueSalahTime;
use App\Model\Mosque\MosqueSpecialSalahTime;

use App\Model\Users;
use App\Model\Admin;
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
     *     required=false,
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
            'id_district'=>'nullable|numeric',
            'name_mosque'=>'required',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $id_user = $request->input('id_user');
        $id_mosque = $request->input('id_mosque');
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district') ?? null;
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $user = Users::find($id_user);
        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users for that ID not found.";

            return response($res, 200);
        }

        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $borough = Borough::find($id_borough);
        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }

        $data = SuggestMosque::create([
            'id_user' => $id_user,
            'id_mosque' => $id_mosque,
            'id_borough' => $id_borough,
            // 'id_district' => $id_district,
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

        // if($id_district !== null){
        //     $districts = Districts::find($id_district);
        //     if($districts === null){
        //         $res['status_code'] = 406;
        //         $res['success'] = false;
        //         $res['detail'] = "Data Districts for that ID not found.";

        //         return response($res, 200);
        //     }
            
        //     $suggestMosque->id_district = $id_district;
        // }

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
            ->with(['Mosque', 'User'])
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
            ->with(['User', 'Mosque', 'Borough'])
            ->first();
        
        $suggestFacilities = SuggestMosqueFacilities::where('id_suggest_mosque', $suggestMosque->id_suggest_mosque)
            ->with('Facilities')
            ->get();

        $suggestSalahTimes = SuggestMosqueSalahTimes::where('id_suggest_mosque', $suggestMosque->id_suggest_mosque)
            ->get();

        $suggestSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::where('id_suggest_mosque', $suggestMosque->id_suggest_mosque)
            ->get();

        $suggestImage = SuggestMosqueImage::where('id_suggest_mosque', $suggestMosque->id_suggest_mosque)
            ->get();

        $expiresAt = Carbon::now()->addDays();  
        foreach ($suggestFacilities as $key => $sfa) {
            $imageReference = app('firebase.storage')->getBucket()->object($sfa->facilities->icon_facilities);  
            if($imageReference->exists()){
                $icon = $imageReference->signedUrl($expiresAt);
                $suggestFacilities[$key]['facilities']->icon_facilities_firebase = $icon;
            }else{
                $suggestFacilities[$key]['facilities']->icon_facilities_firebase = null;
            }
        }
        foreach ($suggestImage as $key => $sim) {
            $imageReference = app('firebase.storage')->getBucket()->object($sim->mosque_image_url);  
            if($imageReference->exists()){
                $image = $imageReference->signedUrl($expiresAt);
                $suggestImage[$key]->mosque_image_url_firebase = $image;
            }else{
                $suggestImage[$key]->mosque_image_url_firebase = null;
            }
        }

        $suggestMosque['suggest_mosque_facilities'] = $suggestFacilities;
        $suggestMosque['suggest_mosque_salah_times'] = $suggestSalahTimes;
        $suggestMosque['suggest_mosque_special_salah_times'] = $suggestSpecialSalahTimes;
        $suggestMosque['suggest_mosque_image'] = $suggestImage;

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
        $id_district = $request->input('id_district') ?? null;
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
        // ->when($request->has('id_district'), function ($query) use ($id_district) {
        //     $query->where('suggest_mosque.id_district',$id_district);
        // })
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
        ->with(['Mosque', 'User', 'Borough'])
        ->orderByRaw('suggest_mosque.id_suggest_mosque '.$order)
        ->get([
            'suggest_mosque.id_suggest_mosque',
            'suggest_mosque.id_user',
            'suggest_mosque.status_suggest_mosque',
            'suggest_mosque.id_mosque',
            'suggest_mosque.id_borough',
            // 'suggest_mosque.id_district',
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

            // $district = Districts::where('id_district', $d->id_district)
            //     ->where('soft_delete', 0)
            //     ->first();
                
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





    //////////////////////////////////////////////////////////////////////////////////////
    // Approve Function
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/approve",
     *     tags={"Suggest Mosque"},
     *     summary="Approve suggest mosque response",
     *     description="Approve suggest mosque response",
     *     operationId="approveSuggestMosque",
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
    public function approve(Request $request){
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
            ->where('soft_delete', 0)
            ->get();

        $suggestImage = SuggestMosqueImage::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('soft_delete', 0)
            ->get();

        $suggestSalahTimes = SuggestMosqueSalahTimes::where('id_suggest_mosque', $id_suggest_mosque)
            ->where('soft_delete', 0)
            ->get();

        // $suggestSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::where('id_suggest_mosque', $id_suggest_mosque)
        //     ->where('soft_delete', 0)
        //     ->get();


        // Delete all image on firebase bucket
        $mosqueImage = MosqueImage::where('id_mosque', $id_mosque)
            ->get();

        foreach ($mosqueImage as $key => $value) {
            $imageDeleted = app('firebase.storage')->getBucket()->object($value->mosque_image_url);
            if($imageDeleted->exists()) $imageDeleted->delete();
        }


        // Change Data Mosque
        $mosque->id_borough = $suggestMosque->id_borough;
        // $mosque->id_district = $suggestMosque->id_district;
        $mosque->name_mosque = $suggestMosque->name_mosque;
        $mosque->address = $suggestMosque->address;
        $mosque->latitude = $suggestMosque->latitude;
        $mosque->longitude = $suggestMosque->longitude;


        MosqueFacilities::where('id_mosque', $id_mosque)
            ->forceDelete();

        MosqueImage::where('id_mosque', $id_mosque)
            ->forceDelete();

        foreach ($suggestFacilities as $key => $value) {
            $newMosqueFacilities = new MosqueFacilities();
            $newMosqueFacilities->id_mosque = $id_mosque;
            $newMosqueFacilities->id_facilities = $value->id_facilities;
            $newMosqueFacilities->save();
        }

        foreach ($suggestImage as $key => $value) {
            $newMosqueImage = new MosqueImage();
            $newMosqueImage->id_mosque = $id_mosque;
            $newMosqueImage->mosque_image_url = $value->mosque_image_url;
            $newMosqueImage->save();
        }

        foreach ($suggestSalahTimes as $key => $value) {
            MosqueSalahTime::where('id_mosque', $id_mosque)
                ->where('date_salah_times', $value->date_salah_times)
                ->forceDelete();
        }
        foreach ($suggestSalahTimes as $key => $value) {
            $newMosqueSalahTime = new MosqueSalahTime();
            $newMosqueSalahTime->id_mosque = $id_mosque;
            $newMosqueSalahTime->date_salah_times = $value->date_salah_times;
            $newMosqueSalahTime->type_salah_times = $value->type_salah_times;
            $newMosqueSalahTime->time_salah_times = $value->time_salah_times;
            $newMosqueSalahTime->save();
        }

        // foreach ($suggestSpecialSalahTimes as $key => $value) {
        //     $newMosqueSpecialSalahTime = new MosqueSpecialSalahTime();
        //     $newMosqueSpecialSalahTime->id_mosque = $id_mosque;
        //     $newMosqueSpecialSalahTime->category_special_salah = $value->category_special_salah;
        //     $newMosqueSpecialSalahTime->date_special_salah_times = $value->date_special_salah_times;
        //     $newMosqueSpecialSalahTime->order_special_salah_times = $value->order_special_salah_times;
        //     $newMosqueSpecialSalahTime->time_special_salah_times = $value->time_special_salah_times;
        //     $newMosqueSpecialSalahTime->save();
        // }

        $date_log = Carbon::now()->format('Y-m-d');
        
        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->id_suggest_mosque = $id_suggest_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 1;
        $logSuggestMosque->save();

        $suggestMosque->status_suggest_mosque = 1;
        $suggestMosque->save();
        $mosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Suggest Mosque Approved.";

        return response($res, 200);
    }





    //////////////////////////////////////////////////////////////////////////////////////
    // Decline Function
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/decline",
     *     tags={"Suggest Mosque"},
     *     summary="Decline suggest mosque response",
     *     description="Decline suggest mosque response",
     *     operationId="declineSuggestMosque",
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
    public function decline(Request $request){
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

        $date_log = Carbon::now()->format('Y-m-d');

        $logSuggestMosque = new SuggestMosqueLog();
        $logSuggestMosque->id_suggest_mosque = $id_suggest_mosque;
        $logSuggestMosque->id_admin = $id_admin;
        $logSuggestMosque->date_log = $date_log;
        $logSuggestMosque->action = 2;
        $logSuggestMosque->save();

        $suggestMosque->status_suggest_mosque = 2;
        $suggestMosque->save();

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Suggest Mosque Declined.";

        return response($res, 200);
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // Show Log
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/log",
     *     tags={"Suggest Mosque"},
     *     summary="Show log suggest mosque response",
     *     description="Show log suggest mosque response",
     *     operationId="logSuggestMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="action",
     *     in="formData",
     *     description="Action Suggest Mosque (0: Not yet declined / approved, 1: Approved, 2: Declined) ",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="id_admin",
     *     in="formData",
     *     description="ID Admin",
     *     required=false,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function log(Request $request){
        $id_suggest_mosque = $request->input('id_suggest_mosque') ?? null;
        $action = $request->input('action') ?? null;
        $id_admin = $request->input('id_admin') ?? null;

        $suggestMosqueLog = SuggestMosqueLog::query()
            ->when($request->has('id_suggest_mosque'), function ($query) use ($id_suggest_mosque) {
                $query->where('suggest_mosque_log.id_suggest_mosque',$id_suggest_mosque);
            })
            ->when($request->has('id_admin'), function ($query) use ($id_admin) {
                $query->where('suggest_mosque_log.id_admin',$id_admin);
            })
            ->when($request->has('action'), function ($query) use ($action) {
                $query->where('suggest_mosque_log.action',$action);
            })
            ->with('Admin')
            ->orderByRaw('suggest_mosque_log.id_suggest_mosque_log DESC')
            ->get([
                'suggest_mosque_log.id_suggest_mosque_log',
                'suggest_mosque_log.id_suggest_mosque',
                'suggest_mosque_log.id_admin',
                'suggest_mosque_log.action',
                'suggest_mosque_log.date_log',
                'suggest_mosque_log.soft_delete',
                'suggest_mosque_log.created_at as created_at',
                'suggest_mosque_log.updated_at as updated_at'
            ]);

        if(count($suggestMosqueLog) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Log Suggest Mosque data';
            $res['data'] = $suggestMosqueLog;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueLog;

            return response($res, 200);
        }
    }
}
