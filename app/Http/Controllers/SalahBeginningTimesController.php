<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\SalahBeginningTimes;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

use App\Imports\SalahBeginningTimesImport;
use Maatwebsite\Excel\Facades\Excel;

class SalahBeginningTimesController extends Controller
{
    //
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/storeUpdate",
     *     tags={"Salah Beginning Times"},
     *     summary="Storing new data / Update data Salah Beginning Times response",
     *     description="Storing new data / Update data Salah Beginning Times response",
     *     operationId="storeUpdateSalahBeginningTimes",
     * 
     *  @SWG\Parameter(
     *     name="date_salah_beginning_times",
     *     in="formData",
     *     description="date_salah_beginning_times (YYYY-mm-dd ,ex : 2021-11-15) [default : now] ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="type_salah_beginning_times",
     *     in="formData",
     *     description="type_salah_beginning_times (1 = Fajr / Suhur || 2 = Sunrise || 3 = Zuhr || 4 = Asr 1st || 5 = Asr 2nd || 6 = Maghrib || 7 = Isha ) ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="time_salah_beginning_times",
     *     in="formData",
     *     description="time_salah_beginning_times (hh:ii ,ex : 09:52) ...",
     *     required=true,
     *     type="string"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function storeUpdate(Request $request){
        $this->validate($request,[
            'date_salah_beginning_times'=>'nullable|date|date_format:Y-m-d',
            'type_salah_beginning_times'=>'required|numeric',
            'time_salah_beginning_times'=>'required|date_format:H:i',
        ]);  

        $date_salah_beginning_times = $request->input('date_salah_beginning_times') ?? Carbon::now()->format('Y-m-d');
        $type_salah_beginning_times = $request->input('type_salah_beginning_times');
        $time_salah_beginning_times = $request->input('time_salah_beginning_times').':00';

        
        $data = SalahBeginningTimes::firstOrNew(['date_salah_beginning_times' => $date_salah_beginning_times,
        'type_salah_beginning_times' => $type_salah_beginning_times]);

        $data->date_salah_beginning_times = $date_salah_beginning_times;
        $data->type_salah_beginning_times = $type_salah_beginning_times;
        $data->time_salah_beginning_times = $time_salah_beginning_times;
        $data->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing / updating Salah Beginning Times data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    

    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Delete Undelete
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/deleteUndelete",
     *     tags={"Salah Beginning Times"},
     *     summary="Deleting data Salah Beginning Times response",
     *     description="Deleting data Salah Beginning Times response",
     *     operationId="deleteUndeleteSalahBeginningTimes",
     * 
     *  @SWG\Parameter(
     *     name="id_salah_beginning_times",
     *     in="formData",
     *     description="ID Salah Beginning Times ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="delete",
     *     in="formData",
     *     description="Delete (1 = Delete Data || 0 = Undelete Data) [Default : 1 / Delete] ...",
     *     required=false,
     *     type="integer"
     *   ), 
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function deleteUndelete(Request $request){
        $this->validate($request,[
            'id_salah_beginning_times'=>'required|numeric',
            'delete'=>'numeric',
        ]);  

        $id_salah_beginning_times = $request->input('id_salah_beginning_times');
        $delete = $request->input('delete') ?? 1;

        $data = SalahBeginningTimes::find($id_salah_beginning_times);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Salah Beginning Times for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = $delete;
        $data = $data->save();

        $data = SalahBeginningTimes::find($id_salah_beginning_times);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            if($delete == 1){
                $res['message'] = 'Success Deleting Salah Beginning Times data';
            }else{
                $res['message'] = 'Success Undelete Salah Beginning Times data';
            }
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/salah-beginning-times/show",
     *     tags={"Salah Beginning Times"},
     *     summary="Show all data Salah Beginning Times (data not deleted / soft_delete = 0) response",
     *     description="Show all data Salah Beginning Times (data not deleted / soft_delete = 0) response",
     *     operationId="showSalahBeginningTimes",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){ 
        $data = SalahBeginningTimes::where('soft_delete', 0)
        ->orderBy('id_salah_beginning_times', 'DESC')
        ->get();

        if(!$data->isEmpty()){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['total_data'] = $data->count();
            $res['data'] = $data;
            $res['message'] = 'Success return Salah Beginning Times data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Salah Beginning Times data is Null';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
   

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/showById",
     *     tags={"Salah Beginning Times"},
     *     summary="Show by id data Salah Beginning Times response",
     *     description="Show by id data Salah Beginning Times response",
     *     operationId="showByIdSalahBeginningTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_salah_beginning_times",
     *     in="formData",
     *     description="ID Salah Beginning Times ...",
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
            'id_salah_beginning_times'=>'required|numeric',
        ]);  

        $id_salah_beginning_times = $request->input('id_salah_beginning_times');
        
        
        $data = SalahBeginningTimes::where('id_salah_beginning_times', $id_salah_beginning_times)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Salah Beginning Times for that ID not found.";

            return response($res, 200);
        }
        
        $data = SalahBeginningTimes::where('id_salah_beginning_times', $id_salah_beginning_times)
        ->get();

        if(!$data->isEmpty()){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Salah Beginning Times data';
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Salah Beginning Times data is Null';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/showByFilter",
     *     tags={"Salah Beginning Times"},
     *     summary="Show by filter data Salah Beginning Times response",
     *     description="Show by filter data Salah Beginning Times response",
     *     operationId="showByFilterSalahBeginningTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_salah_beginning_times",
     *     in="formData",
     *     description="ID Salah Times ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="date_salah_beginning_times",
     *     in="formData",
     *     description="date_salah_beginning_times (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="year_salah_beginning_times",
     *     in="formData",
     *     description="Year (YYYY ,ex : 2021) ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="type_salah_beginning_times",
     *     in="formData",
     *     description="type_salah_beginning_times (1 = Fajr / Suhur || 2 = Sunrise || 3 = Zuhr || 4 = Asr 1st || 5 = Asr 2nd || 6 = Maghrib || 7 = Isha ) ...",
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
        
        $id_salah_beginning_times = $request->input('id_salah_beginning_times');
        $date_salah_beginning_times = $request->input('date_salah_beginning_times');
        $year_salah_beginning_times = $request->input('year_salah_beginning_times');
        $type_salah_beginning_times = $request->input('type_salah_beginning_times');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = SalahBeginningTimes::query()
        ->when($request->has('id_salah_beginning_times'), function ($query) use ($id_salah_beginning_times) {
            $query->where('salah_beginning_times.id_salah_beginning_times',$id_salah_beginning_times);
        })
        ->when($request->has('date_salah_beginning_times'), function ($query) use ($date_salah_beginning_times) {
            $query->where('salah_beginning_times.date_salah_beginning_times',$date_salah_beginning_times);
        })
        ->when($request->has('year_salah_beginning_times'), function ($query) use ($year_salah_beginning_times) {
            $query->whereYear('salah_beginning_times.date_salah_beginning_times',$year_salah_beginning_times);
        })
        ->when($request->has('type_salah_beginning_times'), function ($query) use ($type_salah_beginning_times) {
            $query->where('salah_beginning_times.type_salah_beginning_times',$type_salah_beginning_times);
        })
        ->where('salah_beginning_times.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('salah_beginning_times.id_salah_beginning_times '.$order)
        ->distinct('salah_beginning_times.id_salah_beginning_times')
        ->get([
            'salah_beginning_times.id_salah_beginning_times',
            'salah_beginning_times.date_salah_beginning_times',
            'salah_beginning_times.type_salah_beginning_times',
            'salah_beginning_times.time_salah_beginning_times',
            'salah_beginning_times.soft_delete',
            'salah_beginning_times.created_at',
            'salah_beginning_times.updated_at'
        ]);

        if(!$data->isEmpty()){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Salah Beginning Times data';
            $res['total_data'] = $data->count();
            $res['data'] = $data;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Salah Beginning Times data is Null / Not Found';
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/ShowCSV",
     *     tags={"Salah Beginning Times"},
     *     summary="Show data Salah Beginning Times in CSV FILE response",
     *     description="Show data Salah Beginning Times in CSV FILE response",
     *     operationId="ShowCSVSalahBeginningTimes",
     * 
     *   @SWG\Parameter(
     *     name="year",
     *     in="formData",
     *     description="Year [Default = this year]",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="month",
     *     in="formData",
     *     description="Month (13 = All Month)[Default = this month]",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function ShowCSV(Request $request){ 
        ini_set('auto_detect_line_endings', TRUE);/// (PHP's detection of line endings) write at the top.

        $year = $request->input('year') ??Carbon::now()->format('Y');
        $month = $request->input('month') ?? Carbon::now()->format('m');

        // $CSVfile = 'assets/salah beginning times/Feb-2022.csv';

                
        if($month == 1){
            $month= ['Jan'];
        }else if($month == 2){
            $month = ['Feb'];
        }else if($month == 3){
            $month = ['Mar'];
        }else if($month == 4){
            $month = ['Apr'];
        }else if($month == 5){
            $month = ['May'];
        }else if($month == 6){
            $month = ['Jun'];
        }else if($month == 7){
            $month = ['Jul'];
        }else if($month == 8){
            $month = ['Aug'];
        }else if($month == 9){
            $month = ['Sep'];
        }else if($month == 10){
            $month = ['Oct'];
        }else if($month == 11){
            $month = ['Nov'];
        }else if($month == 12){
            $month = ['Dec'];
        }else if($month == 13){
            $month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        }

        $res['year'] = $year;
        $res['month'] = $month;
        $res['Rule'][0] = "Rules need to Follow so that there are no errors in data collection";
        $res['Rule'][1] = "1. Folder Mosque MUST have the same name as Mosque Name in Database";
        $res['Rule'][2] = "2. CSV Data MUST be seperated by Comma ,";
        $res['Rule'][3] = "3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file";

        $csv = array();
        foreach($month as $m){
        $CSVfile = 'assets/Salah Beginning Times/'.$m.'-'.$year.'.csv';
            ////VALIDATION FILE CSV EXISTS OR NOT 
            if(!file_exists($CSVfile)){
                $csv[] = Null;
                continue;
            }
            
            $csvrows = array_map('str_getcsv', file($CSVfile));
            $csvheader = array_shift($csvrows);
            foreach ($csvrows as $row) {
            $csv[$row[1]] = array_combine($csvheader, $row);
            }
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $csv;
        return response($res, 200);
    }




    
    ////
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // Import
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/import",
     *     tags={"Salah Beginning Times"},
     *     summary="Import new data Salah Beginning Times response",
     *     description="Import new data Salah Beginning Times <br>
        <p>1. File Excel Salah Beginning Times extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importSalahBeginningTimes",
     * 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Salah Beginning Times .xlsx or .xls) ...",
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
        $file = $request->file('file');
        $validator = Validator::make(
            [
                'file'      => $file,
                'extension' => strtolower($file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:xlsx,xls,csv',
            ]
          );
          if ($validator->fails()){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['message'] = 'Failed, File Not Uploaded or File Extension Not (.xlsx / .xls / .csv) ';
            return response($res, 200);
          }

        $data = null; 
        $import = new SalahBeginningTimesImport($data);
        Excel::import($import, $file);

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Salah Beginning Times data';
        return response($res, 200);
    }







    /**
     * @SWG\Post(
     *   path="/salah-beginning-times/importMultiple",
     *     tags={"Salah Beginning Times"},
     *     summary="Import Multiple new data Salah Beginning Times response",
     *     description="Import Multiple new data Salah Beginning Times <br>
        <p>1. File Excel Salah Beginning Times extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importMultipleSalahBeginningTimes",
     * 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Salah Beginning Times .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Salah Beginning Times .xlsx or .xls or .csv) ...",
     *     required=true,
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
                $res['detail'] = "Files Salah Beginning Times Extension Must Be (xlsx/xls/csv)";
                return response($res, 422);
            }          

            $data = null; 
            $import = new SalahBeginningTimesImport($data);
            Excel::import($import, $file);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Salah Beginning Times data';
        return response($res, 200);
    }
}
