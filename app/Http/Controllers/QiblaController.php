<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QiblaController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // UPDATE
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/qibla/direction",
     *     tags={"Qibla"},
     *     summary="Show direction from latitude and latitude response",
     *     description="Show direction from latitude and latitude response",
     *     operationId="qiblaDirection",
     * 
     *   @SWG\Parameter(
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
    public function direction(Request $request){
        $this->validate($request,[
            'latitude'=>'required',
            'longitude'=>'required',
        ]); 

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://api.aladhan.com/v1/qibla/'.$latitude.'/'.$longitude);
        $data = json_decode($response->getBody()->getContents());

        return response()->json($data);
    }
}
