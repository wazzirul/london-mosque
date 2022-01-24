<?php

namespace App\Http\Controllers\Mosque;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueFavorite;
use App\Model\Users;

class MosqueFavoriteController extends Controller
{
    //



    //////////////////////////////////////////////////////////////////////////////////////
    // favorite
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-favorite/favorite",
     *     tags={"Mosque Favorite"},
     *     summary="Adding new/ Update data mosque Favorite response",
     *     description="Adding new/ Update data mosque Favorite response",
     *     operationId="favoriteMosqueFavorite",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
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
    public function favorite(Request $request){
        $this->validate($request,[
            'id_user'=>'required|numeric',
            'id_mosque'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $id_mosque = $request->input('id_mosque');

        ////////////////////////////////////////////////////////////////////////////////    
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
        ////////////////////////////////////////////////////////////////////////////////
        $user = Users::where('id_user', $id_user)
            ->first();
        if($user === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data User for that ID not found.";

            return response($res, 200);
        }else if($user->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "User was deleted. Please contact customer service.";

            return response($res, 200);
        }
        ////////////////////////////////////////////////////////////////////////////////

        $MosqueFavorite = MosqueFavorite::where('id_user',$id_user)
        ->where('id_mosque',$id_mosque)
        ->first();

        if($MosqueFavorite === null){
            $MosqueFavorite = MosqueFavorite::create([
                'id_mosque' => $id_mosque,
                'id_user' => $id_user,
                'status_mosque_favorite' => 1
            ]);
        }else{
            $MosqueFavorite->status_mosque_favorite = 1;
        }

        $data = $MosqueFavorite->save();
        $data = MosqueFavorite::with('Mosque','User')
        ->where('id_user',$id_user)
        ->where('id_mosque',$id_mosque)
        ->first();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $data;

            return response($res, 200);
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // UNfavorite
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-favorite/unfavorite",
     *     tags={"Mosque Favorite"},
     *     summary="Remove / Unfavorite data mosque Favorite response",
     *     description="Remove / Unfavorite data mosque Favorite response",
     *     operationId="unfavoriteMosqueFavorite",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_favorite",
     *     in="formData",
     *     description="ID Mosque Favorite ...",
     *     required=true,
     *     type="integer"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function unfavorite(Request $request){
        $this->validate($request,[
            'id_mosque_favorite'=>'required|numeric',
        ]);  

        $id_mosque_favorite = $request->input('id_mosque_favorite');
        $MosqueFavorite = MosqueFavorite::where('id_mosque_favorite', $id_mosque_favorite)
            ->first();

        if($MosqueFavorite === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Favorite for that ID not found.";

            return response($res, 200);
        }

        $MosqueFavorite->status_mosque_favorite = 0;

        $data = $MosqueFavorite->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $MosqueFavorite;

            return response($res, 200);
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////
    // UNfavorite
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-favorite/unfavoritebyusermosque",
     *     tags={"Mosque Favorite"},
     *     summary="Remove / Unfavorite data mosque Favorite response",
     *     description="Remove / Unfavorite data mosque Favorite response",
     *     operationId="unfavoriteMosqueByUserMosqueFavorite",
     * 
     *   @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
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
    public function unfavoritebyusermosque(Request $request){
        $this->validate($request,[
            'id_mosque'=>'required|numeric',
            'id_user'=>'required|numeric',
        ]);  

        $id_user = $request->input('id_user');
        $id_mosque = $request->input('id_mosque');

        $MosqueFavorite = MosqueFavorite::where('id_mosque', $id_mosque)
            ->where('id_user', $id_user)
            ->first();

        if($MosqueFavorite === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Favorite not found.";

            return response($res, 200);
        }

        $MosqueFavorite->status_mosque_favorite = 0;

        $data = $MosqueFavorite->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $MosqueFavorite;

            return response($res, 200);
        }
    }


    


    

    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW by Filter
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-favorite/showByFilter",
     *     tags={"Mosque Favorite"},
     *     summary="Show all data Mosque Favorite response",
     *     description="Show all data Mosque Favorite response",
     *     operationId="showByFilterMosqueFavorite",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_favorite",
     *     in="formData",
     *     description="ID Mosque Favorite ...",
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
     *  @SWG\Parameter(
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
        
        $id_mosque_favorite = $request->input('id_mosque_favorite');
        $id_mosque = $request->input('id_mosque');
        $id_user = $request->input('id_user');

        $status_mosque_favorite = $request->input('status_mosque_favorite') ?? 1;

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = MosqueFavorite::query()
        ->when($request->has('id_mosque_favorite'), function ($query) use ($id_mosque_favorite) {
            $query->where('mosque_favorite.id_mosque_favorite',$id_mosque_favorite);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque_favorite.id_mosque',$id_mosque);
        })
        ->when($request->has('id_user'), function ($query) use ($id_user) {
            $query->where('mosque_favorite.id_user',$id_user);
        })
        ->leftJoin('mosque_image', 'mosque_image.id_mosque', '=', 'mosque_favorite.id_mosque')
        ->where('mosque_favorite.soft_delete',$soft_delete) 
        ->where('mosque_favorite.status_mosque_favorite',$status_mosque_favorite)
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque_favorite.id_mosque_favorite '.$order)
        ->distinct('mosque_favorite.id_mosque_favorite')
        ->get([
            'mosque_favorite.id_mosque_favorite',
            'mosque_favorite.id_mosque',
            'mosque_favorite.id_user',
            'mosque_favorite.status_mosque_favorite',
            'mosque_image.mosque_image_url',
            'mosque_favorite.soft_delete',
            'mosque_favorite.created_at',
            'mosque_favorite.updated_at'
        ]);

        foreach($data as $index => $d){
            $mosque = Mosque::where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();
            // $user = Users::where('id_user', $d->id_user)
            //     ->where('soft_delete', 0)
            //     ->first();

            $data[$index]->mosque = $mosque;
            // $data[$index]->user = $user;
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
