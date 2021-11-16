<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\SuggestMosque\SuggestMosqueSalahTimes;
use App\Model\SuggestMosque\SuggestMosque;

class SuggestMosqueSalahTimesController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/store",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Menambah data suggest mosque salah times response",
     *     description="Menambah data suggest mosque salah times response",
     *     operationId="storeSuggestMosqueSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="date_salah_times",
     *     in="formData",
     *     description="date_salah_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="type_salah_times",
     *     in="formData",
     *     description="type_salah_times (1 = Fajr || 2 = Zuhr || 3 = Asr || 4 = Maghrib || 5 = Isha) ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_salah_times",
     *     in="formData",
     *     description="time_salah_times (hh:ii ,ex : 09:52) ...",
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
            'date_salah_times'=>'nullable',
            'type_salah_times'=>'required',
            'time_salah_times'=>'required',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $date_salah_times = $request->input('date_salah_times') ?? Carbon::now()->format('Y-m-d');
        $type_salah_times = $request->input('type_salah_times');
        $time_salah_times = $request->input('time_salah_times').':00';

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $data = SuggestMosqueSalahTimes::create([
            'id_suggest_mosque' => $id_suggest_mosque,
            'date_salah_times' => $date_salah_times,
            'type_salah_times' => $type_salah_times,
            'time_salah_times' => $time_salah_times,
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
     *   path="/suggest-mosque/salah-times/update",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Mengedit data suggest mosque salah times response",
     *     description="Mengedit data suggest mosque salah times response",
     *     operationId="updateSuggestMosqueSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Salah Times ...",
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
     *     name="date_salah_times",
     *     in="formData",
     *     description="date_salah_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="type_salah_times",
     *     in="formData",
     *     description="type_salah_times (1 = Fajr || 2 = Zuhr || 3 = Asr || 4 = Maghrib || 5 = Isha) (Kosongi jika tidak ingin merubah data)...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_salah_times",
     *     in="formData",
     *     description="time_salah_times (hh:ii ,ex : 09:52) (Kosongi jika tidak ingin merubah data)...",
     *     required=true,
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
            'id_suggest_mosque_salah_times'=>'required|numeric',
            'id_suggest_mosque'=>'nullable|numeric',
            'date_salah_times'=>'nullable',
            'type_salah_times'=>'nullable',
            'time_salah_times'=>'nullable',
        ]);  

        $id_suggest_mosque_salah_times = $request->input('id_suggest_mosque_salah_times');
        $suggestMosqueSalahTimes = SuggestMosqueSalahTimes::find($id_suggest_mosque_salah_times);

        if($suggestMosqueSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }

        $id_suggest_mosque = $request->input('id_suggest_mosque') ?? null;
        $date_salah_times = $request->input('date_salah_times') ?? null;
        $type_salah_times = $request->input('type_salah_times') ?? null;
        $time_salah_times = $request->input('time_salah_times') ?? null;

        if($id_suggest_mosque !== null){
            $suggestMosque = SuggestMosque::find($id_suggest_mosque);
            if($suggestMosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Suggest Mosque for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosqueSalahTimes->id_suggest_mosque = $id_suggest_mosque;
        }

        if($date_salah_times !== null){
            $suggestMosqueSalahTimes->date_salah_times = $date_salah_times;
        }

        if($type_salah_times !== null){
            $suggestMosqueSalahTimes->type_salah_times = $type_salah_times;
        }

        if($time_salah_times !== null){
            $suggestMosqueSalahTimes->time_salah_times = $time_salah_times.":00";
        }

        $data = $suggestMosqueSalahTimes->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/delete",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Menghapus data suggest mosque response",
     *     description="Menghapus data suggest mosque response",
     *     operationId="deleteSuggestMosqueSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Salah Times ...",
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
            'id_suggest_mosque_salah_times'=>'required|numeric',
        ]);  

        $id_suggest_mosque_salah_times = $request->input('id_suggest_mosque_salah_times');
        $suggestMosqueSalahTimes = SuggestMosqueSalahTimes::find($id_suggest_mosque_salah_times);

        if($suggestMosqueSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }

        $suggestMosqueSalahTimes->soft_delete = 1;

        $data = $suggestMosqueSalahTimes->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/unDelete",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Mengembalikan data terhapus data suggest mosque response",
     *     description="Mengembalikan data terhapus data suggest mosque response",
     *     operationId="unDeleteSuggestMosqueSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Salah Times ...",
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
            'id_suggest_mosque_salah_times'=>'required|numeric',
        ]);  

        $id_suggest_mosque_salah_times = $request->input('id_suggest_mosque_salah_times');
        $suggestMosqueSalahTimes = SuggestMosqueSalahTimes::where('id_suggest_mosque_salah_times', $id_suggest_mosque_salah_times)
            ->first();

        if($suggestMosqueSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueSalahTimes->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Salah Times is active.";

            return response($res, 200);
        }

        $suggestMosqueSalahTimes->soft_delete = 0;

        $data = $suggestMosqueSalahTimes->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/suggest-mosque/salah-times/show",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Show all data suggest mosque response",
     *     description="Show all data suggest mosque response",
     *     operationId="showSuggestMosqueSalahTimes",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $suggestMosqueSalahTimes = SuggestMosqueSalahTimes::where('soft_delete', 0)
            ->orderBy('id_suggest_mosque_salah_times', 'DESC')
            ->get();

        if(count($suggestMosqueSalahTimes) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/showById",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Show data suggest mosque by ID suggest mosque response",
     *     description="Show data suggest mosque by ID suggest mosque response",
     *     operationId="showByIdSuggestMosqueSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Salah Times ...",
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
            'id_suggest_mosque_salah_times'=>'required|numeric',
        ]);  

        $id_suggest_mosque_salah_times = $request->input('id_suggest_mosque_salah_times');
        $suggestMosqueSalahTimes = SuggestMosqueSalahTimes::where('id_suggest_mosque_salah_times', $id_suggest_mosque_salah_times)
            ->first();

        if($suggestMosqueSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueSalahTimes->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Suggest Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $suggestMosqueSalahTimes;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/salah-times/showByFilter",
     *     tags={"Suggest Mosque Salah Times"},
     *     summary="Show data by filter suggest mosque salah times response",
     *     description="Show data by filter suggest mosque salah times response",
     *     operationId="showByFilterSuggestMosqueSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="date_salah_times",
     *     in="formData",
     *     description="date_salah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="type_salah_times",
     *     in="formData",
     *     description="type_salah_times (1 = Fajr || 2 = Zuhr || 3 = Asr || 4 = Maghrib || 5 = Isha) ...",
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
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showByFilter(Request $request){ 
        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $date_salah_times = $request->input('date_salah_times');
        $type_salah_times = $request->input('type_salah_times');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $suggestMosqueSalahTimes = SuggestMosqueSalahTimes::query()
        ->when($request->has('id_suggest_mosque'), function ($query) use ($id_suggest_mosque) {
            $query->where('suggest_mosque_salah_times.id_suggest_mosque',$id_suggest_mosque);
        })
        ->when($request->has('date_salah_times'), function ($query) use ($date_salah_times) {
            $query->where('suggest_mosque_salah_times.date_salah_times',$date_salah_times);
        })
        ->when($request->has('type_salah_times'), function ($query) use ($type_salah_times) {
            $query->where('suggest_mosque_salah_times.type_salah_times',$type_salah_times);
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('suggest_mosque_salah_times.soft_delete',$soft_delete);
        })
        ->skip($skip)
        ->take($take)
        ->orderByRaw('suggest_mosque_salah_times.id_suggest_mosque_salah_times '.$order)
        ->get([
            'suggest_mosque_salah_times.id_suggest_mosque_salah_times',
            'suggest_mosque_salah_times.id_suggest_mosque',
            'suggest_mosque_salah_times.date_salah_times',
            'suggest_mosque_salah_times.type_salah_times',
            'suggest_mosque_salah_times.time_salah_times',
            'suggest_mosque_salah_times.soft_delete',
            'suggest_mosque_salah_times.created_at as created_at',
            'suggest_mosque_salah_times.updated_at as updated_at'
        ]);
        
        if(count($suggestMosqueSalahTimes) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Suggest Mosque Salah Times data';
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueSalahTimes;

            return response($res, 200);
        }
    }
}
