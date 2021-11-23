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
     *     summary="Menambah data Mosque response",
     *     description="Menambah data Mosque response",
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
     *     required=true,
     *     type="integer"
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
            'name_mosque'=>'required',
            'address'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
        ]);  

        $id_mosque_google_place = $request->input('id_mosque_google_place') ?? NULL;
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district') ?? null;
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
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
            'name_mosque' => $name_mosque,
            'address' => $address,
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
     *     summary="Mengupdate data Mosque response",
     *     description="Mengupdate data Mosque response
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
        ]);  

        $id_mosque = $request->input('id_mosque');
        $id_mosque_google_place = $request->input('id_mosque_google_place') ?? NULL;
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');
        $address = $request->input('address');
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

        if($name_mosque !== null){
            $data->name_mosque = $name_mosque;
        }

        if($address !== null){
            $data->address = $address;
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
     *     summary="Menghapus data Mosque response",
     *     description="Menghapus data Mosque response",
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
     *     summary="Mengembalikan data Mosque yang terhapus response",
     *     description="Mengembalikan data Mosque yang terhapus response",
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
        $mosque = Mosque::with('AvgRating')
        ->where('soft_delete', 0)
        ->orderBy('id_mosque', 'DESC')
        ->get();

        $now = Carbon::now()->format('Y-m-d');
        $year = Carbon::today()->format('Y');
        $mth = Carbon::today()->format('m');
        $expiresAt = Carbon::now()->addDays();  

        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
            $salah_times = MosqueSalahTime::where('id_mosque', $d->id_mosque)
                ->where('date_salah_times',$now)
                ->get();
            $jummah_times = MosqueSpecialSalahTime::where('id_mosque', $d->id_mosque)
                ->where('mosque_special_salah_times.category_special_salah', 3) //category special salah = jummah
                ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
                ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
                ->get();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->get();
            foreach ($facilities as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->icon_facilities);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $facilities[$key]->icon_facilities_url_firebase = $image;
                }else{
                    $facilities[$key]->icon_facilities_url_firebase = null;
                }
            }

            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->take(5)
                ->get();
            foreach ($images as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $images[$key]->mosque_image_url_firebase = $image;
                }else{
                    $images[$key]->mosque_image_url_firebase = null;
                }
            }

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
        $expiresAt = Carbon::now()->addDays();  
        foreach($mosque as $index => $d){
            $borough = Borough::where('id_borough', $d->id_borough)
                ->where('soft_delete', 0)
                ->first();
            $district = Districts::where('id_district', $d->id_district)
                ->where('soft_delete', 0)
                ->first();
            $salah_times = MosqueSalahTime::where('id_mosque', $d->id_mosque)
                ->where('date_salah_times',$now)
                ->get();
            $jummah_times = MosqueSpecialSalahTime::where('id_mosque', $d->id_mosque)
                ->where('mosque_special_salah_times.category_special_salah', 3) //category special salah = 3 / jummah
                ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
                ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
                ->get();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->get();
            foreach ($facilities as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->icon_facilities);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $facilities[$key]->icon_facilities_url_firebase = $image;
                }else{
                    $facilities[$key]->icon_facilities_url_firebase = null;
                }
            }

            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->get();
            foreach ($images as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $images[$key]->mosque_image_url_firebase = $image;
                }else{
                    $images[$key]->mosque_image_url_firebase = null;
                }
            }

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
        
        $id_mosque = $request->input('id_mosque');
        $id_borough = $request->input('id_borough');
        $id_district = $request->input('id_district');
        $name_mosque = $request->input('name_mosque');

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
        $expiresAt = Carbon::now()->addDays();  
        
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
        ->when($request->has('id_borough'), function ($query) use ($id_borough) {
            $query->where('mosque.id_borough',$id_borough);
        })
        ->when($request->has('id_district'), function ($query) use ($id_district) {
            $query->where('mosque.id_district',$id_district);
        })
        ->when($request->has('name_mosque'), function ($query) use ($name_mosque) {
            $query->where('mosque.name_mosque','like','%'.$name_mosque.'%');
        })
        ->when($request->has('id_facilities'), function ($query) use ($id_facilities) {
            $query->whereIn('mosque_facilities.id_facilities',$id_facilities);
        })
        ->when($request->has('soft_delete'), function ($query) use ($soft_delete) {
            $query->where('mosque.soft_delete',$soft_delete);
        })
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
            'mosque.name_mosque',
            'mosque.address',
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
                ->get();
            $jummah_times = MosqueSpecialSalahTime::where('id_mosque', $d->id_mosque)
                ->where('mosque_special_salah_times.category_special_salah', 3) //category special salah = 3 / jummah
                ->whereYear('mosque_special_salah_times.date_special_salah_times', '=', $year)
                ->whereMonth('mosque_special_salah_times.date_special_salah_times', '=', $mth)
                ->get();
            $facilities = MosqueFacilities::join('facilities','facilities.id_facilities','=','mosque_facilities.id_facilities')
                ->where('id_mosque', $d->id_mosque)
                ->get();
            foreach ($facilities as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->icon_facilities);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $facilities[$key]->icon_facilities_url_firebase = $image;
                }else{
                    $facilities[$key]->icon_facilities_url_firebase = null;
                }
            }

            $images = MosqueImage::where('id_mosque', $d->id_mosque)
                ->take(5)
                ->get();
            foreach ($images as $key => $sim) {
                $imageReference = app('firebase.storage')->getBucket()->object($sim->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $images[$key]->mosque_image_url_firebase = $image;
                }else{
                    $images[$key]->mosque_image_url_firebase = null;
                }
            }
    
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
            $res['data'] = $mosque;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $mosque;

            return response($res, 200);
        }
    }
}
