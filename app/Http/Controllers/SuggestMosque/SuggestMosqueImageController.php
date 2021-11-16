<?php

namespace App\Http\Controllers\SuggestMosque;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Model\SuggestMosque\SuggestMosque;
use App\Model\SuggestMosque\SuggestMosqueImage;

class SuggestMosqueImageController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/image/store",
     *     tags={"Suggest Mosque Image"},
     *     summary="Menambah data suggest mosque image response",
     *     description="Menambah data suggest mosque image response",
     *     operationId="storeSuggestMosqueImage",
     * 
     *  @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
     *     required=true,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="mosque_image_url",
     *     in="formData",
     *     description="Image Url (base64) ...",
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
            'mosque_image_url'=>'required',
        ]);  

        $id_suggest_mosque = $request->input('id_suggest_mosque');

        $suggestMosque = SuggestMosque::find($id_suggest_mosque);
        if($suggestMosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }

        $image = $request->mosque_image_url; //base64 string from frontend  
        $name = Str::random(25);
        $firebase_storage_path = 'mosque-image/';  
        $localfolder = public_path('firebase-temp-uploads') .'/';  
        if (!file_exists($localfolder)) {  
            mkdir($localfolder, 0777, true);  
        }  
        $parts = explode(";base64,", $image);
        $replace = substr($image, 0, strpos($image, ',')+1); 
        $files = str_replace($replace, '', $image); 
        $files = str_replace(' ', '+', $files); 
        $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];   // .jpg .png .pdf
        $file = $name.'.'.$extension;
            
        if (file_put_contents($localfolder . $file, base64_decode($files))) {  
            $uploadedfile = fopen($localfolder . $file, 'r');  
            app('firebase.storage')->getBucket()->upload(base64_decode($files), ['name' => $firebase_storage_path . $file]);  
            //will remove from local laravel folder  
            unlink($localfolder . $file); 

            $data = SuggestMosqueImage::create([
                'id_suggest_mosque' => $id_suggest_mosque,
                'mosque_image_url' => $firebase_storage_path.$file,
            ]);

            if($data){
                $res['status_code'] = 200;
                $res['success'] = true;
                $res['data'] = $data;

                return response($res, 200);
            }
        } else {  
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Error upload file to cloud server.";

            return response($res, 200);
        }  
    }


    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/image/update",
     *     tags={"Suggest Mosque Image"},
     *     summary="Mengedit data suggest mosque image response",
     *     description="Mengedit data suggest mosque image response",
     *     operationId="updateSuggestMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_image",
     *     in="formData",
     *     description="ID Suggest Mosque Image ...",
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
     *     name="mosque_image_url",
     *     in="formData",
     *     description="Mosque Image Url (base64) (Kosongi jika tidak ingin merubah data)...",
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
            'id_suggest_mosque_image'=>'required|numeric',
            'id_suggest_mosque'=>'nullable|numeric',
            'mosque_image_url'=>'nullable',
        ]);  

        $id_suggest_mosque_image = $request->input('id_suggest_mosque_image');
        $suggestMosqueImage = SuggestMosqueImage::find($id_suggest_mosque_image);

        if($suggestMosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Image for that ID not found.";

            return response($res, 200);
        }

        $id_suggest_mosque = $request->input('id_suggest_mosque') ?? null;
        $image = $request->mosque_image_url ?? null; //base64 string from frontend  

        if($id_suggest_mosque !== null){
            $suggestMosque = SuggestMosque::find($id_suggest_mosque);
            if($suggestMosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Suggest Mosque for that ID not found.";

                return response($res, 200);
            }
            
            $suggestMosqueImage->id_suggest_mosque = $id_suggest_mosque;
        }

        if($image !== null){
            $image = $request->mosque_image_url; //base64 string from frontend  
            $name = Str::random(25);
            $firebase_storage_path = 'mosque-image/';  
            $localfolder = public_path('firebase-temp-uploads') .'/';  
            if (!file_exists($localfolder)) {  
                mkdir($localfolder, 0777, true);  
            }  
            $parts = explode(";base64,", $image);
            $replace = substr($image, 0, strpos($image, ',')+1); 
            $files = str_replace($replace, '', $image); 
            $files = str_replace(' ', '+', $files); 
            $extension = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];   // .jpg .png .pdf
            $file = $name.'.'.$extension;

            if (file_put_contents($localfolder . $file, base64_decode($files))) {  
                // Delete old image
                $imageDeleted = app('firebase.storage')->getBucket()->object($suggestMosqueImage->mosque_image_url)->delete();

                app('firebase.storage')->getBucket()->upload(base64_decode($files), ['name' => $firebase_storage_path . $file]);  
                //will remove from local laravel folder  
                unlink($localfolder . $file); 

                $suggestMosqueImage->mosque_image_url = $firebase_storage_path.$file;
            } else {  
                $res['status_code'] = 200;
                $res['success'] = true;
                $res['detail'] = "Error upload file to cloud server.";

                return response($res, 200);
            }  
        }

        $data = $suggestMosqueImage->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/image/delete",
     *     tags={"Suggest Mosque Image"},
     *     summary="Menghapus data suggest mosque image response",
     *     description="Menghapus data suggest mosque image response",
     *     operationId="deleteSuggestMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_image",
     *     in="formData",
     *     description="ID Suggest Mosque Image ...",
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
            'id_suggest_mosque_image'=>'required|numeric',
        ]);  

        $id_suggest_mosque_image = $request->input('id_suggest_mosque_image');
        $suggestMosqueImage = SuggestMosqueImage::find($id_suggest_mosque_image);

        if($suggestMosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Image for that ID not found.";

            return response($res, 200);
        }

        $suggestMosqueImage->soft_delete = 1;

        $data = $suggestMosqueImage->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/image/unDelete",
     *     tags={"Suggest Mosque Image"},
     *     summary="Mengembalikan data terhapus data suggest mosque image response",
     *     description="Mengembalikan data terhapus data suggest mosque image response",
     *     operationId="unDeleteSuggestMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_image",
     *     in="formData",
     *     description="ID Suggest Mosque Image ...",
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
            'id_suggest_mosque_image'=>'required|numeric',
        ]);  

        $id_suggest_mosque_image = $request->input('id_suggest_mosque_image');
        $suggestMosqueImage = SuggestMosqueImage::where('id_suggest_mosque_image', $id_suggest_mosque_image)
            ->first();

        if($suggestMosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Image for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueImage->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque Image is active.";

            return response($res, 200);
        }

        $suggestMosqueImage->soft_delete = 0;

        $data = $suggestMosqueImage->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/suggest-mosque/image/show",
     *     tags={"Suggest Mosque Image"},
     *     summary="Show all data suggest mosque image response",
     *     description="Show all data suggest mosque image response",
     *     operationId="showSuggestMosqueImage",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $suggestMosqueImage = SuggestMosqueImage::where('soft_delete', 0)
            ->orderBy('id_suggest_mosque_image', 'DESC')
            ->get();

        if(count($suggestMosqueImage) !== 0){
            foreach($suggestMosqueImage as $index => $smi){
                $expiresAt = Carbon::now()->addDays();  
                $imageReference = app('firebase.storage')->getBucket()->object($smi->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $suggestMosqueImage[$index]->mosque_image_url_firebase = $image;
                }else{
                    $suggestMosqueImage[$index]->mosque_image_url_firebase = null;
                }
            }

            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/image/showById",
     *     tags={"Suggest Mosque Image"},
     *     summary="Show data suggest mosque image by ID suggest mosque image response",
     *     description="Show data suggest mosque image by ID suggest mosque image response",
     *     operationId="showByIdSuggestMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque_image",
     *     in="formData",
     *     description="ID Suggest Mosque Image ...",
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
            'id_suggest_mosque_image'=>'required|numeric',
        ]);  

        $id_suggest_mosque_image = $request->input('id_suggest_mosque_image');
        $suggestMosqueImage = SuggestMosqueImage::where('id_suggest_mosque_image', $id_suggest_mosque_image)
            ->first();

        if($suggestMosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Suggest Mosque for that ID not found.";

            return response($res, 200);
        }else if($suggestMosqueImage->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Suggest Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $expiresAt = Carbon::now()->addDays();  
        $imageReference = app('firebase.storage')->getBucket()->object($suggestMosqueImage->mosque_image_url);  
        if($imageReference->exists()){
            $image = $imageReference->signedUrl($expiresAt);
            $suggestMosqueImage->mosque_image_url_firebase = $image;
        }  

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $suggestMosqueImage;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/suggest-mosque/image/showByFilter",
     *     tags={"Suggest Mosque Image"},
     *     summary="Show data by filter suggest mosque image response",
     *     description="Show data by filter suggest mosque image response",
     *     operationId="showByFilterSuggestMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_suggest_mosque",
     *     in="formData",
     *     description="ID Suggest Mosque ...",
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
        $id_suggest_mosque = $request->input('id_suggest_mosque');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $suggestMosqueImage = SuggestMosqueImage::query()
        ->when($request->has('id_suggest_mosque'), function ($query) use ($id_suggest_mosque) {
            $query->where('suggest_mosque_image.id_suggest_mosque',$id_suggest_mosque);
        })
        ->skip($skip)
        ->take($take)
        ->orderByRaw('suggest_mosque_image.id_suggest_mosque_image '.$order)
        ->get([
            'suggest_mosque_image.id_suggest_mosque_image',
            'suggest_mosque_image.id_suggest_mosque',
            'suggest_mosque_image.mosque_image_url',
            'suggest_mosque_image.soft_delete',
            'suggest_mosque_image.created_at as created_at',
            'suggest_mosque_image.updated_at as updated_at'
        ]);
        
        if(count($suggestMosqueImage) !== 0){
            foreach($suggestMosqueImage as $index => $smi){
                $expiresAt = Carbon::now()->addDays();  
                $imageReference = app('firebase.storage')->getBucket()->object($smi->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $suggestMosqueImage[$index]->mosque_image_url_firebase = $image;
                }else{
                    $suggestMosqueImage[$index]->mosque_image_url_firebase = null;
                }
            }

            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Suggest Mosque Image data';
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $suggestMosqueImage;

            return response($res, 200);
        }
    }
}
