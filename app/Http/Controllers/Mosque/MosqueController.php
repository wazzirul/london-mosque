<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Users;
use App\Model\Borough;
use App\Model\Districts;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueSalahTime;
use App\Model\Mosque\MosqueSpecialSalahTime;
use App\Model\Mosque\MosqueFacilities;
use App\Model\Mosque\MosqueFavorite;
use App\Model\Mosque\MosqueImage;
use App\Model\Mosque\MosqueReview;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

use App\Imports\MosqueImport;
use Maatwebsite\Excel\Facades\Excel;

class MosqueController extends Controller
{
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/store",
     *     tags={"Mosque"},
     *     summary="Storing new data Mosque response",
     *     description="Storing new data Mosque response",
     *     operationId="storeMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_google_place",
     *     in="formData",
     *     description="ID Mosque Google Place (Google Place / Maps ID) ...",
     *     required=false,
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
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="slug_mosque",
     *     in="formData",
     *     description="Slug Mosque (Unique) ...",
     *     required=true,
     *     type="string"
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
     *     name="url",
     *     in="formData",
     *     description="URL Mosque ...",
     *     required=false,
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
            'id_mosque_google_place'=>'nullable',
            'id_borough'=>'required|numeric',
            'id_district'=>'nullable|numeric',
            'slug_mosque'=>'required|unique:mosque',
            'name_mosque'=>'required',
            'address'=>'required',
            'url'=>'nullable',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $id_mosque_google_place = $request->input('id_mosque_google_place') ?? NULL;
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district') ?? null;
        $slug_mosque = $request->input('slug_mosque');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $url = $request->input('url');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

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

        // $district = Districts::where('id_district', $id_district)
        //     ->first();

        // if($district === null){
        //     $res['status_code'] = 406;
        //     $res['success'] = false;
        //     $res['detail'] = "Data District for that ID not found.";

        //     return response($res, 200);
        // }else if($district->soft_delete === 1){
        //     $res['status_code'] = 406;
        //     $res['success'] = false;
        //     $res['detail'] = "District was deleted. Please contact customer service.";

        //     return response($res, 200);
        // }

        $data = Mosque::create([
            'id_mosque_google_place' => $id_mosque_google_place,
            'id_borough' => $id_borough,
            // 'id_district' => $id_district,
            'slug_mosque' => $slug_mosque,
            'name_mosque' => $name_mosque,
            'address' => $address,
            'url' => $url,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }






    
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE UPDATE UPDATE UPDATE UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/update",
     *     tags={"Mosque"},
     *     summary="Updating data Mosque response",
     *     description="Updating data Mosque response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_mosque_google_place",
     *     in="formData",
     *     description="ID Mosque Google Place (Google Place / Maps ID) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_borough",
     *     in="formData",
     *     description="ID Borough ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="slug_mosque",
     *     in="formData",
     *     description="Slug Mosque (Unique) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="name_mosque",
     *     in="formData",
     *     description="Name Mosque ...",
     *     required=false,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="Address Mosque ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="url",
     *     in="formData",
     *     description="URL Mosque ...",
     *     required=false,
     *     type="string"
     *   ),  
     *  @SWG\Parameter(
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
            'id_mosque'=>'required|numeric',
            'id_mosque_google_place'=>'nullable',
            'id_borough'=>'numeric',
            'id_district'=>'numeric',
            'slug_mosque'=>'required',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $id_mosque_google_place = $request->input('id_mosque_google_place') ?? NULL;
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $slug_mosque = $request->input('slug_mosque');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $url = $request->input('url');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $data = Mosque::find($id_mosque);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        if($id_borough !== null){
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
            $data->id_borough = $id_borough;
        }

        // if($id_district !== null){
        //     $district = Districts::where('id_district', $id_district)
        //         ->first();
    
        //     if($district === null){
        //         $res['status_code'] = 406;
        //         $res['success'] = false;
        //         $res['detail'] = "Data District for that ID not found.";
    
        //         return response($res, 200);
        //     }else if($district->soft_delete === 1){
        //         $res['status_code'] = 406;
        //         $res['success'] = false;
        //         $res['detail'] = "District was deleted. Please contact customer service.";
    
        //         return response($res, 200);
        //     }
        //     $data->id_district = $id_district;
        // }

        if($id_mosque_google_place !== null){
            $data->id_mosque_google_place = $id_mosque_google_place;
        }

        if($slug_mosque !== null){
            $dataSlugMosque = Mosque::where('slug_mosque', $slug_mosque)->first();
            if($dataSlugMosque !== null){
                if($data->id_mosque !== $dataSlugMosque->id_mosque && $data->slug_mosque !== $dataSlugMosque->slug_mosque){
                    $res['status_code'] = 406;
                    $res['success'] = false;
                    $res['detail'] = "Slug Mosque already taken, Please use another slug mosque.";

                    return response($res, 200);
                }
            }

            $data->slug_mosque = $slug_mosque;
        }

        if($name_mosque !== null){
            $data->name_mosque = $name_mosque;
        }

        if($address !== null){
            $data->address = $address;
        }

        if($url !== null){
            $data->url = $url;
        }

        if($latitude !== null){
            $data->latitude = $latitude;
        }

        if($longitude !== null){
            $data->longitude = $longitude;
        }

        $data = $data->save();
        $data = Mosque::with('Borough','Districts')->find($id_mosque);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/delete",
     *     tags={"Mosque"},
     *     summary="Deleting data Mosque response",
     *     description="Deleting data Mosque response",
     *     operationId="deleteMosque",
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
    public function delete(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $mosque->soft_delete = 1;

        $mosque = $mosque->save();
        $data = Mosque::find($id_mosque);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/undelete",
     *     tags={"Mosque"},
     *     summary="Undelete / Restore data Mosque yang terhapus response",
     *     description="Undelete / Restore data Mosque yang terhapus response",
     *     operationId="undeleteMosque",
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
    public function undelete(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $mosque->soft_delete = 0;   

        $mosque = $mosque->save();
        $data = Mosque::find($id_mosque);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success restoring Mosque data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque/show",
     *     tags={"Mosque"},
     *     summary="Show all data mosque response",
     *     description="Show all data mosque response
           <br>*Salah Times using DATE NOW to save data from many return
           <br>*Jummah Times using DATE YEAR AND MONTH NOW to save data from many return",
     *     operationId="showMosques",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){ 
        ini_set('max_execution_time', '500');

        $mosque = Mosque::with('AvgRating')
            ->where('soft_delete', 0)
            ->orderBy('id_mosque', 'DESC')
            ->get();

        $now = Carbon::now()->format('Y-m-d');
        $year = Carbon::today()->format('Y');
        $mth = Carbon::today()->format('m');

        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
            $salah_times = MosqueSalahTime::where('id_mosque', $d->id_mosque)
                ->where('date_salah_times',$now)
                ->where('soft_delete', 0)
                ->get();
            $jummah_times = MosqueSpecialSalahTime::where('id_mosque', $d->id_mosque)
                ->where('mosque_special_salah_times.category_special_salah', 3) //category special salah = jummah
                ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
                ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
                ->where('soft_delete', 0)
                ->get();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->where('mosque_facilities.soft_delete', 0)
                ->get();

            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->orderByRaw('id_mosque_image DESC')
                ->where('soft_delete', 0)
                ->take(1)
                ->get();

            $mosque[$index]->borough = $borough ?? null;
            $mosque[$index]->district = $district  ?? null;
            $mosque[$index]->salah_times = $salah_times ?? null;
            $mosque[$index]->jummah_times = $jummah_times ?? null;
            $mosque[$index]->facilities = $facilities ?? null;
            $mosque[$index]->images = $images ?? null;
        }

        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['totaldata'] = $mosque->count();
            $res['data'] = $mosque;
            $res['message'] = 'Success return Mosque data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }




    /**
     * @SWG\Get(
     *   path="/mosque/showAllAdminPage",
     *     tags={"Mosque"},
     *     summary="show all data mosque with main purpose for Admin Page response",
     *     description="show all data mosque with main purpose for Admin Page response",
     *     operationId="showAllAdminPageMosques",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showAllAdminPage(){ 
        ini_set('max_execution_time', '500');

        $mosque = Mosque::leftjoin('borough','borough.id_borough','=','mosque.id_borough')
            ->where('mosque.soft_delete', 0)
            ->where('borough.soft_delete', 0)
            ->orderBy('id_mosque', 'ASC')
            ->get([
                'mosque.id_mosque',
                'mosque.id_borough',
                'mosque.name_mosque',
                'mosque.slug_mosque',
                'mosque.address',
                'mosque.url',
                'borough.name_borough',
                'mosque.latitude',
                'mosque.longitude',
                'mosque.created_at',
                'mosque.updated_at',
            ]);

        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['totaldata'] = $mosque->count();
            $res['data'] = $mosque;
            $res['message'] = 'Success return Mosque data';

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/showById",
     *     tags={"Mosque"},
     *     summary="Show all data mosque response",
     *     description="Show all data mosque response
           <br>*Salah Times using DATE NOW to save data from many return
           <br>*Jummah Times using DATE YEAR AND MONTH NOW to save data from many return",
     *     operationId="showByIdMosques",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque",
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
            'id_mosque'=>'required|numeric',
        ]);  

        $id_mosque = $request->input('id_mosque');
        
        $mosque = Mosque::where('id_mosque', $id_mosque)
            ->first();

        if($mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }

        $mosque = Mosque::with('AvgRating')
        ->where('id_mosque', $id_mosque)
        ->get();

        $now = Carbon::now()->format('Y-m-d');
        $year = Carbon::today()->format('Y');
        $mth = Carbon::today()->format('m');
        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
            $salah_times = MosqueSalahTime::where('id_mosque', $d->id_mosque)
                ->where('date_salah_times',$now)
                ->where('soft_delete', 0)
                ->get();
            $jummah_times = MosqueSpecialSalahTime::where('id_mosque', $d->id_mosque)
                ->where('mosque_special_salah_times.category_special_salah', 3) //category special salah = 3 / jummah
                ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
                ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
                ->where('soft_delete', 0)
                ->get();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->where('mosque_facilities.soft_delete', 0)
                ->get();

            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->get();
            
            $mosque[$index]->borough = $borough ?? null;
            $mosque[$index]->district = $district  ?? null;
            $mosque[$index]->salah_times = $salah_times ?? null;
            $mosque[$index]->jummah_times = $jummah_times ?? null;
            $mosque[$index]->facilities = $facilities ?? null;
            $mosque[$index]->images = $images ?? null;
        }

        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['totaldata'] = $mosque->count();
            $res['data'] = $mosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }



    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque/showGroupByBorough",
     *     tags={"Mosque"},
     *     summary="show Group By Borough all data mosque response",
     *     description="show Group By Borough all data mosque response
           <br>*Salah Times using DATE NOW to save data from many return
           <br>*Jummah Times using DATE YEAR AND MONTH NOW to save data from many return",
     *     operationId="showGroupByBoroughMosques",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showGroupByBorough(){ 
        ini_set('max_execution_time', '500');

        $borough = Borough::where('soft_delete', 0)->get();

        $now = Carbon::now()->format('Y-m-d');
        $year = Carbon::today()->format('Y');
        $mth = Carbon::today()->format('m');

        if(!$borough->isEmpty()){
            
            foreach($borough as $borough){
                $res['status_code'] = 200;
                $res['success'] = true;
                $res['message'] = 'Success return Mosque data';
                $res['data']['Borough'][$borough->name_borough] = $borough;

                $mosque = Mosque::with('AvgRating')
                ->where('soft_delete', 0)
                ->where('id_borough', $borough->id_borough)
                ->orderBy('id_mosque', 'DESC')
                ->get();
    
    
                foreach($mosque as $index => $d){
                    $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                        ->where('id_mosque', $d->id_mosque)
                        ->where('mosque_facilities.soft_delete', 0)
                        ->get();
    
                    $images = MosqueImage::where('id_mosque', $d->id_mosque)
                        ->orderByRaw('id_mosque_image DESC')
                        ->where('soft_delete', 0)
                        ->take(1)
                        ->get();
                    
                    $mosque[$index]->facilities = $facilities ?? null;
                    $mosque[$index]->images = $images ?? null;
                }
                $res['data']['Borough'][$borough->name_borough]['TotalDataMosque'] = $mosque->count();
                $res['data']['Borough'][$borough->name_borough]['DataMosque'] = $mosque;

            }

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Borough / Mosque Data Not Found";

            return response($res, 200);
        }
    }

    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/showByFilter",
     *     tags={"Mosque"},
     *     summary="Show all data mosque response",
     *     description="Show all data mosque response
           <br>*id_user and status_mosque_favorite must use together to get favorite mosque the user have
           <br>*image limit 5, use show by id to show all image or in Mosque Images Tags ",
     *     operationId="showByFilterMosques",
     * 
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
     *   @SWG\Parameter(
     *     name="id_district",
     *     in="formData",
     *     description="ID District ...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="slug_mosque",
     *     in="formData",
     *     description="Slug Mosque (Unique) ...",
     *     required=false,
     *     type="string"
     *   ), 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="status_mosque_favorite",
     *     in="formData",
     *     description="Status Mosque Favorite (Default=1 / Favorite) [0 = Not Favorite  ||  1 = Favorite]",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="name_mosque",
     *     in="formData",
     *     description="Name Mosque (%like) ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="Address Mosque (%like) ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="url",
     *     in="formData",
     *     description="URL Mosque (%like) ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="date_salah_times_from",
     *     in="formData",
     *     description="Date Salah Times From (YYYY-mm-dd ,ex : 2021-11-15) ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="date_salah_times_to",
     *     in="formData",
     *     description="Date Salah Times To (YYYY-mm-dd ,ex : 2021-11-16) ...",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="id_facilities[]",
     *     in="formData",
     *     description="ID Facilities (Facilities in mosque, use comma / , to seperate)[example = 1,2,3,etc] ...",
     *     required=false,
     *     type="string"
     *   ), 
     *  @SWG\Parameter(
     *     name="where_year_jummah_times",
     *     in="formData",
     *     description="where_year_jummah_times [default = this year] (Jummah Times) ...",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     name="where_month_jummah_times",
     *     in="formData",
     *     description="where_month_jummah_times (month : 1 - 12) [default = this month] (Jummah Times) ...",
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
        
        $id_mosque = $request->input('id_mosque');
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $slug_mosque = $request->input('slug_mosque');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
        $url = $request->input('url');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";
        
        $id_user = $request->input('id_user');
        $status_mosque_favorite = $request->input('status_mosque_favorite') ?? 1;

        $id_facilities = $request->input('id_facilities');
        $date_salah_times_from = $request->input('date_salah_times_from') ?? Carbon::now()->format('Y-m-d');
        $date_salah_times_to = $request->input('date_salah_times_to') ?? Carbon::now()->format('Y-m-d');
        
        $year = $request->input('where_year_jummah_times') ?? Carbon::today()->format('Y');
        $mth = $request->input('where_month_jummah_times') ?? Carbon::today()->format('m');
        
        $StartTimeDay = '00:00:01';
        $EndTimeDay = '23:59:59';
        
        $mosque = Mosque::query()
        ->when($request->has('id_facilities'), function ($query) use ($id_facilities) {
            $query->leftJoin('mosque_facilities','mosque_facilities.id_mosque','=','mosque.id_mosque');
            $query->leftJoin('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities');
        })
        ->when($request->has('id_user') && $request->has('status_mosque_favorite'), function ($query) use ($id_user, $status_mosque_favorite) {
            $query->leftJoin('mosque_favorite','mosque_favorite.id_mosque','=','mosque.id_mosque');
            $query->leftJoin('users','users.id_user','=','mosque_favorite.id_user');
            ///////////////////////////////////////
            $query->where('users.id_user',$id_user);
            $query->where('mosque_favorite.status_mosque_favorite',$status_mosque_favorite);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque.id_mosque',$id_mosque);
        })
        ->when($request->has('slug_mosque'), function ($query) use ($slug_mosque) {
            $query->where('mosque.slug_mosque',$slug_mosque);
        })
        ->when($request->has('id_borough'), function ($query) use ($id_borough) {
            $query->where('mosque.id_borough',$id_borough);
        })
        ->when($request->has('id_district'), function ($query) use ($id_district) {
            $query->where('mosque.id_district',$id_district);
        })
        ->when($request->has('name_mosque'), function ($query) use ($name_mosque) {
            $query->where('mosque.name_mosque','like','%'.$name_mosque.'%');
        })
        ->when($request->has('address'), function ($query) use ($address) {
            $query->where('mosque.address','like','%'.$address.'%');
        })
        ->when($request->has('url'), function ($query) use ($url) {
            $query->where('mosque.url','like','%'.$url.'%');
        })
        ->when($request->has('id_facilities'), function ($query) use ($id_facilities) {
            $query->whereIn('mosque_facilities.id_facilities',$id_facilities);
        })
        ->where('mosque.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->with('AvgRating')
        ->orderByRaw('mosque.id_mosque '.$order)
        ->distinct('mosque.id_mosque')
        ->get([
            'mosque.id_mosque',
            'mosque.id_mosque_google_place',
            'mosque.id_borough',
            'mosque.id_district',
            'mosque.slug_mosque',
            'mosque.name_mosque',
            'mosque.address',
            'mosque.url',
            'mosque.latitude',
            'mosque.longitude',
            'mosque.soft_delete',
            'mosque.created_at as created_at',
            'mosque.updated_at as updated_at'
        ]);
        

        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
            $salah_times = MosqueSalahTime::where('id_mosque', $d->id_mosque)
                ->whereBetween('date_salah_times', [$date_salah_times_from, $date_salah_times_to])
                ->where('soft_delete', 0)
                ->get();
            $jummah_times = MosqueSpecialSalahTime::where('id_mosque', $d->id_mosque)
                ->where('mosque_special_salah_times.category_special_salah', 3) //category special salah = 3 / jummah
                ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
                ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
                ->where('soft_delete', 0)
                ->get();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->where('mosque_facilities.soft_delete', 0)
                ->get();

            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->take(5)
                ->get();
    
            $mosque[$index]->borough = $borough ?? null;
            $mosque[$index]->district = $district  ?? null;
            $mosque[$index]->salah_times = $salah_times ?? null;
            $mosque[$index]->jummah_times = $jummah_times ?? null;
            $mosque[$index]->facilities = $facilities ?? null;
            $mosque[$index]->images = $images ?? null;
        }


        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['totaldata'] = $mosque->count();
            $res['data'] = $mosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }



    
    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW By ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque/nearestMosque",
     *     tags={"Mosque"},
     *     summary="Show Nearest data mosque from latitude and longitude response",
     *     description="Show Nearest data mosque from latitude and longitude response
           <br>*Salah Times using DATE NOW to save data from many return
           <br>*Jummah Times using DATE YEAR AND MONTH NOW to save data from many return",
     *     operationId="nearestMosqueMosques",
     * 
     *   @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Latitude ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Longitude ...",
     *     required=true,
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
    public function nearestMosque(Request $request){ 
        
        $this->validate($request,[
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $latitude = (float)$request->input('latitude');
        $longitude = (float)$request->input('longitude');
        $lat = number_format($latitude,4);
        $long = number_format($longitude,4);
        
        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $date_salah_times_from = Carbon::now()->format('Y-m-d');
        $date_salah_times_to = Carbon::now()->format('Y-m-d');
        
        $year = Carbon::today()->format('Y');
        $mth = Carbon::today()->format('m');
        
        $StartTimeDay = '00:00:01';
        $EndTimeDay = '23:59:59';
        
        ////////////////
        //////////
        //Function To Calculate Distance
        function distance($lat1, $lon1, $lat2, $lon2, $unit) {
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
              return 0;
            }
            else {
              $theta = $lon1 - $lon2;
              $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
              $dist = acos($dist);
              $dist = rad2deg($dist);
              $miles = $dist * 60 * 1.1515;
              $unit = strtoupper($unit);
              
              if ($unit == "K") {
              $miles = ($miles * 1.609344);
              return number_format($miles,2, '.', '');
              } else if ($unit == "N") {
              $miles = ($miles * 0.8684);
              return number_format($miles,2, '.', '');
              } else {
              $miles = $miles;
              return number_format($miles,2, '.', '');
              }
            }
          }

        $dayNow = Carbon::now()->format('Y-m-d');
        $thisHour = Carbon::now()->format('H:i:s');
        $oneHourAgo = Carbon::now()->subHours(1)->format('H:i:s');

        $mosque = Mosque::query()
        ->where('mosque.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->with('AvgRating')
        ->orderByRaw('mosque.id_mosque '.$order)
        ->distinct('mosque.id_mosque')
        ->get([
            'mosque.id_mosque',
            'mosque.id_mosque_google_place',
            'mosque.id_borough',
            'mosque.id_district',
            'mosque.slug_mosque',
            'mosque.name_mosque',
            'mosque.address',
            'mosque.url',
            'mosque.latitude',
            'mosque.longitude',
            'mosque.soft_delete',
            'mosque.created_at as created_at',
            'mosque.updated_at as updated_at'
        ]);

        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->where('mosque_facilities.soft_delete', 0)
                ->get();
            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->take(2)
                ->get();
            $distance = distance($lat, $long, (float)$d->latitude, (float)$d->longitude, "M");
    
            $mosque[$index]->distance = $distance ?? null;
            $mosque[$index]->borough = $borough ?? null;
            $mosque[$index]->district = $district  ?? null;
            $mosque[$index]->facilities = $facilities ?? null;
            $mosque[$index]->images = $images ?? null;
        }

        if(count($mosque) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque data';
            $res['data'] = $mosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

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
     *   path="/mosque/import",
     *     tags={"Mosque"},
     *     summary="Import new data Mosque response",
     *     description="Import new data Mosque <br>
        <p>1. File Excel Mosque extension must be .xlsx or .xls or .csv</p>
        <p>2. Data Borough Must Be In Database ,</p>
        <p>3. CSV Data MUST be seperated by Comma ,</p>
        <p>4. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importMosque",
     * 
     *  @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="file (File Excel Mosque .xlsx or .xls) ...",
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
            $res['detail'] = "Files Mosque Extension Must Be (xlsx/xls/csv)";
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
        $import = new MosqueImport($data);
        Excel::import($import, $file);
        

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque data';
        return response($res, 200);
    }







    /**
     * @SWG\Post(
     *   path="/mosque/importMultiple",
     *     tags={"Mosque"},
     *     summary="Import Multiple new data Mosque response",
     *     description="Import Multiple new data Mosque <br>
        <p>1. File Excel Mosque extension must be .xlsx or .xls or .csv</p>
        <p>2. Data Borough Must Be In Database ,</p>
        <p>3. CSV Data MUST be seperated by Comma ,</p>
        <p>4. CSV Data Format MUST be same like other, dont make a difference in the ORDER & FORMAT data in each file</p>",
     *     operationId="importMultipleMosque",
     * 
     *  @SWG\Parameter(
     *     name="file[0]",
     *     in="formData",
     *     description="file (File Excel Mosque .xlsx or .xls) ...",
     *     required=true,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[1]",
     *     in="formData",
     *     description="file (File Excel Mosque .xlsx or .xls or .csv) ...",
     *     required=false,
     *     type="file"
     *   ), 
     *  @SWG\Parameter(
     *     name="file[2]",
     *     in="formData",
     *     description="file (File Excel Mosque .xlsx or .xls or .csv) ...",
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
                $res['detail'] = "Files Mosque Extension Must Be (xlsx/xls/csv)";
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
            $import = new MosqueImport($data);
            Excel::import($import, $file);
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['message'] = 'Success Importing Mosque data';
        return response($res, 200);
    }
}
