<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\SuggestMosque\SuggestMosqueSpecialSalahTimes;
use App\Model\SuggestMosque\SuggestMosque;

class SuggestMosqueSpecialSalahTimesController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/special-salah-times/store",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Menambah data suggest mosque special salah times response",
     *     description="Menambah data suggest mosque special salah times response",
     *     operationId="storeSuggestMosqueSpecialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="category_special_salah",
     *     in="formData",
     *     description="category_special_salah (1: Eid-ul Fitri, 2: Eid-ul Adha, 3: Jummah) ...",
     *     required=true,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="date_special_salah_times",
     *     in="formData",
     *     description="date_special_salah_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="order_special_salah_times",
     *     in="formData",
     *     description="order_special_salah_times (1st, 2nd, 3rd, 4th, 5th, etc.) ...",
     *     required=true,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_special_salah_times",
     *     in="formData",
     *     description="time_special_salah_times (hh:ii ,ex : 09:52) ...",
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
            'category_special_salah'=>'required',
            'date_special_salah_times'=>'nullable|date_format:Y-m-d',
            'order_special_salah_times'=>'required',
            'time_special_salah_times'=>'required|date_format:H:i',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');
        $category_special_salah = $request->input('category_special_salah');
        $date_special_salah_times = $request->input('date_special_salah_times') ?? Carbon::now()->format('Y-m-d');
        $order_special_salah_times = $request->input('order_special_salah_times');
        $time_special_salah_times = $request->input('time_special_salah_times').':00';

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $data = SuggestMosqueSpecialSalahTimes::create([
            'id_suggest_mosque' => $id_suggest_mosque,
            'category_special_salah' => $category_special_salah,
            'date_special_salah_times' => $date_special_salah_times,
            'order_special_salah_times' => $order_special_salah_times,
            'time_special_salah_times' => $time_special_salah_times,
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
     *   path="/suggest-mosque/special-salah-times/update",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Mengedit data suggest mosque special salah times response",
     *     description="Mengedit data suggest mosque special salah times response",
     *     operationId="updateSuggestMosqueSpecialSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Special Salah Times ...",
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
     *     name="category_special_salah",
     *     in="formData",
     *     description="category_special_salah (1: Eid-ul Fitri, 2: Eid-ul Adha, 3: Jummah) ...",
     *     required=false,
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="date_special_salah_times",
     *     in="formData",
     *     description="date_special_salah_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] (Kosongi jika tidak ingin merubah data)...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="order_special_salah_times",
     *     in="formData",
     *     description="order_special_salah_times (1st, 2nd, 3rd, 4th, 5th, etc.) (Kosongi jika tidak ingin merubah data)...",
     *     required=true,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_special_salah_times",
     *     in="formData",
     *     description="time_special_salah_times (hh:ii ,ex : 09:52) (Kosongi jika tidak ingin merubah data)...",
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
            'id_special_salah_times'=>'required|numeric',
            'id_suggest_mosque'=>'nullable|numeric',
            'category_special_salah'=>'nullable',
            'date_special_salah_times'=>'nullable|date_format:Y-m-d',
            'order_special_salah_times'=>'nullable',
            'time_special_salah_times'=>'nullable|date_format:H:i',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');
        $suggestMosqueSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::find($id_special_salah_times);

        if($suggestMosqueSpecialSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Special Salah Times for that ID not found.";

            return response($res, 200);
        }

        $id_suggest_mosque = $request->input('id_suggest_mosque') ?? null;
        $category_special_salah = $request->input('category_special_salah') ?? null;
        $date_special_salah_times = $request->input('date_special_salah_times') ?? null;
        $order_special_salah_times = $request->input('order_special_salah_times') ?? null;
        $time_special_salah_times = $request->input('time_special_salah_times') ?? null;

        if($id_suggest_mosque !== null){
            $suggestMosque = SuggestMosque::find($id_suggest_mosque);
            if($suggestMosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Suggest Mosque for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosqueSpecialSalahTimes->id_suggest_mosque = $id_suggest_mosque;
        }

        if($category_special_salah !== null){
            $suggestMosqueSpecialSalahTimes->category_special_salah = $category_special_salah;
        }

        if($date_special_salah_times !== null){
            $suggestMosqueSpecialSalahTimes->date_special_salah_times = $date_special_salah_times;
        }

        if($order_special_salah_times !== null){
            $suggestMosqueSpecialSalahTimes->order_special_salah_times = $order_special_salah_times;
        }

        if($time_special_salah_times !== null){
            $suggestMosqueSpecialSalahTimes->time_special_salah_times = $time_special_salah_times.":00";
        }

        $data = $suggestMosqueSpecialSalahTimes->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/special-salah-times/delete",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Menghapus data suggest mosque response",
     *     description="Menghapus data suggest mosque response",
     *     operationId="deleteSuggestMosqueSpecialSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Special Salah Times ...",
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
            'id_special_salah_times'=>'required|numeric',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');
        $suggestMosqueSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::find($id_special_salah_times);

        if($suggestMosqueSpecialSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Special Salah Times for that ID not found.";

            return response($res, 200);
        }

        $suggestMosqueSpecialSalahTimes->soft_delete = 1;

        $data = $suggestMosqueSpecialSalahTimes->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/special-salah-times/unDelete",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Mengembalikan data terhapus data suggest mosque response",
     *     description="Mengembalikan data terhapus data suggest mosque response",
     *     operationId="unDeleteSuggestMosqueSpecialSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Special Salah Times ...",
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
            'id_special_salah_times'=>'required|numeric',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');
        $suggestMosqueSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::where('id_special_salah_times', $id_special_salah_times)
            ->first();

        if($suggestMosqueSpecialSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Special Salah Times for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueSpecialSalahTimes->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Special Salah Times is active.";

            return response($res, 200);
        }

        $suggestMosqueSpecialSalahTimes->soft_delete = 0;

        $data = $suggestMosqueSpecialSalahTimes->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/suggest-mosque/special-salah-times/show",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Show all data suggest mosque response",
     *     description="Show all data suggest mosque response",
     *     operationId="showSuggestMosqueSpecialSalahTimes",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $suggestMosqueSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::where('soft_delete', 0)
            ->orderBy('id_special_salah_times', 'DESC')
            ->get();

        if(count($suggestMosqueSpecialSalahTimes) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/special-salah-times/showById",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Show data suggest mosque by ID suggest mosque response",
     *     description="Show data suggest mosque by ID suggest mosque response",
     *     operationId="showByIdSuggestMosqueSpecialSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Suggest Mosque Special Salah Times ...",
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
            'id_special_salah_times'=>'required|numeric',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');
        $suggestMosqueSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::where('id_special_salah_times', $id_special_salah_times)
            ->first();

        if($suggestMosqueSpecialSalahTimes === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueSpecialSalahTimes->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Suggest Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $suggestMosqueSpecialSalahTimes;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/special-salah-times/showByFilter",
     *     tags={"Suggest Mosque Special Salah Times"},
     *     summary="Show data by filter suggest mosque special salah times response",
     *     description="Show data by filter suggest mosque special salah times response",
     *     operationId="showByFilterSuggestMosqueSpecialSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="date_special_salah_times",
     *     in="formData",
     *     description="date_special_salah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="where_year",
     *     in="formData",
     *     description="where_year [default = this year] ...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="where_month",
     *     in="formData",
     *     description="where_month (month : 1 - 12) [default = this month] ...",
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
        $date_special_salah_times = $request->input('date_special_salah_times');
        $order_special_salah_times = $request->input('order_special_salah_times');

        $year = $request->input('where_year') ?? Carbon::today()->format('Y');
        $mth = $request->input('where_month') ?? Carbon::today()->format('m');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $suggestMosqueSpecialSalahTimes = SuggestMosqueSpecialSalahTimes::query()
        ->when($request->has('id_suggest_mosque'), function ($query) use ($id_suggest_mosque) {
            $query->where('suggest_mosque_special_salah_times.id_suggest_mosque',$id_suggest_mosque);
        })
        ->when($request->has('date_special_salah_times'), function ($query) use ($date_special_salah_times) {
            $query->where('suggest_mosque_special_salah_times.date_special_salah_times',$date_special_salah_times);
        })
        ->when($request->has('order_special_salah_times'), function ($query) use ($order_special_salah_times) {
            $query->where('suggest_mosque_special_salah_times.order_special_salah_times',$order_special_salah_times);
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('suggest_mosque_special_salah_times.soft_delete',$soft_delete);
        })
        ->skip($skip)
        ->take($take)
        ->orderByRaw('suggest_mosque_special_salah_times.id_special_salah_times '.$order)
        ->get([
            'suggest_mosque_special_salah_times.id_special_salah_times',
            'suggest_mosque_special_salah_times.id_suggest_mosque',
            'suggest_mosque_special_salah_times.date_special_salah_times',
            'suggest_mosque_special_salah_times.order_special_salah_times',
            'suggest_mosque_special_salah_times.time_special_salah_times',
            'suggest_mosque_special_salah_times.soft_delete',
            'suggest_mosque_special_salah_times.created_at as created_at',
            'suggest_mosque_special_salah_times.updated_at as updated_at'
        ]);
        
        if(count($suggestMosqueSpecialSalahTimes) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Suggest Mosque Special Salah Times data';
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueSpecialSalahTimes;

            return response($res, 200);
        }
    }
}
