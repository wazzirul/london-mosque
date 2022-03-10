<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

use App\Model\Borough;
use App\Model\Districts;

use App\Imports\BoroughImport;
use Maatwebsite\Excel\Facades\Excel;

class BoroughController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/store",
     *     tags={"Borough"},
     *     summary="Storing new data borough response",
     *     description="Storing new data borough response",
     *     operationId="storeBorough",
     * 
     *  @SWG\Parameter(
     *     name="name_borough",
     *     in="formData",
     *     description="Name Borough ...",
     *     required=true,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Latitude ...",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude ...",
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
            'name_borough'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $name_borough = $request->input('name_borough');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $data = Borough::create([
            'name_borough' => $name_borough,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Storing New Borough Data.";
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/update",
     *     tags={"Borough"},
     *     summary="Updating data borough response",
     *     description="Updating data borough response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="name_borough",
     *     in="formData",
     *     description="Name Borough ...",
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
     *  @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude ...",
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
            'id_borough'=>'required',
            'latitude'=>'nullable',
            'longitude'=>'nullable',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }

        $name_borough = $request->input('name_borough') ?? null;
        $latitude = $request->input('latitude') ?? null;
        $longitude = $request->input('longitude') ?? null;

        if($name_borough !== null){
            $borough->name_borough = $name_borough;
        }

        if($latitude !== null){
            $borough->latitude = $latitude;
        }

        if($longitude !== null){
            $borough->longitude = $longitude;
        }

        $data = $borough->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Updating Borough Data.";
            $res['data'] = $borough;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/delete",
     *     tags={"Borough"},
     *     summary="Deleting data borough response",
     *     description="Deleting data borough response",
     *     operationId="deleteBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
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
            'id_borough'=>'required|numeric',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }

        $borough->soft_delete = 1;

        $data = $borough->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Deleting Borough Data.";
            $res['data'] = $borough;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/unDelete",
     *     tags={"Borough"},
     *     summary="Restoring data in deleted section borough response",
     *     description="Restoring data in deleted section borough response",
     *     operationId="unDeleteBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
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
            'id_borough'=>'required|numeric',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }else if($borough->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough is active.";

            return response($res, 200);
        }

        $borough->soft_delete = 0;

        $data = $borough->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Restoring / Undelete Borough Data.";
            $res['data'] = $borough;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/borough/show",
     *     tags={"Borough"},
     *     summary="Show all data borough response",
     *     description="Show all data borough response",
     *     operationId="showBorough",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $borough = Borough::where('soft_delete', 0)
            ->orderBy('id_borough', 'DESC')
            ->get();

        if(count($borough) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Returning Borough Data.";
            $res['totaldata'] = $borough->count();
            $res['data'] = $borough;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $borough;

            return response($res, 200);
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW DELETED
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/borough/showDeleted",
     *     tags={"Borough"},
     *     summary="Show Deleted all data borough response",
     *     description="Show Deleted all data borough response",
     *     operationId="showDeletedBorough",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showDeleted(){
        $borough = Borough::where('soft_delete', 1)
            ->orderBy('id_borough', 'DESC')
            ->get();

        if(count($borough) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Returning Borough Data.";
            $res['totaldata'] = $borough->count();
            $res['data'] = $borough;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $borough;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/showById",
     *     tags={"Borough"},
     *     summary="Show data borough by ID borough response",
     *     description="Show data borough by ID borough response",
     *     operationId="showByIdBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
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
            'id_borough'=>'required|numeric',
        ]);  

        $id_borough = $request->input('id_borough');
        $borough = Borough::where('id_borough', $id_borough)
            ->first();

        if($borough === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Borough for that ID not found.";

            return response($res, 200);
        }else if($borough->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Borough was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $districts = Districts::where('id_borough', $id_borough)
            ->where('soft_delete', 0)
            ->get();

        $borough->districts = $districts;

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['detail'] = "Success Returning Borough Data.";
        $res['totaldata'] = $borough->count();
        $res['data'] = $borough;

        return response($res, 200);
    }



    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/borough/showByFilter",
     *     tags={"Borough"},
     *     summary="Show data borough by filter borough response",
     *     description="Show data borough by filter borough response",
     *     operationId="showByFilterBorough",
     * 
     *   @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="name_borough",
     *     in="formData",
     *     description="Name Borough ...",
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
        $id_borough = $request->input('id_borough');
        $name_borough = $request->input('name_borough');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $borough = Borough::query()
        ->when($request->has('id_borough'), function ($query) use ($id_borough) {
            $query->where('borough.id_borough',$id_borough);
        })
        ->when($request->has('name_borough'), function ($query) use ($name_borough) {
            $query->where('borough.name_borough','like','%'.$name_borough.'%');
        })
        ->where('borough.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('borough.id_borough '.$order)
        ->distinct('borough.id_borough')
        ->get([
            'borough.id_borough',
            'borough.name_borough',
            'borough.latitude',
            'borough.longitude',
            'borough.soft_delete',
            'borough.created_at as created_at',
            'borough.updated_at as updated_at'
        ]);

        foreach($borough as $index => $d){
            
            $districts = Districts::where('id_borough', $d->id_borough)
            ->where('soft_delete', 0)
            ->get();
    
            $borough[$index]->districts = $districts  ?? null;
        }
        if(count($borough) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Borough data';
            $res['totaldata'] = $borough->count();
            $res['data'] = $borough;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $borough;

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
     *   path="/borough/import",
     *     tags={"Borough"},
     *     summary="Import new data Borough response",
     *     description="Import new data Borough <br>
        <p>1. File Excel Borough extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importBorough",
     * 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Borough .xlsx or .xls) ...",
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
        
        $this->validate($request,[
            'file'=>'required',
        ]);  
        $file = $request->file;

        $mimes = $file->getClientOriginalExtension();
        if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
            $res['message'] = "Failed";
            $res['detail'] = "Files Borough Extension Must Be (xlsx/xls/csv)";
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
        $import = new BoroughImport($data);
        Excel::import($import, $file);
        

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Borough data';
        return response($res, 200);
    }







    /**
     * @SWG\Post(
     *   path="/borough/importMultiple",
     *     tags={"Borough"},
     *     summary="Import Multiple new data Borough response",
     *     description="Import Multiple new data Borough <br>
        <p>1. File Excel Borough extension must be .xlsx or .xls or .csv</p>
        <p>2. CSV Data MUST be seperated by Comma ,</p>
        <p>3. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importMultipleBorough",
     * 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Borough .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Borough .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[2]",
     *     in="formData",
     *     description="file (File Excel Borough .xlsx or .xls or .csv) ...",
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
        $file = $request->file;
        foreach($request->file as $file){
            $mimes = $file->getClientOriginalExtension();
            if(!in_array($mimes, array('xlsx', 'xls', 'csv', 'Xslx', 'Xls', 'Csv'))) {
                $res['message'] = "Failed";
                $res['detail'] = "Files Borough Extension Must Be (xlsx/xls/csv)";
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
            $import = new BoroughImport($data);
            Excel::import($import, $file);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Borough data';
        return response($res, 200);
    }
}
