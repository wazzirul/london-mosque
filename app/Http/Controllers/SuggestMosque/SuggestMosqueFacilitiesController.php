<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\SuggestMosque\SuggestMosque;
use App\Model\SuggestMosque\SuggestMosqueFacilities;
use App\Model\Facilities;

class SuggestMosqueFacilitiesController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/store",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Menambah data suggest mosque facilities response",
     *     description="Menambah data suggest mosque facilities response",
     *     operationId="storeSuggestMosqueFacilities",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities ...",
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
            'id_suggest_mosque'=>'required|numeric',
            'id_facilities'=>'required|numeric',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_facilities = $request->input('id_facilities');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $facilities = Facilities::find($id_facilities);
        if($facilities === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Facilities for that ID not found.";

            return response($res, 200);
        }

        $data = SuggestMosqueFacilities::create([
            'id_suggest_mosque' => $id_suggest_mosque,
            'id_facilities' => $id_facilities,
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
     *   path="/suggest-mosque/facilities/update",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Mengedit data suggest mosque facilities response",
     *     description="Mengedit data suggest mosque facilities response",
     *     operationId="updateSuggestMosqueFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_facilities",
     *     in="formData",
     *     description="ID Suggest Mosque Facilities ...",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="id_facilities",
     *     in="formData",
     *     description="ID Facilities (Kosongi jika tidak ingin merubah data)...",
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
            'id_suggest_mosque_facilities'=>'required|numeric',
            'id_suggest_mosque'=>'nullable|numeric',
            'id_facilities'=>'nullable|numeric',
        ]);  

        $id_suggest_mosque_facilities = $request->input('id_suggest_mosque_facilities');
        $suggestMosqueFacilities = SuggestMosqueFacilities::find($id_suggest_mosque_facilities);

        if($suggestMosqueFacilities === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Facilities for that ID not found.";

            return response($res, 200);
        }

        $id_suggest_mosque = $request->input('id_suggest_mosque') ?? null;
        $id_facilities = $request->input('id_facilities') ?? null;

        if($id_suggest_mosque !== null){
            $suggestMosque = SuggestMosque::find($id_suggest_mosque);
            if($suggestMosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Suggest Mosque for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosqueFacilities->id_suggest_mosque = $id_suggest_mosque;
        }

        if($id_facilities !== null){
            $facilities = Facilities::find($id_facilities);
            if($facilities === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Facilities for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosqueFacilities->id_facilities = $id_facilities;
        }

        $data = $suggestMosqueFacilities->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/delete",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Menghapus data suggest mosque facilities response",
     *     description="Menghapus data suggest mosque facilities response",
     *     operationId="deleteSuggestMosqueFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_facilities",
     *     in="formData",
     *     description="ID Suggest Mosque Facilities ...",
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
            'id_suggest_mosque_facilities'=>'required|numeric',
        ]);  

        $id_suggest_mosque_facilities = $request->input('id_suggest_mosque_facilities');
        $suggestMosqueFacilities = SuggestMosqueFacilities::find($id_suggest_mosque_facilities);

        if($suggestMosqueFacilities === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Facilities for that ID not found.";

            return response($res, 200);
        }

        $suggestMosqueFacilities->soft_delete = 1;

        $data = $suggestMosqueFacilities->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/unDelete",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Mengembalikan data terhapus data suggest mosque facilities response",
     *     description="Mengembalikan data terhapus data suggest mosque facilities response",
     *     operationId="unDeleteSuggestMosqueFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_facilities",
     *     in="formData",
     *     description="ID Suggest Mosque Facilities ...",
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
            'id_suggest_mosque_facilities'=>'required|numeric',
        ]);  

        $id_suggest_mosque_facilities = $request->input('id_suggest_mosque_facilities');
        $suggestMosqueFacilities = SuggestMosqueFacilities::where('id_suggest_mosque_facilities', $id_suggest_mosque_facilities)
            ->first();

        if($suggestMosqueFacilities === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Facilities for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueFacilities->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Facilities is active.";

            return response($res, 200);
        }

        $suggestMosqueFacilities->soft_delete = 0;

        $data = $suggestMosqueFacilities->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/suggest-mosque/facilities/show",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Show all data suggest mosque facilities response",
     *     description="Show all data suggest mosque facilities response",
     *     operationId="showSuggestMosqueFacilities",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $suggestMosqueFacilities = SuggestMosqueFacilities::where('soft_delete', 0)
            ->orderBy('id_suggest_mosque_facilities', 'DESC')
            ->get();

        if(count($suggestMosqueFacilities) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/showById",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Show data suggest mosque facilities by ID suggest mosque facilities response",
     *     description="Show data suggest mosque facilities by ID suggest mosque facilities response",
     *     operationId="showByIdSuggestMosqueFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_facilities",
     *     in="formData",
     *     description="ID Suggest Mosque Facilities ...",
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
            'id_suggest_mosque_facilities'=>'required|numeric',
        ]);  

        $id_suggest_mosque_facilities = $request->input('id_suggest_mosque_facilities');
        $suggestMosqueFacilities = SuggestMosqueFacilities::where('id_suggest_mosque_facilities', $id_suggest_mosque_facilities)
            ->first();

        if($suggestMosqueFacilities === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueFacilities->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Suggest Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $suggestMosqueFacilities;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/facilities/showByFilter",
     *     tags={"Suggest Mosque Facilities"},
     *     summary="Show data by filter suggest mosque facilities response",
     *     description="Show data by filter suggest mosque facilities response",
     *     operationId="showByFilterSuggestMosqueFacilities",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
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
        
        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $id_facilities = $request->input('id_facilities');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $suggestMosqueFacilities = SuggestMosqueFacilities::query()
        ->when($request->has('id_suggest_mosque'), function ($query) use ($id_suggest_mosque) {
            $query->where('suggest_mosque_facilities.id_suggest_mosque',$id_suggest_mosque);
        })
        ->when($request->has('id_facilities'), function ($query) use ($id_facilities) {
            $query->where('suggest_mosque_facilities.id_facilities',$id_facilities);
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('suggest_mosque_facilities.soft_delete',$soft_delete);
        })
        ->skip($skip)
        ->take($take)
        ->with(['Facilities'])
        ->orderByRaw('suggest_mosque_facilities.id_suggest_mosque_facilities '.$order)
        ->get([
            'suggest_mosque_facilities.id_suggest_mosque_facilities',
            'suggest_mosque_facilities.id_suggest_mosque',
            'suggest_mosque_facilities.id_facilities',
            'suggest_mosque_facilities.mosque_facilities_approve_status',
            'suggest_mosque_facilities.soft_delete',
            'suggest_mosque_facilities.created_at as created_at',
            'suggest_mosque_facilities.updated_at as updated_at'
        ]);
        
        if(count($suggestMosqueFacilities) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Suggest Mosque Facilities data';
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueFacilities;

            return response($res, 200);
        }
    }
}
