<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Borough;
use App\Model\Districts;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueSalahTime;
use App\Model\Mosque\MosqueFacilities;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

class MosqueSalahTimesController extends Controller
{
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-salah-times/store",
     *     tags={"Mosque Salah Times"},
     *     summary="Storing new data Mosque Salah Times response",
     *     description="Storing new data Mosque Salah Times response",
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
            'id_mosque'=>'required|numeric',
            'date_salah_times'=>'nullable|date|date_format:Y-m-d',
            'type_salah_times'=>'required|numeric',
            'time_salah_times'=>'required|date_format:H:i',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $date_salah_times = $request->input('date_salah_times') ?? Carbon::now()->format('Y-m-d');
        $type_salah_times = $request->input('type_salah_times');
        $time_salah_times = $request->input('time_salah_times').':00';

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

        $data = MosqueSalahTime::create([
            'id_mosque' => $id_mosque,
            'date_salah_times' => $date_salah_times,
            'type_salah_times' => $type_salah_times,
            'time_salah_times' => $time_salah_times,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Salah Times data';
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
     *   path="/mosque-salah-times/update",
     *     tags={"Mosque Salah Times"},
     *     summary="Updating data Mosque Salah Times response",
     *     description="Updating data Mosque Salah Times response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_salah_times",
     *     in="formData",
     *     description="ID Salah Times ...",
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
     *  @SWG\Parameter(
     *     name="time_salah_times",
     *     in="formData",
     *     description="time_salah_times (hh:ii ,ex : 09:52) ...",
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
            'id_salah_times'=>'required|numeric',
            'id_mosque'=>'nullable|numeric',
            'date_salah_times'=>'nullable|date|date_format:Y-m-d',
            'type_salah_times'=>'nullable|numeric',
            'time_salah_times'=>'nullable|date_format:H:i',
        ]);  

        $id_salah_times = $request->input('id_salah_times');
        $id_mosque = $request->input('id_mosque');
        $date_salah_times = $request->input('date_salah_times');
        $type_salah_times = $request->input('type_salah_times');
        $time_salah_times = $request->input('time_salah_times');

        $data = MosqueSalahTime::find($id_salah_times);

        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }else if($data->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque Salah Times was deleted. Please contact customer service.";

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

        if($date_salah_times !== null){
            $data->date_salah_times = $date_salah_times;
        }

        if($type_salah_times !== null){
            $data->type_salah_times = $type_salah_times;
        }

        if($time_salah_times !== null){
            $data->time_salah_times = $time_salah_times.':00';
        }

        $data = $data->save();

        $data = MosqueSalahTime::with('Mosque')->find($id_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque Salah Times data';
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
     *   path="/mosque-salah-times/delete",
     *     tags={"Mosque Salah Times"},
     *     summary="Deleting data Mosque Salah Times response",
     *     description="Deleting data Mosque Salah Times response",
     *     operationId="deleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_salah_times",
     *     in="formData",
     *     description="ID Salah Times ...",
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
            'id_salah_times'=>'required|numeric',
        ]);  

        $id_salah_times = $request->input('id_salah_times');

        $data = MosqueSalahTime::find($id_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 1;
        $data = $data->save();

        $data = MosqueSalahTime::find($id_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque Salah Times data';
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
     *   path="/mosque-salah-times/undelete",
     *     tags={"Mosque Salah Times"},
     *     summary="Restoring / undelete data Mosque Salah Times response",
     *     description="Restoring / undelete data Mosque Salah Times response",
     *     operationId="undeleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_salah_times",
     *     in="formData",
     *     description="ID Salah Times ...",
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
            'id_salah_times'=>'required|numeric',
        ]);  

        $id_salah_times = $request->input('id_salah_times');

        $data = MosqueSalahTime::find($id_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Salah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 0;
        $data = $data->save();

        $data = MosqueSalahTime::find($id_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring / undelete Mosque Salah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque-salah-times/show",
     *     tags={"Mosque Salah Times"},
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
        $data = MosqueSalahTime::where('soft_delete', 0)
        ->orderBy('id_salah_times', 'DESC')
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
            $res['message'] = 'Success return Mosque Salah Times data';

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
     *   path="/mosque-salah-times/showById",
     *     tags={"Mosque Salah Times"},
     *     summary="Show by id data mosque salah times response",
     *     description="Show by id data mosque salah times response",
     *     operationId="showByIdSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_salah_times",
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
            'id_salah_times'=>'required|numeric',
        ]);  

        $id_salah_times = $request->input('id_salah_times');
        
        
        $data = MosqueSalahTime::where('id_salah_times', $id_salah_times)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        
        $data = MosqueSalahTime::where('id_salah_times', $id_salah_times)
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
            $res['message'] = 'Success return Mosque Salah Times data';
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
     *   path="/mosque-salah-times/showByFilter",
     *     tags={"Mosque Salah Times"},
     *     summary="Show by filter data mosque salah times response",
     *     description="Show by filter data mosque salah times response",
     *     operationId="showByFilterSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_salah_times",
     *     in="formData",
     *     description="ID Salah Times ...",
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
        
        $id_salah_times = $request->input('id_salah_times');
        $id_mosque = $request->input('id_mosque');
        $date_salah_times = $request->input('date_salah_times');
        $type_salah_times = $request->input('type_salah_times');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = MosqueSalahTime::query()
        ->when($request->has('id_salah_times'), function ($query) use ($id_salah_times) {
            $query->where('salah_times.id_salah_times',$id_salah_times);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('salah_times.id_mosque',$id_mosque);
        })
        ->when($request->has('date_salah_times'), function ($query) use ($date_salah_times) {
            $query->where('salah_times.date_salah_times',$date_salah_times);
        })
        ->when($request->has('type_salah_times'), function ($query) use ($type_salah_times) {
            $query->where('salah_times.type_salah_times',$type_salah_times);
        })
        ->where('salah_times.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('salah_times.id_salah_times '.$order)
        ->distinct('salah_times.id_salah_times')
        ->get([
            'salah_times.id_salah_times',
            'salah_times.id_mosque',
            'salah_times.date_salah_times',
            'salah_times.type_salah_times',
            'salah_times.time_salah_times',
            'salah_times.soft_delete',
            'salah_times.created_at',
            'salah_times.updated_at'
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
