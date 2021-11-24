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
use App\Model\Mosque\MosqueReview;
use App\Model\Users;

class MosqueReviewController extends Controller
{
    //
    //
    //
    //////////////////////////////////////////////////////////////////////////////////////
    // STORE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/mosque-review/store",
     *     tags={"Mosque Review"},
     *     summary="Storing new data Mosque Review response",
     *     description="Storing new data Mosque Review response",
     *     operationId="storeMosqueReview",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque",
     *     in="formData",
     *     description="ID Mosque ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="rating",
     *     in="formData",
     *     description="Rating (1, 2, 3, 4, 5) ...",
     *     required=true,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="review",
     *     in="formData",
     *     description="Review ...",
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
            'id_user'=>'required|numeric',
            'rating'=>'required|numeric',
            'review'=>'required',
        ]);  

        $id_mosque = $request->input('id_mosque');
        $id_user = $request->input('id_user');
        $rating = $request->input('rating');
        $review = $request->input('review');

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
        $checkReview = MosqueReview::where('id_user',$id_user)
        ->where('id_mosque',$id_mosque)
        ->where('soft_delete',0)
        ->first();
        if($checkReview !== null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Review already stored, just use update function if you want to change review.";

            return response($res, 200);
        }

        $data = MosqueReview::create([
            'id_mosque' => $id_mosque,
            'id_user' => $id_user,
            'rating' => $rating,
            'review' => $review,
        ]);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success storing Review data';
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
     *   path="/mosque-review/update",
     *     tags={"Mosque Review"},
     *     summary="Updating data Mosque Review response",
     *     description="Updating data Mosque Review response
          <br>#Leave it <strong>blank</strong> if you don't want to change the data",
     *     operationId="updateMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_review",
     *     in="formData",
     *     description="ID Review ...",
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
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="rating",
     *     in="formData",
     *     description="Rating (1, 2, 3, 4, 5) ...",
     *     required=false,
     *     type="integer"
     *   ), 
     *  @SWG\Parameter(
     *     name="review",
     *     in="formData",
     *     description="Review ...",
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
            'id_mosque_review'=>'required|numeric',
            'id_mosque'=>'nullable|numeric',
            'id_user'=>'nullable|numeric',
            'rating'=>'nullable|numeric',
            'review'=>'nullable',
        ]);  

        $id_mosque_review = $request->input('id_mosque_review');
        $id_mosque = $request->input('id_mosque');
        $id_user = $request->input('id_user');
        $rating = $request->input('rating');
        $review = $request->input('review');

        $data = MosqueReview::find($id_mosque_review);

        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Review for that ID not found.";

            return response($res, 200);
        }else if($data->soft_delete === 1){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Mosque Review was deleted. Please contact customer service.";

            return response($res, 200);
        }


        //////////////////////////////////////////////////////////////////////////////////////
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
        ///////////////////////////////////////////////////////////////////////////////////////
        if($id_user !== null){

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
            $data->id_user = $id_user;
        }
        ///////////////////////////////////////////////////////////////////////////////////////
        if($rating !== null){
            $data->rating = $rating;
        }

        if($review !== null){
            $data->review = $review;
        }

        $data = $data->save();

        $data = MosqueReview::where('id_mosque_review',$id_mosque_review)->get();
        
        foreach($data as $index => $d){
            $mosque = Mosque::with('AvgRating')
                ->where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();

            $data[$index]->mosque = $mosque;
        }

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating Mosque Review data';
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
     *   path="/mosque-review/delete",
     *     tags={"Mosque Review"},
     *     summary="Deleting data Mosque Review response",
     *     description="Deleting data Mosque Review response",
     *     operationId="deleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_review",
     *     in="formData",
     *     description="ID Review ...",
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
            'id_mosque_review'=>'required|numeric',
        ]);  

        $id_mosque_review = $request->input('id_mosque_review');

        $data = MosqueReview::find($id_mosque_review);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Review for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 1;
        $data = $data->save();

        $data = MosqueReview::find($id_mosque_review);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success deleting Mosque Review data';
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
     *   path="/mosque-review/undelete",
     *     tags={"Mosque Review"},
     *     summary="Restoring / undelete data Mosque Review response",
     *     description="Restoring / undelete data Mosque Review response",
     *     operationId="undeleteMosque",
     * 
     *  @SWG\Parameter(
     *     name="id_mosque_review",
     *     in="formData",
     *     description="ID Review ...",
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
            'id_mosque_review'=>'required|numeric',
        ]);  

        $id_mosque_review = $request->input('id_mosque_review');

        $data = MosqueReview::find($id_mosque_review);
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque Review for that ID not found.";

            return response($res, 200);
        }

        $data->soft_delete = 0;
        $data = $data->save();

        $data = MosqueReview::find($id_mosque_review);

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Restoring / undelete Mosque Review data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/mosque-review/show",
     *     tags={"Mosque Review"},
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
        $data = MosqueReview::where('soft_delete', 0)
        ->orderBy('id_mosque_review', 'DESC')
        ->get();

        
        foreach($data as $index => $d){
            $mosque = Mosque::with('AvgRating')
                ->where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();

            $data[$index]->mosque = $mosque;
        }


        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['data'] = $data;
            $res['message'] = 'Success return Mosque Review data';

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
     *   path="/mosque-review/showById",
     *     tags={"Mosque Review"},
     *     summary="Show by id data mosque Review response",
     *     description="Show by id data mosque Review response",
     *     operationId="showByIdSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_review",
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
            'id_mosque_review'=>'required|numeric',
        ]);  

        $id_mosque_review = $request->input('id_mosque_review');
        
        
        $data = MosqueReview::where('id_mosque_review', $id_mosque_review)
        ->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data Mosque for that ID not found.";

            return response($res, 200);
        }
        
        $data = MosqueReview::where('id_mosque_review', $id_mosque_review)
        ->get();

        
        foreach($data as $index => $d){
            $mosque = mosque::with('AvgRating')
                ->where('id_mosque', $d->id_mosque)
                ->where('soft_delete', 0)
                ->first();

            $data[$index]->mosque = $mosque;
        }


        if(count($data) !== 0){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success return Mosque Review data';
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
     *   path="/mosque-review/showByFilter",
     *     tags={"Mosque Review"},
     *     summary="Show by filter data mosque Review response",
     *     description="Show by filter data mosque Review response",
     *     operationId="showByFilterSalahTimes",
     * 
     *   @SWG\Parameter(
     *     name="id_mosque_review",
     *     in="formData",
     *     description="ID Review ...",
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
     *     name="id_user",
     *     in="formData",
     *     description="ID User ...",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="rating",
     *     in="formData",
     *     description="Rating (1, 2, 3, 4, 5) ...",
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
        
        $id_mosque_review = $request->input('id_mosque_review');
        $id_mosque = $request->input('id_mosque');
        $id_user = $request->input('id_user');
        $rating = $request->input('rating');

        $soft_delete = $request->input('soft_delete') ?? 0;
        $skip = $request->input('skip') ?? 0;
        $take = $request->input('take') ?? 100;
        $order = $request->input('order') ?? "DESC";

        
        
        $data = MosqueReview::query()
        ->when($request->has('id_mosque_review'), function ($query) use ($id_mosque_review) {
            $query->where('mosque_review.id_mosque_review',$id_mosque_review);
        })
        ->when($request->has('id_mosque'), function ($query) use ($id_mosque) {
            $query->where('mosque_review.id_mosque',$id_mosque);
        })
        ->when($request->has('id_user'), function ($query) use ($id_user) {
            $query->where('mosque_review.id_user',$id_user);
        })
        ->when($request->has('rating'), function ($query) use ($rating) {
            $query->where('mosque_review.rating',$rating);
        })
        ->where('mosque_review.soft_delete',$soft_delete) 
        ->skip($skip)
        ->take($take)
        ->orderByRaw('mosque_review.id_mosque_review '.$order)
        ->distinct('mosque_review.id_mosque_review')
        ->get([
            'mosque_review.id_mosque_review',
            'mosque_review.id_mosque',
            'mosque_review.id_user',
            'mosque_review.rating',
            'mosque_review.review',
            'mosque_review.soft_delete',
            'mosque_review.created_at',
            'mosque_review.updated_at'
        ]);

        
        foreach($data as $index => $d){
            $mosque = mosque::with('AvgRating')
                ->where('id_mosque', $d->id_mosque)
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
