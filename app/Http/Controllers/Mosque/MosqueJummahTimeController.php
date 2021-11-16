<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Borough;
use App\Model\Districts;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueSalahTime;
use App\Model\Mosque\MosqueJummahTime;
use App\Model\Mosque\MosqueFacilities;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

class MosqueJummahTimeController extends Controller
{
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-jummah-times/store",
     *     tags={"Mosque Jummah Times"},
     *     summary="Storing new data Mosque Jummah Times response",
     *     description="Storing new data Mosque Jummah Times response",
     *     operationId="storeMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="date_jummah_times",
     *     in="formData",
     *     description="date_jummah_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="order_jummah_times",
     *     in="formData",
     *     description="order_jummah_times (1st, 2nd, 3rd, 4th, 5th, etc.) ...",
     *     required=true,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_jummah_times",
     *     in="formData",
     *     description="time_jummah_times (hh:ii ,ex : 09:52) ...",
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
            'id_mosque'=>'required|numeric',
            'date_jummah_times'=>'nullable|date_format:Y-m-d',
            'order_jummah_times'=>'required',
            'time_jummah_times'=>'required|date_format:H:i',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $date_jummah_times = $request->input('date_jummah_times') ?? Carbon::now()->format('Y-m-d');
        $order_jummah_times = $request->input('order_jummah_times');
        $time_jummah_times = $request->input('time_jummah_times').':00';

        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }else if($mosque->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $data = MosqueJummahTime::create([
            'id_mosque' => $id_mosque,
            'date_jummah_times' => $date_jummah_times,
            'order_jummah_times' => $order_jummah_times,
            'time_jummah_times' => $time_jummah_times,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Jummah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }



    
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Update
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-jummah-times/update",
     *     tags={"Mosque Jummah Times"},
     *     summary="Updating data Mosque Jummah Times response",
     *     description="Updating data Mosque Jummah Times response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_jummah_times",
     *     in="formData",
     *     description="ID Jummah Times ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="date_jummah_times",
     *     in="formData",
     *     description="date_jummah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="order_jummah_times",
     *     in="formData",
     *     description="order_jummah_times (1st, 2nd, 3rd, 4th, 5th, etc.) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_jummah_times",
     *     in="formData",
     *     description="time_jummah_times (hh:ii ,ex : 09:52) ...",
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
            'id_jummah_times'=>'required|numeric',
            'id_mosque'=>'nullable|numeric',
            'date_jummah_times'=>'nullable|date|date_format:Y-m-d',
            'order_jummah_times'=>'nullable',
            'time_jummah_times'=>'nullable|date_format:H:i',
        ]);  

        $id_jummah_times = $request->input('id_jummah_times');
        $id_mosque = $request->input('id_mosque');
        $date_jummah_times = $request->input('date_jummah_times');
        $order_jummah_times = $request->input('order_jummah_times');
        $time_jummah_times = $request->input('time_jummah_times').':00';

        $data = MosqueJummahTime::find($id_jummah_times);

        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Jummah Times for that ID not found.";

            return response($res, 200);
        }else if($data->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque Jummah Times was deleted. Please contact customer service.";

            return response($res, 200);
        }



        if($id_mosque !== null){
            
        $mosque = Mosque::where('id_mosque', $id_mosque)
        ->first();

            if($mosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Mosque for that ID not found.";

                return response($res, 200);
            }else if($mosque->soft_delete === 1){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Mosque was deleted. Please contact customer service.";

                return response($res, 200);
            }

            $data->id_mosque = $id_mosque;
        }

        if($date_jummah_times !== null){
            $data->date_jummah_times = $date_jummah_times;
        }

        if($order_jummah_times !== null){
            $data->order_jummah_times = $order_jummah_times;
        }

        if($time_jummah_times !== null){
            $data->time_jummah_times = $time_jummah_times;
        }

        $data = $data->save();

        $data = MosqueJummahTime::with('Mosque')->find($id_jummah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque Jummah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }





    
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Delete
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-jummah-times/delete",
     *     tags={"Mosque Jummah Times"},
     *     summary="Deleting data Mosque Jummah Times response",
     *     description="Deleting data Mosque Jummah Times response",
     *     operationId="deleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_jummah_times",
     *     in="formData",
     *     description="ID Jummah Times ...",
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
            'id_jummah_times'=>'required|numeric',
        ]);  

        $id_jummah_times = $request->input('id_jummah_times');

        $data = MosqueJummahTime::find($id_jummah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Jummah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 1;
        $data = $data->save();

        $data = MosqueJummahTime::find($id_jummah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque Jummah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }





    
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Undelete
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-jummah-times/undelete",
     *     tags={"Mosque Jummah Times"},
     *     summary="Restoring / undelete data Mosque Jummah Times response",
     *     description="Restoring / undelete data Mosque Jummah Times response",
     *     operationId="undeleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_jummah_times",
     *     in="formData",
     *     description="ID Jummah Times ...",
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
            'id_jummah_times'=>'required|numeric',
        ]);  

        $id_jummah_times = $request->input('id_jummah_times');

        $data = MosqueJummahTime::find($id_jummah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Jummah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 0;
        $data = $data->save();

        $data = MosqueJummahTime::find($id_jummah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring / undelete Mosque Jummah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque-jummah-times/show",
     *     tags={"Mosque Jummah Times"},
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
        $data = MosqueJummahTime::where('soft_delete', 0)
        ->orderBy('id_jummah_times', 'DESC')
        ->get();

        
        foreach($data as $index => $d){
            $mosque = Mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();

            $data[$index]->mosque = $mosque;
        }


        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $data;
            $res['message'] = 'Success return Mosque Jummah Times data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-jummah-times/showById",
     *     tags={"Mosque Jummah Times"},
     *     summary="Show all data mosque Jummah times response",
     *     description="Show all data mosque Jummah times response",
     *     operationId="showByIdSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_jummah_times",
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
            'id_jummah_times'=>'required|numeric',
        ]);  

        $id_jummah_times = $request->input('id_jummah_times');
        
        
        $data = MosqueJummahTime::where('id_jummah_times', $id_jummah_times)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        
        $data = MosqueJummahTime::where('id_jummah_times', $id_jummah_times)
        ->get();

        
        foreach($data as $index => $d){
            $mosque = mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();

            $data[$index]->mosque = $mosque;
        }


        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque Jummah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-jummah-times/showByFilter",
     *     tags={"Mosque Jummah Times"},
     *     summary="Show all data mosque Jummah times response",
     *     description="Show all data mosque Jummah times response",
     *     operationId="showByFilterSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_jummah_times",
     *     in="formData",
     *     description="ID Jummah Times ...",
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
     *     name="date_jummah_times",
     *     in="formData",
     *     description="date_jummah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
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
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showByFilter(Request $request){ 
        
        $id_jummah_times = $request->input('id_jummah_times');
        $id_mosque = $request->input('id_mosque');
        $date_jummah_times = $request->input('date_jummah_times');
        $order_jummah_times = $request->input('order_jummah_times');

        $year = $request->input('where_year') ?? Carbon::today()->format('Y');
        $mth = $request->input('where_month') ?? Carbon::today()->format('m');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = MosqueJummahTime::query()
        ->when($request->has('id_jummah_times'), function ($query) use ($id_jummah_times) {
            $query->where('mosque_jummah_times.id_jummah_times',$id_jummah_times);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque_jummah_times.id_mosque',$id_mosque);
        })
        ->when($request->has('date_jummah_times'), function ($query) use ($date_jummah_times) {
            $query->where('mosque_jummah_times.date_jummah_times',$date_jummah_times);
        })
        ->when($request->has('order_jummah_times'), function ($query) use ($order_jummah_times) {
            $query->where('mosque_jummah_times.order_jummah_times',$order_jummah_times);
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('mosque_jummah_times.soft_delete',$soft_delete);
        })
        ->whereYear('mosque_jummah_times.date_jummah_times', '=', $year)
        ->whereMonth('mosque_jummah_times.date_jummah_times', '=', $mth)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque_jummah_times.id_jummah_times '.$order)
        ->distinct('mosque_jummah_times.id_jummah_times')
        ->get([
            'mosque_jummah_times.id_jummah_times',
            'mosque_jummah_times.id_mosque',
            'mosque_jummah_times.date_jummah_times',
            'mosque_jummah_times.order_jummah_times',
            'mosque_jummah_times.time_jummah_times',
            'mosque_jummah_times.soft_delete',
            'mosque_jummah_times.created_at',
            'mosque_jummah_times.updated_at'
        ]);

        
        foreach($data as $index => $d){
            $mosque = mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();

            $data[$index]->mosque = $mosque;
        }


        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $data;

            return response($res, 200);
        }
    }
}
