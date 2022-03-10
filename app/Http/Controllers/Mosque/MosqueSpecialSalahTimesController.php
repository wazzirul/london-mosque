<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Borough;
use App\Model\Districts;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueSalahTime;
use App\Model\Mosque\MosqueSpecialSalahTime;
use App\Model\Mosque\MosqueFacilities;

use App\Imports\MosqueSpecialSalahJummahTimesImport;
use App\Imports\MosqueSpecialSalahEidulFitriTimesImport;
use App\Imports\MosqueSpecialSalahEidulAdhaTimesImport;
use Maatwebsite\Excel\Facades\Excel;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

class MosqueSpecialSalahTimesController extends Controller
{
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/store",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Storing new data Mosque Special Salah Times response",
     *     description="Storing new data Mosque Special Salah Times response",
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
     *     name="category_special_salah",
     *     in="formData",
     *     description="category_special_salah ( 1 = Eid-ul Fitri || 2 = Eid-ul Adha || 3 = Jummah ) ...",
     *     required=true,
     *     type="integer"
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
            'id_mosque'=>'required|numeric',
            'category_special_salah'=>'required|numeric',
            'date_special_salah_times'=>'nullable|date_format:Y-m-d',
            'order_special_salah_times'=>'required',
            'time_special_salah_times'=>'required|date_format:H:i',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $category_special_salah = $request->input('category_special_salah');
        $date_special_salah_times = $request->input('date_special_salah_times') ?? Carbon::now()->format('Y-m-d');
        $order_special_salah_times = $request->input('order_special_salah_times');
        $time_special_salah_times = $request->input('time_special_salah_times').':00';

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

        $data = MosqueSpecialSalahTime::create([
            'id_mosque' => $id_mosque,
            'category_special_salah' => $category_special_salah,
            'date_special_salah_times' => $date_special_salah_times,
            'order_special_salah_times' => $order_special_salah_times,
            'time_special_salah_times' => $time_special_salah_times,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Special Salah Times data';
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
     *   path="/mosque-specialsalah-times/update",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Updating data Mosque Special Salah Times response",
     *     description="Updating data Mosque Special Salah Times response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Special Salah Times ...",
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
     *     name="category_special_salah",
     *     in="formData",
     *     description="category_special_salah ( 1 = Eid-ul Fitri || 2 = Eid-ul Adha || 3 = Jummah ) ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="date_special_salah_times",
     *     in="formData",
     *     description="date_special_salah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="order_special_salah_times",
     *     in="formData",
     *     description="order_special_salah_times (1st, 2nd, 3rd, 4th, 5th, etc.) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_special_salah_times",
     *     in="formData",
     *     description="time_special_salah_times (hh:ii ,ex : 09:52) ...",
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
            'id_special_salah_times'=>'required|numeric',
            'id_mosque'=>'nullable|numeric',
            'category_special_salah'=>'nullable|numeric',
            'date_special_salah_times'=>'nullable|date|date_format:Y-m-d',
            'order_special_salah_times'=>'nullable',
            'time_special_salah_times'=>'nullable|date_format:H:i',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');
        $id_mosque = $request->input('id_mosque');
        $category_special_salah = $request->input('category_special_salah');
        $date_special_salah_times = $request->input('date_special_salah_times');
        $order_special_salah_times = $request->input('order_special_salah_times');
        $time_special_salah_times = $request->input('time_special_salah_times');

        $data = MosqueSpecialSalahTime::find($id_special_salah_times);

        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Special Salah Times for that ID not found.";

            return response($res, 200);
        }else if($data->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque Special Salah Times was deleted. Please contact customer service.";

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

        if($category_special_salah !== null){
            $data->category_special_salah = $category_special_salah;
        }

        if($date_special_salah_times !== null){
            $data->date_special_salah_times = $date_special_salah_times;
        }

        if($order_special_salah_times !== null){
            $data->order_special_salah_times = $order_special_salah_times;
        }

        if($time_special_salah_times !== null){
            $data->time_special_salah_times = $time_special_salah_times.':00';
        }

        $data = $data->save();

        $data = MosqueSpecialSalahTime::with('Mosque')->find($id_special_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque Special Salah Times data';
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
     *   path="/mosque-specialsalah-times/delete",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Deleting data Mosque Special Salah Times response",
     *     description="Deleting data Mosque Special Salah Times response",
     *     operationId="deleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Special Salah Times ...",
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

        $data = MosqueSpecialSalahTime::find($id_special_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Special Salah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 1;
        $data = $data->save();

        $data = MosqueSpecialSalahTime::find($id_special_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque Special Salah Times data';
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
     *   path="/mosque-specialsalah-times/undelete",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Restoring / undelete data Mosque Special Salah Times response",
     *     description="Restoring / undelete data Mosque Special Salah Times response",
     *     operationId="undeleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Special Salah Times ...",
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
            'id_special_salah_times'=>'required|numeric',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');

        $data = MosqueSpecialSalahTime::find($id_special_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Special Salah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 0;
        $data = $data->save();

        $data = MosqueSpecialSalahTime::find($id_special_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring / undelete Mosque Special Salah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque-specialsalah-times/show",
     *     tags={"Mosque Special Salah Times"},
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
        $data = MosqueSpecialSalahTime::where('soft_delete', 0)
        ->orderBy('id_special_salah_times', 'DESC')
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
            $res['message'] = 'Success return Mosque Special Salah Times data';

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
     *   path="/mosque-specialsalah-times/showById",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Show by id data mosque Special Salah times response",
     *     description="Show by id data mosque Special Salah times response",
     *     operationId="showByIdSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_special_salah_times",
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
            'id_special_salah_times'=>'required|numeric',
        ]);  

        $id_special_salah_times = $request->input('id_special_salah_times');
        
        
        $data = MosqueSpecialSalahTime::where('id_special_salah_times', $id_special_salah_times)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        
        $data = MosqueSpecialSalahTime::where('id_special_salah_times', $id_special_salah_times)
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
            $res['message'] = 'Success return Mosque Special Salah Times data';
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
     *   path="/mosque-specialsalah-times/showByFilter",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Show by filter data mosque Special Salah times response",
     *     description="Show by filter data mosque Special Salah times response",
     *     operationId="showByFilterSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_special_salah_times",
     *     in="formData",
     *     description="ID Special Salah Times ...",
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
     *  @SWG\Parameter(
     *     name="category_special_salah",
     *     in="formData",
     *     description="category_special_salah ( 1 = Eid-ul Fitri || 2 = Eid-ul Adha || 3 = Jummah ) ...",
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
     *     description="where_year [default = this year (gregorian / masehi year)] ...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="where_month",
     *     in="formData",
     *     description="where_month (month : 1 - 12) [default = this month (gregorian / masehi month)] ...",
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
        
        $id_special_salah_times = $request->input('id_special_salah_times');
        $id_borough = $request->input('id_borough');
        $id_mosque = $request->input('id_mosque');
        $category_special_salah = $request->input('category_special_salah');
        $date_special_salah_times = $request->input('date_special_salah_times');
        $order_special_salah_times = $request->input('order_special_salah_times');

        $year = $request->input('where_year') ?? Carbon::today()->format('Y');
        $mth = $request->input('where_month') ?? Carbon::today()->format('m');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = MosqueSpecialSalahTime::query()
        ->when($request->has('id_special_salah_times'), function ($query) use ($id_special_salah_times) {
            $query->where('mosque_special_salah_times.id_special_salah_times',$id_special_salah_times);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque_special_salah_times.id_mosque',$id_mosque);
        })
        ->when($request->has('category_special_salah'), function ($query) use ($category_special_salah) {
            $query->where('mosque_special_salah_times.category_special_salah',$category_special_salah);
        })
        ->when($request->has('date_special_salah_times'), function ($query) use ($date_special_salah_times) {
            $query->where('mosque_special_salah_times.date_special_salah_times',$date_special_salah_times);
        })
        ->when($request->has('order_special_salah_times'), function ($query) use ($order_special_salah_times) {
            $query->where('mosque_special_salah_times.order_special_salah_times',$order_special_salah_times);
        })
        ->when($request->has('where_year'), function ($query) use ($year) {
            $query->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year);
        })
        ->when($request->has('where_month'), function ($query) use ($mth) {
            $query->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth);
        })
        ->when($request->has('id_borough'), function ($query) use ($id_borough) {
            $query->leftJoin('mosque','mosque.id_mosque','=','mosque_special_salah_times.id_mosque');
            $query->where('mosque.id_borough',$id_borough);
        })
        ->where('mosque_special_salah_times.soft_delete',$soft_delete)
        // ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
        // ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque_special_salah_times.id_special_salah_times '.$order)
        ->distinct('mosque_special_salah_times.id_special_salah_times')
        ->get([
            'mosque_special_salah_times.id_special_salah_times',
            'mosque_special_salah_times.id_mosque',
            'mosque_special_salah_times.category_special_salah',
            'mosque_special_salah_times.date_special_salah_times',
            'mosque_special_salah_times.order_special_salah_times',
            'mosque_special_salah_times.time_special_salah_times',
            'mosque_special_salah_times.soft_delete',
            'mosque_special_salah_times.created_at',
            'mosque_special_salah_times.updated_at'
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
            $res['message'] = 'Data not found';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/importJummah",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Import Salah Jummah data in Mosque Spesial Salah Times response",
     *     description="Import Salah Jummah data in Mosque Spesial Salah Times <br>
        <p>1. File Excel Salah Jummah data extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importJummahMosqueSpesialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Jummah Times .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importJummah(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric|min:1',
            'file'=>'required',
        ]);  
        $file = $request->file;
        $id_mosque = $request->input('id_mosque');
        
        $mosque = Mosque::find($id_mosque);
        
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

        $mimes = $file->getClientOriginalExtension();
        if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
            $res['message'] = "Failed";
            $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
            return response($res, 422);
        }        
        
        ////////////////////////////////////////////////
        ///////////////////////////////////////////////
        ///Check Delimiter Separator IF Using CSV
        if(in_array($mimes, array('csv','Csv','CSV'))){
            $delimiters = [",", ";", "\t"];
            $checkLines = 3;
            $default =",";

            $fileObject = new \SplFileObject($file);
            $results = [];
            $counter = 0;
            while ($fileObject->valid() && $counter <= $checkLines) {
                $line = $fileObject->fgets();
                foreach ($delimiters as $delimiter) {
                    $fields = explode($delimiter, $line);
                    $totalFields = count($fields);
                    if ($totalFields > 1) {
                        if (!empty($results[$delimiter])) {
                            $results[$delimiter] += $totalFields;
                        } else {
                            $results[$delimiter] = $totalFields;
                        }
                    }
                }
                $counter++;
            }
            if (!empty($results)) {
                $results = array_keys($results, max($results));

                // return $results[0];
                if($results[0] !== ","){
                    $res['status_code'] = 406;
                    $res['success'] = false;
                    $res['detail'] = "Error, File CSV Must Use Comma (,) Delimiter Separator.";

                    return response($res, 200);
                }
            }
        }
        ///END OF Check Delimiter Separator IF Using CSV
        ////////////////////////////////////////////////
        ///////////////////////////////////////////////

        $data = $id_mosque; 
        $import = new MosqueSpecialSalahJummahTimesImport($data);
        Excel::import($import, $file);

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque Salah Jummah Times data';
        return response($res, 200);
    }


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/importJummahMultiple",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Import Salah Jummah data in Mosque Spesial Salah Times response",
     *     description="Import Salah Jummah data in Mosque Spesial Salah Times <br>
        <p>1. File Excel Salah Jummah data extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importJummahMultipleMosqueSpesialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Jummah .xlsx or .xls or .csv) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Jummah .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[2]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Jummah .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importJummahMultiple(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
            'file'=>'required',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $file = $request->input('file');

        $mosque = Mosque::find($id_mosque);
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

        foreach($request->file as $file){
            $mimes = $file->getClientOriginalExtension();
            if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
                $res['message'] = "Failed";
                $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
                return response($res, 422);
            }          
            
            ////////////////////////////////////////////////
            ///////////////////////////////////////////////
            ///Check Delimiter Separator IF Using CSV
            if(in_array($mimes, array('csv','Csv','CSV'))){
                $delimiters = [",", ";", "\t"];
                $checkLines = 3;
                $default =",";
    
                $fileObject = new \SplFileObject($file);
                $results = [];
                $counter = 0;
                while ($fileObject->valid() && $counter <= $checkLines) {
                    $line = $fileObject->fgets();
                    foreach ($delimiters as $delimiter) {
                        $fields = explode($delimiter, $line);
                        $totalFields = count($fields);
                        if ($totalFields > 1) {
                            if (!empty($results[$delimiter])) {
                                $results[$delimiter] += $totalFields;
                            } else {
                                $results[$delimiter] = $totalFields;
                            }
                        }
                    }
                    $counter++;
                }
                if (!empty($results)) {
                    $results = array_keys($results, max($results));
    
                    // return $results[0];
                    if($results[0] !== ","){
                        $res['status_code'] = 406;
                        $res['success'] = false;
                        $res['detail'] = "Error, File CSV Must Use Comma (,) Delimiter Separator.";
    
                        return response($res, 200);
                    }
                }
            }

            $data = $id_mosque; 
            $import = new MosqueSpecialSalahJummahTimesImport($data);
            Excel::import($import, $file);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque Salah Jummah Times data';
        return response($res, 200);
    }


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/importEidulFitri",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Import Salah Eid-ul Fitri data in Mosque Spesial Salah Times response",
     *     description="Import Salah Eid-ul Fitri data in Mosque Spesial Salah Times <br>
        <p>1. File Excel Salah Eid-ul Fitri data extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importEidulFitriMosqueSpesialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Fitri Times .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importEidulFitri(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric|min:1',
            'file'=>'required',
        ]);  
        $file = $request->file;
        $id_mosque = $request->input('id_mosque');
        
        $mosque = Mosque::find($id_mosque);
        
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

        $mimes = $file->getClientOriginalExtension();
        if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
            $res['message'] = "Failed";
            $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
            return response($res, 422);
        }        
        
        ////////////////////////////////////////////////
        ///////////////////////////////////////////////
        ///Check Delimiter Separator IF Using CSV
        if(in_array($mimes, array('csv','Csv','CSV'))){
            $delimiters = [",", ";", "\t"];
            $checkLines = 3;
            $default =",";

            $fileObject = new \SplFileObject($file);
            $results = [];
            $counter = 0;
            while ($fileObject->valid() && $counter <= $checkLines) {
                $line = $fileObject->fgets();
                foreach ($delimiters as $delimiter) {
                    $fields = explode($delimiter, $line);
                    $totalFields = count($fields);
                    if ($totalFields > 1) {
                        if (!empty($results[$delimiter])) {
                            $results[$delimiter] += $totalFields;
                        } else {
                            $results[$delimiter] = $totalFields;
                        }
                    }
                }
                $counter++;
            }
            if (!empty($results)) {
                $results = array_keys($results, max($results));

                // return $results[0];
                if($results[0] !== ","){
                    $res['status_code'] = 406;
                    $res['success'] = false;
                    $res['detail'] = "Error, File CSV Must Use Comma (,) Delimiter Separator.";

                    return response($res, 200);
                }
            }
        }
        ///END OF Check Delimiter Separator IF Using CSV
        ////////////////////////////////////////////////
        ///////////////////////////////////////////////

        $data = $id_mosque; 
        $import = new MosqueSpecialSalahEidulFitriTimesImport($data);
        Excel::import($import, $file);

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque Salah Eid-ul Fitri Times data';
        return response($res, 200);
    }



    


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/importEidulFitriMultiple",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Import Salah Eid-ul Fitri Multiple data in Mosque Spesial Salah Times response",
     *     description="Import Salah Eid-ul Fitri Multiple data in Mosque Spesial Salah Times <br>
        <p>1. File Excel Salah Eid-ul Fitri data extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importEidulFitriMultipleMosqueSpesialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Fitri .xlsx or .xls or .csv) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Fitri .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[2]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Fitri .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importEidulFitriMultiple(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
            'file'=>'required',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $file = $request->input('file');

        $mosque = Mosque::find($id_mosque);
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

        foreach($request->file as $file){
            $mimes = $file->getClientOriginalExtension();
            if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
                $res['message'] = "Failed";
                $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
                return response($res, 422);
            }          
            
            ////////////////////////////////////////////////
            ///////////////////////////////////////////////
            ///Check Delimiter Separator IF Using CSV
            if(in_array($mimes, array('csv','Csv','CSV'))){
                $delimiters = [",", ";", "\t"];
                $checkLines = 3;
                $default =",";
    
                $fileObject = new \SplFileObject($file);
                $results = [];
                $counter = 0;
                while ($fileObject->valid() && $counter <= $checkLines) {
                    $line = $fileObject->fgets();
                    foreach ($delimiters as $delimiter) {
                        $fields = explode($delimiter, $line);
                        $totalFields = count($fields);
                        if ($totalFields > 1) {
                            if (!empty($results[$delimiter])) {
                                $results[$delimiter] += $totalFields;
                            } else {
                                $results[$delimiter] = $totalFields;
                            }
                        }
                    }
                    $counter++;
                }
                if (!empty($results)) {
                    $results = array_keys($results, max($results));
    
                    // return $results[0];
                    if($results[0] !== ","){
                        $res['status_code'] = 406;
                        $res['success'] = false;
                        $res['detail'] = "Error, File CSV Must Use Comma (,) Delimiter Separator.";
    
                        return response($res, 200);
                    }
                }
            }

            $data = $id_mosque; 
            $import = new MosqueSpecialSalahEidulFitriTimesImport($data);
            Excel::import($import, $file);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque Salah Eid-ul Fitri Times data';
        return response($res, 200);
    }


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/importEidulAdha",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Import Salah Eid-ul Adha data in Mosque Spesial Salah Times response",
     *     description="Import Salah Eid-ul Adha data in Mosque Spesial Salah Times <br>
        <p>1. File Excel Salah Eid-ul Adha data extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importEidulAdhaMosqueSpesialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Adha Times .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importEidulAdha(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric|min:1',
            'file'=>'required',
        ]);  
        $file = $request->file;
        $id_mosque = $request->input('id_mosque');
        
        $mosque = Mosque::find($id_mosque);
        
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

        $mimes = $file->getClientOriginalExtension();
        if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
            $res['message'] = "Failed";
            $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
            return response($res, 422);
        }        
        
        ////////////////////////////////////////////////
        ///////////////////////////////////////////////
        ///Check Delimiter Separator IF Using CSV
        if(in_array($mimes, array('csv','Csv','CSV'))){
            $delimiters = [",", ";", "\t"];
            $checkLines = 3;
            $default =",";

            $fileObject = new \SplFileObject($file);
            $results = [];
            $counter = 0;
            while ($fileObject->valid() && $counter <= $checkLines) {
                $line = $fileObject->fgets();
                foreach ($delimiters as $delimiter) {
                    $fields = explode($delimiter, $line);
                    $totalFields = count($fields);
                    if ($totalFields > 1) {
                        if (!empty($results[$delimiter])) {
                            $results[$delimiter] += $totalFields;
                        } else {
                            $results[$delimiter] = $totalFields;
                        }
                    }
                }
                $counter++;
            }
            if (!empty($results)) {
                $results = array_keys($results, max($results));

                // return $results[0];
                if($results[0] !== ","){
                    $res['status_code'] = 406;
                    $res['success'] = false;
                    $res['detail'] = "Error, File CSV Must Use Comma (,) Delimiter Separator.";

                    return response($res, 200);
                }
            }
        }
        ///END OF Check Delimiter Separator IF Using CSV
        ////////////////////////////////////////////////
        ///////////////////////////////////////////////

        $data = $id_mosque; 
        $import = new MosqueSpecialSalahEidulAdhaTimesImport($data);
        Excel::import($import, $file);

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque Salah Eid-ul Adha Times data';
        return response($res, 200);
    }


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-specialsalah-times/importEidulAdhaMultiple",
     *     tags={"Mosque Special Salah Times"},
     *     summary="Import Salah Eid-ul Adha data in Mosque Spesial Salah Times response",
     *     description="Import Salah Eid-ul Adha data in Mosque Spesial Salah Times <br>
        <p>1. File Excel Salah Eid-ul Adha data extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importEidulAdhaMultipleMosqueSpesialSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Adha .xlsx or .xls or .csv) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Adha .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[2]",
     *     in="formData",
     *     description="file (File Excel Mosque Salah Eid-ul Adha .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importEidulAdhaMultiple(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
            'file'=>'required',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $file = $request->input('file');

        $mosque = Mosque::find($id_mosque);
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

        foreach($request->file as $file){
            $mimes = $file->getClientOriginalExtension();
            if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
                $res['message'] = "Failed";
                $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
                return response($res, 422);
            }          
            
            ////////////////////////////////////////////////
            ///////////////////////////////////////////////
            ///Check Delimiter Separator IF Using CSV
            if(in_array($mimes, array('csv','Csv','CSV'))){
                $delimiters = [",", ";", "\t"];
                $checkLines = 3;
                $default =",";
    
                $fileObject = new \SplFileObject($file);
                $results = [];
                $counter = 0;
                while ($fileObject->valid() && $counter <= $checkLines) {
                    $line = $fileObject->fgets();
                    foreach ($delimiters as $delimiter) {
                        $fields = explode($delimiter, $line);
                        $totalFields = count($fields);
                        if ($totalFields > 1) {
                            if (!empty($results[$delimiter])) {
                                $results[$delimiter] += $totalFields;
                            } else {
                                $results[$delimiter] = $totalFields;
                            }
                        }
                    }
                    $counter++;
                }
                if (!empty($results)) {
                    $results = array_keys($results, max($results));
    
                    // return $results[0];
                    if($results[0] !== ","){
                        $res['status_code'] = 406;
                        $res['success'] = false;
                        $res['detail'] = "Error, File CSV Must Use Comma (,) Delimiter Separator.";
    
                        return response($res, 200);
                    }
                }
            }

            $data = $id_mosque; 
            $import = new MosqueSpecialSalahEidulAdhaTimesImport($data);
            Excel::import($import, $file);
        }
        
        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque Salah Eid-ul Adha Times data';
        return response($res, 200);
    }
}
