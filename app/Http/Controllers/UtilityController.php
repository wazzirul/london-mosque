<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use GuzzleHttp\Client;
use stdClass;
class UtilityController extends Controller
{
    //




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/utility/GetEidFitriDate",
     *     tags={"Utility"},
     *     summary="Get Eid-ul Fitri Date response",
     *     description="Get Eid-ul Fitri Date response",
     *     operationId="GetEidFitriDateUtility",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function GetEidFitriDate(){
        //////////////////////////////////////////
        $client = new Client();
        // FETCH API Tanggalan Hijriah
        $now = Carbon::now()->format('d-m-Y');
        // $now = '10-08-2021';
        $response = $client->request('GET', 'http://api.aladhan.com/v1/gToH?date='.$now);
        $body = json_decode($response->getBody()->getContents());
        if($body->code != 200){
            $res['status_code'] = 500;
            $res['success'] = false;
            $res['reason'] = 'ServerTrouble';
            $res['detail'] = 'Server Under Maintance, Please try again in a moment.';

            return response($res, 200);
        }
        $data = $body->data;
        $date_hijri = explode('-', $data->hijri->date);
        $year_hijri = $date_hijri[2];

        $clientHijri = new Client();
        $responseHijri = $clientHijri->request('GET', 'http://api.aladhan.com/v1//hToG?date=01-10-'.$year_hijri);
        $bodyHijri = json_decode($responseHijri->getBody()->getContents());
        if($bodyHijri->code != 200){
            $res['status_code'] = 500;
            $res['success'] = false;
            $res['reason'] = 'ServerTrouble';
            $res['detail'] = 'Server Under Maintance, Please try again in a moment.';

            return response($res, 200);
        }
        $dataHijri = $bodyHijri->data;


        
        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data']['event'] = 'Eid-ul Fitri';
        $res['data']['gregorian_date'] = $dataHijri->gregorian->date;
        $res['data']['gregorian_date_month'] = $dataHijri->gregorian->month;
        $res['data']['hijri_date'] = $dataHijri->hijri->date;
        $res['data']['hijri_date_month'] = $dataHijri->hijri->month;
        $res['data']['hijri_date_event'] = $dataHijri->hijri->holidays;

        return response($res, 200);
    }




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/utility/GetEidAdhaDate",
     *     tags={"Utility"},
     *     summary="Get Eid-ul Adha Date response",
     *     description="Get Eid-ul Adha Date response",
     *     operationId="GetEidAdhaDateUtility",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function GetEidAdhaDate(){
        //////////////////////////////////////////
        $client = new Client();
        // FETCH API Tanggalan Hijriah
        $now = Carbon::now()->format('d-m-Y');
        // $now = '10-08-2021';
        $response = $client->request('GET', 'http://api.aladhan.com/v1/gToH?date='.$now);
        $body = json_decode($response->getBody()->getContents());
        if($body->code != 200){
            $res['status_code'] = 500;
            $res['success'] = false;
            $res['reason'] = 'ServerTrouble';
            $res['detail'] = 'Server Under Maintance, Please try again in a moment.';

            return response($res, 200);
        }
        $data = $body->data;
        $date_hijri = explode('-', $data->hijri->date);
        $year_hijri = $date_hijri[2];

        $clientHijri = new Client();
        $responseHijri = $clientHijri->request('GET', 'http://api.aladhan.com/v1//hToG?date=10-12-'.$year_hijri);
        $bodyHijri = json_decode($responseHijri->getBody()->getContents());
        if($bodyHijri->code != 200){
            $res['status_code'] = 500;
            $res['success'] = false;
            $res['reason'] = 'ServerTrouble';
            $res['detail'] = 'Server Under Maintance, Please try again in a moment.';

            return response($res, 200);
        }
        $dataHijri = $bodyHijri->data;


        
        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data']['event'] = 'Eid-ul Adha';
        $res['data']['gregorian_date'] = $dataHijri->gregorian->date;
        $res['data']['gregorian_date_month'] = $dataHijri->gregorian->month;
        $res['data']['hijri_date'] = $dataHijri->hijri->date;
        $res['data']['hijri_date_month'] = $dataHijri->hijri->month;
        $res['data']['hijri_date_event'] = $dataHijri->hijri->holidays;

        return response($res, 200);
    }
}
