<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;

use App\Model\SuggestMosque\SuggestMosque;

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
    // SHOW BY ID USERS
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/showByIdUsers",
     *     tags={"Suggest Mosque"},
     *     summary="Show data suggest mosque by ID suggest mosque response",
     *     description="Show data suggest mosque by ID suggest mosque response",
     *     operationId="showByIdUsersSuggestMosque",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showByIdUsers(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $user = Users::find($id_user);
        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Users for that ID not found.";

            return response($res, 200);
        }
        
        $suggestMosque = SuggestMosque::where('id_user', $id_user)
            ->where('soft_delete', 0)
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
    // SHOW BY ID MOSQUE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/showByIdMosque",
     *     tags={"Suggest Mosque"},
     *     summary="Show data suggest mosque by ID suggest mosque response",
     *     description="Show data suggest mosque by ID suggest mosque response",
     *     operationId="showByIdMosqueSuggestMosque",
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
    public function showByIdMosque(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $mosque = Mosque::find($id_mosque);
        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        
        $suggestMosque = SuggestMosque::where('id_mosque', $id_mosque)
            ->where('soft_delete', 0)
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
}
