<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\RamadhanSalahTimes;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

use App\Imports\RamadhanSalahTimesImport;
use Maatwebsite\Excel\Facades\Excel;

class RamadhanSalahTimesController extends Controller
{
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/ramadhan-salah-times/store",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Storing new data Ramadhan Salah Times response",
     *     description="Storing new data Ramadhan Salah Times response",
     *     operationId="storeRamadhanSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="date_ramadhan_salah_times",
     *     in="formData",
     *     description="date_ramadhan_salah_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="type_ramadhan_salah_times",
     *     in="formData",
     *     description="type_ramadhan_salah_times (1 = Suhur || 2 = Zuhr || 3 = Asr || 4 = Iftar || 5 = Isha) ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_ramadhan_salah_times",
     *     in="formData",
     *     description="time_ramadhan_salah_times (hh:ii ,ex : 09:52) ...",
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
            'date_ramadhan_salah_times'=>'nullable|date|date_format:Y-m-d',
            'type_ramadhan_salah_times'=>'required|numeric',
            'time_ramadhan_salah_times'=>'required|date_format:H:i',
        ]);  

        $date_ramadhan_salah_times = $request->input('date_ramadhan_salah_times') ?? Carbon::now()->format('Y-m-d');
        $type_ramadhan_salah_times = $request->input('type_ramadhan_salah_times');
        $time_ramadhan_salah_times = $request->input('time_ramadhan_salah_times').':00';

        $data = RamadhanSalahTimes::create([
            'date_ramadhan_salah_times' => $date_ramadhan_salah_times,
            'type_ramadhan_salah_times' => $type_ramadhan_salah_times,
            'time_ramadhan_salah_times' => $time_ramadhan_salah_times,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Ramadhan Salah Times data';
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
     *   path="/ramadhan-salah-times/update",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Updating data Ramadhan Salah Times response",
     *     description="Updating data Ramadhan Salah Times response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateRamadhanSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_ramadhan_salah_times",
     *     in="formData",
     *     description="ID Ramadhan Salah Times ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="date_ramadhan_salah_times",
     *     in="formData",
     *     description="date_ramadhan_salah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="type_ramadhan_salah_times",
     *     in="formData",
     *     description="type_ramadhan_salah_times (1 = Suhur || 2 = Zuhr || 3 = Asr || 4 = Iftar || 5 = Isha) ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_ramadhan_salah_times",
     *     in="formData",
     *     description="time_ramadhan_salah_times (hh:ii ,ex : 09:52) ...",
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
            'id_ramadhan_salah_times'=>'required|numeric',
            'date_ramadhan_salah_times'=>'nullable|date|date_format:Y-m-d',
            'type_ramadhan_salah_times'=>'nullable|numeric',
            'time_ramadhan_salah_times'=>'nullable|date_format:H:i',
        ]);  

        $id_ramadhan_salah_times = $request->input('id_ramadhan_salah_times');
        $date_ramadhan_salah_times = $request->input('date_ramadhan_salah_times') ?? null;
        $type_ramadhan_salah_times = $request->input('type_ramadhan_salah_times') ?? null;
        $time_ramadhan_salah_times = $request->input('time_ramadhan_salah_times') ?? null;

        $data = RamadhanSalahTimes::find($id_ramadhan_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Ramadhan Salah Times for that ID not found.";

            return response($res, 200);
        }else if($data->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Ramadhan Salah Times was deleted. Please contact customer service.";

            return response($res, 200);
        }

        if($date_ramadhan_salah_times !== null){
            $data->date_ramadhan_salah_times = $date_ramadhan_salah_times;
        }

        if($type_ramadhan_salah_times !== null){
            $data->type_ramadhan_salah_times = $type_ramadhan_salah_times;
        }

        if($time_ramadhan_salah_times !== null){
            $data->time_ramadhan_salah_times = $time_ramadhan_salah_times.':00';
        }

        $data = $data->save();

        $data = RamadhanSalahTimes::find($id_ramadhan_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Ramadhan Salah Times data';
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
     *   path="/ramadhan-salah-times/delete",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Deleting data Ramadhan Salah Times response",
     *     description="Deleting data Ramadhan Salah Times response",
     *     operationId="deleteRamadhanSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_ramadhan_salah_times",
     *     in="formData",
     *     description="ID Ramadhan Salah Times ...",
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
            'id_ramadhan_salah_times'=>'required|numeric',
        ]);  

        $id_ramadhan_salah_times = $request->input('id_ramadhan_salah_times');

        $data = RamadhanSalahTimes::find($id_ramadhan_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Ramadhan Salah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 1;
        $data = $data->save();

        $data = RamadhanSalahTimes::find($id_ramadhan_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Ramadhan Salah Times data';
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
     *   path="/ramadhan-salah-times/undelete",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Restoring / undelete data Ramadhan Salah Times response",
     *     description="Restoring / undelete data Ramadhan Salah Times response",
     *     operationId="undeleteRamadhanSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_ramadhan_salah_times",
     *     in="formData",
     *     description="ID Ramadhan Salah Times ...",
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
            'id_ramadhan_salah_times'=>'required|numeric',
        ]);  

        $id_ramadhan_salah_times = $request->input('id_ramadhan_salah_times');

        $data = RamadhanSalahTimes::find($id_ramadhan_salah_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Ramadhan Salah Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 0;
        $data = $data->save();

        $data = RamadhanSalahTimes::find($id_ramadhan_salah_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring / undelete Ramadhan Salah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/ramadhan-salah-times/show",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Show all data Ramadhan Salah Times response",
     *     description="Show all data Ramadhan Salah Times response",
     *     operationId="showRamadhanSalahTimes",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){ 
        $data = RamadhanSalahTimes::where('soft_delete', 0)
        ->orderBy('id_ramadhan_salah_times', 'DESC')
        ->get();

        if(!$data->isEmpty()){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $data;
            $res['message'] = 'Success return Ramadhan Salah Times data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Ramadhan Salah Times data is Null';
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/ramadhan-salah-times/showById",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Show by id data Ramadhan salah times response",
     *     description="Show by id data Ramadhan salah times response",
     *     operationId="showByIdRamadhanSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_ramadhan_salah_times",
     *     in="formData",
     *     description="ID Ramadhan Salah Times ...",
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
            'id_ramadhan_salah_times'=>'required|numeric',
        ]);  

        $id_ramadhan_salah_times = $request->input('id_ramadhan_salah_times');
        
        
        $data = RamadhanSalahTimes::where('id_ramadhan_salah_times', $id_ramadhan_salah_times)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Ramadhan Salah Times for that ID not found.";

            return response($res, 200);
        }
        
        $data = RamadhanSalahTimes::where('id_ramadhan_salah_times', $id_ramadhan_salah_times)
        ->get();

        if(!$data->isEmpty()){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Ramadhan Salah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Ramadhan Salah Times data is Null';
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/ramadhan-salah-times/showByFilter",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Show by filter data Ramadhan salah times response",
     *     description="Show by filter data Ramadhan salah times response",
     *     operationId="showByFilterSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_ramadhan_salah_times",
     *     in="formData",
     *     description="ID Salah Times ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="date_ramadhan_salah_times",
     *     in="formData",
     *     description="date_ramadhan_salah_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="year_ramadhan_salah_times",
     *     in="formData",
     *     description="Year (YYYY ,ex : 2021) ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="type_ramadhan_salah_times",
     *     in="formData",
     *     description="type_ramadhan_salah_times (1 = Suhur || 2 = Zuhr || 3 = Asr || 4 = Iftar || 5 = Isha) ...",
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
        
        $id_ramadhan_salah_times = $request->input('id_ramadhan_salah_times');
        $date_ramadhan_salah_times = $request->input('date_ramadhan_salah_times');
        $year_ramadhan_salah_times = $request->input('year_ramadhan_salah_times');
        $type_ramadhan_salah_times = $request->input('type_ramadhan_salah_times');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = RamadhanSalahTimes::query()
        ->when($request->has('id_ramadhan_salah_times'), function ($query) use ($id_ramadhan_salah_times) {
            $query->where('ramadhan_salah_times.id_ramadhan_salah_times',$id_ramadhan_salah_times);
        })
        ->when($request->has('date_ramadhan_salah_times'), function ($query) use ($date_ramadhan_salah_times) {
            $query->where('ramadhan_salah_times.date_ramadhan_salah_times',$date_ramadhan_salah_times);
        })
        ->when($request->has('year_ramadhan_salah_times'), function ($query) use ($year_ramadhan_salah_times) {
            $query->whereYear('ramadhan_salah_times.date_ramadhan_salah_times',$year_ramadhan_salah_times);
        })
        ->when($request->has('type_ramadhan_salah_times'), function ($query) use ($type_ramadhan_salah_times) {
            $query->where('ramadhan_salah_times.type_ramadhan_salah_times',$type_ramadhan_salah_times);
        })
        ->where('ramadhan_salah_times.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('ramadhan_salah_times.id_ramadhan_salah_times '.$order)
        ->distinct('ramadhan_salah_times.id_ramadhan_salah_times')
        ->get([
            'ramadhan_salah_times.id_ramadhan_salah_times',
            'ramadhan_salah_times.date_ramadhan_salah_times',
            'ramadhan_salah_times.type_ramadhan_salah_times',
            'ramadhan_salah_times.time_ramadhan_salah_times',
            'ramadhan_salah_times.soft_delete',
            'ramadhan_salah_times.created_at',
            'ramadhan_salah_times.updated_at'
        ]);

        if(!$data->isEmpty()){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Ramadhan Salah Times data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Ramadhan Salah Times data is Null';
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
     *   path="/ramadhan-salah-times/import",
     *     tags={"Ramadhan Salah Times"},
     *     summary="Import new data Ramadhan Salah Times response",
     *     description="Import new data Ramadhan Salah Times <br>
        <p>1. File Excel Ramadhan Salah Times extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importRamadhanSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Ramadhan Salah/Prayer Times .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function import(Request $request){
        $file = $request->file;

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

        $data = null; 
        $import = new RamadhanSalahTimesImport($data);
        Excel::import($import, $file);

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Ramadhan Salah Times data';
        return response($res, 200);
    }


    




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/ramadhan-salah-times/importMultiple",
     *     tags={"Ramadhan Salah Times"},
     *     summary="import Multiple new data Ramadhan Salah Times response",
     *     description="import Multiple new data Ramadhan Salah Times <br>
        <p>1. File Excel Ramadhan Salah Times extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importMultipleRamadhanSalahTimes",
     * 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Ramadhan Salah Times .xlsx or .xls or .csv) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Ramadhan Salah Times .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[2]",
     *     in="formData",
     *     description="file (File Excel Ramadhan Salah Times .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function importMultiple(Request $request){
        $this->validate($request,[
            'file'=>'required',
        ]);  

        $file = $request->input('file');
        
        foreach($request->file as $file){
            $mimes = $file->getClientOriginalExtension();
            if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
                $res['message'] = "Failed";
                $res['detail'] = "Files Ramadhan Salah Times Extension Must Be (xlsx/xls/csv)";
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


            $data = null; 
            $import = new RamadhanSalahTimesImport($data);
            Excel::import($import, $file);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Ramadhan Salah Times data';
        return response($res, 200);
    }
}
