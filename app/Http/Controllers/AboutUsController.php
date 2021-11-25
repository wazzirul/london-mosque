<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\AboutUs;

class AboutUsController extends Controller
{
    
    //////////////////////////////////////////////////////////////////////////////////////
    // About Us
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/AboutUs/update",
     *     tags={"About Us"},
     *     summary="Updating data About Us response",
     *     description="Updating data About Us response",
     *     operationId="updateAboutUs",
     * 
     *   @SWG\Parameter(
     *     name="id_about_us",
     *     in="formData",
     *     description="ID About Us",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="about_us",
     *     in="formData",
     *     description="About Us ...",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function update(Request $request){
        $this->validate($request,[
            'id_about_us'=>'required|numeric',
            'about_us'=>'required',
        ]);  

        $about_us = $request->input('about_us');
        $data = AboutUs::where('soft_delete', 0)->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data About Us not created yet.";

            return response($res, 200);
        }

        $data->about_us = $about_us;
        $data->save();

        if($data){
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success updating About Us data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }

    
    
    //////////////////////////////////////////////////////////////////////////////////////
    // About Us
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/AboutUs/show",
     *     tags={"About Us"},
     *     summary="Returning / Show data About Us response",
     *     description="Returning / Show data About Us response",
     *     operationId="showAboutUs",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function show(Request $request){

        $data = AboutUs::where('soft_delete', 0)->first();

        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Data About Us not created yet.";

            return response($res, 200);
        }else{
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Showing About Us data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
}
