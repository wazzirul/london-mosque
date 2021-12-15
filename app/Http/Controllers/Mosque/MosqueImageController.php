<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueImage;


class MosqueImageController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-image/store",
     *     tags={"Mosque Image"},
     *     summary="Storing new data mosque image response",
     *     description="Storing new data mosque image response",
     *     operationId="storeMosqueImage",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
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
            'id_mosque'=>'required|numeric',
            'mosque_image_url'=>'required',
        ]);  

        $id_mosque = $request->input('id_mosque');

        $Mosque = Mosque::find($id_mosque);
        if($Mosque === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

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
            $cloudinary = cloudinary()->upload($uploadedfile)->getSecurePath('londonmosques-image');
            //will remove from local laravel folder  
            unlink($localfolder . $file); 

            $data = MosqueImage::create([
                'id_mosque' => $id_mosque,
                'mosque_image_url' => $cloudinary,
            ]);

            if($data){
                $res['status_code'] = 200;
                $res['success'] = true;
                $res['detail'] = "Success Storing new Mosque Image.";
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
     *   path="/mosque-image/update",
     *     tags={"Mosque Image"},
     *     summary="Updating data mosque image response",
     *     description="Updating data mosque image response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_image",
     *     in="formData",
     *     description="ID Mosque Image ...",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=false,
     *     type="integer"
     *   ),  
     *  @SWG\Parameter(
     *     name="mosque_image_url",
     *     in="formData",
     *     description="Mosque Image Url (base64) ...",
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
            'id_mosque_image'=>'required|numeric',
            'id_mosque'=>'nullable|numeric',
            'mosque_image_url'=>'nullable',
        ]);  

        $id_mosque_image = $request->input('id_mosque_image');
        $MosqueImage = MosqueImage::find($id_mosque_image);

        if($MosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Image for that ID not found.";

            return response($res, 200);
        }

        $id_mosque = $request->input('id_mosque') ?? null;
        $image = $request->mosque_image_url ?? null; //base64 string from frontend  

        if($id_mosque !== null){
            $Mosque = Mosque::find($id_mosque);
            if($Mosque === null){
                $res['status_code'] = 406;
                $res['success'] = false;
                $res['detail'] = "Data Mosque for that ID not found.";

                return response($res, 200);
            }
            
            $MosqueImage->id_mosque = $id_mosque;
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
                $imageDeleted = app('firebase.storage')->getBucket()->object($MosqueImage->mosque_image_url)->delete();

                app('firebase.storage')->getBucket()->upload(base64_decode($files), ['name' => $firebase_storage_path . $file]);  
                //will remove from local laravel folder  
                unlink($localfolder . $file); 

                $MosqueImage->mosque_image_url = $firebase_storage_path.$file;
            } else {  
                $res['status_code'] = 200;
                $res['success'] = true;
                $res['detail'] = "Error upload file to cloud server.";

                return response($res, 200);
            }  
        }

        $data = $MosqueImage->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['detail'] = "Success Updating Mosque Image.";
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-image/delete",
     *     tags={"Mosque Image"},
     *     summary="Deleting data mosque image response",
     *     description="Deleting data mosque image response",
     *     operationId="deleteMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_image",
     *     in="formData",
     *     description="ID Mosque Image ...",
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
            'id_mosque_image'=>'required|numeric',
        ]);  

        $id_mosque_image = $request->input('id_mosque_image');
        $MosqueImage = MosqueImage::find($id_mosque_image);

        if($MosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Image for that ID not found.";

            return response($res, 200);
        }

        $MosqueImage->soft_delete = 1;

        $data = $MosqueImage->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Deleting Mosque data';
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNDELETE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-image/undelete",
     *     tags={"Mosque Image"},
     *     summary="Restoring data terhapus data mosque image response",
     *     description="Restoring data terhapus data mosque image response",
     *     operationId="unDeleteMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_image",
     *     in="formData",
     *     description="ID Mosque Image ...",
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
            'id_mosque_image'=>'required|numeric',
        ]);  

        $id_mosque_image = $request->input('id_mosque_image');
        $MosqueImage = MosqueImage::where('id_mosque_image', $id_mosque_image)
            ->first();

        if($MosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Image for that ID not found.";

            return response($res, 200);
        }else if($MosqueImage->soft_delete === 0){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Image is active.";

            return response($res, 200);
        }

        $MosqueImage->soft_delete = 0;

        $data = $MosqueImage->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring Mosque data';
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque-image/show",
     *     tags={"Mosque Image"},
     *     summary="Show all data mosque image response",
     *     description="Show all data mosque image response",
     *     operationId="showMosqueImage",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(){
        $MosqueImage = MosqueImage::where('soft_delete', 0)
            ->orderBy('id_mosque_image', 'DESC')
            ->get();

        if(count($MosqueImage) !== 0){
            foreach($MosqueImage as $index => $smi){
                $expiresAt = Carbon::now()->addDays();  
                $imageReference = app('firebase.storage')->getBucket()->object($smi->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $MosqueImage[$index]->mosque_image_url_firebase = $image;
                }else{
                    $MosqueImage[$index]->mosque_image_url_firebase = null;
                }
            }

            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Salah Times not found.";
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW BY ID
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-image/showById",
     *     tags={"Mosque Image"},
     *     summary="Show data mosque image by ID mosque image response",
     *     description="Show data mosque image by ID mosque image response",
     *     operationId="showByIdMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_image",
     *     in="formData",
     *     description="ID Mosque Image ...",
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
            'id_mosque_image'=>'required|numeric',
        ]);  

        $id_mosque_image = $request->input('id_mosque_image');
        $MosqueImage = MosqueImage::where('id_mosque_image', $id_mosque_image)
            ->first();

        if($MosqueImage === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }else if($MosqueImage->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque was deleted. Please contact customer service.";

            return response($res, 200);
        }

        $expiresAt = Carbon::now()->addDays();  
        $imageReference = app('firebase.storage')->getBucket()->object($MosqueImage->mosque_image_url);  
        if($imageReference->exists()){
            $image = $imageReference->signedUrl($expiresAt);
            $MosqueImage->mosque_image_url_firebase = $image;
        }  

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $MosqueImage;

        return response($res, 200);
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-image/showByFilter",
     *     tags={"Mosque Image"},
     *     summary="Show data by filter mosque image response",
     *     description="Show data by filter mosque image response",
     *     operationId="showByFilterMosqueImage",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
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

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        $MosqueImage = MosqueImage::query()
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque_image.id_mosque',$id_mosque);
        })
        ->where('mosque_image.soft_delete',$soft_delete)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque_image.id_mosque_image '.$order)
        ->get([
            'mosque_image.id_mosque_image',
            'mosque_image.id_mosque',
            'mosque_image.mosque_image_url',
            'mosque_image.soft_delete',
            'mosque_image.created_at as created_at',
            'mosque_image.updated_at as updated_at'
        ]);
        
        if(count($MosqueImage) !== 0){
            foreach($MosqueImage as $index => $smi){
                $expiresAt = Carbon::now()->addDays();  
                $imageReference = app('firebase.storage')->getBucket()->object($smi->mosque_image_url);  
                if($imageReference->exists()){
                    $image = $imageReference->signedUrl($expiresAt);
                    $MosqueImage[$index]->mosque_image_url_firebase = $image;
                }else{
                    $MosqueImage[$index]->mosque_image_url_firebase = null;
                }
            }

            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque Image data';
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }else{
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['data'] = $MosqueImage;

            return response($res, 200);
        }
    }
}
