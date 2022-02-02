<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\SalahBeginningTimes;

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




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Get(
     *   path="/utility/GetRamadhanDate",
     *     tags={"Utility"},
     *     summary="Get Ramadhan Date response",
     *     description="Get Ramadhan Date response",
     *     operationId="GetRamadhanDateUtility",
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function GetRamadhanDate(){
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
        $responseHijri = $clientHijri->request('GET', 'http://api.aladhan.com/v1/gToHCalendar/9/'.$year_hijri);
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
        $res['data']['event'] = 'Ramadhan';
        // $res['data']['gregorian_date'] = $dataHijri->gregorian->date;
        // $res['data']['gregorian_date_month'] = $dataHijri->gregorian->month;
        // $res['data']['hijri_date'] = $dataHijri->hijri->date;
        // $res['data']['hijri_date_month'] = $dataHijri->hijri->month;
        // $res['data']['hijri_date_event'] = $dataHijri->hijri->holidays;
        $res['dataLengkap'] = $dataHijri;

        return response($res, 200);
    }


    




    //////////////////////////////////////////////////////////////////////////////////////
    // SHOW
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/utility/GetNowHijriDateAndNextPrayerTime",
     *     tags={"Utility"},
     *     summary="Get Current Gregorian and Hijri Date, Current Time, and time left next Pray  response",
     *     description="Get Current Gregorian and Hijri Date, Current Time, and time left next Pray  response",
     *     operationId="GetNowHijriDateAndNextPrayerTimeUtility",
     * 
     *   @SWG\Parameter(
     *     name="date",
     *     in="formData",
     *     description="date (YYYY-mm-dd ,ex : 2022-02-25)[Default : Date Now] ...",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function GetNowHijriDateAndNextPrayerTime(Request $request){
        /////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////
        $client = new Client();
        // FETCH API Tanggalan Hijriah
        //Variable Date Now And Time now
        $date = $request->input('date') ?? Carbon::now()->format('Y-m-d');
        $now = date('d-m-Y', strtotime($date));;

        $currentTime = Carbon::now()->format('H:i:s');
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
        /////////////////////////////////////////////////////////////////////
        $nowSalahBeginningDate = $date;
        $nowSalahBeginningDateTimes = $date.' '.$currentTime;
        $TomorrowSalahBeginningDate = Carbon::parse($nowSalahBeginningDate)->addDays(1);
        $dataSalahBeginningTimes = SalahBeginningTimes::whereBetween('date_salah_beginning_times', [$nowSalahBeginningDate,$TomorrowSalahBeginningDate])
        ->orderBy('date_salah_beginning_times', 'ASC')
        ->orderBy('type_salah_beginning_times', 'ASC')
        ->get();

        $convertCurrentTimeto12H = date("g:i a", strtotime($currentTime));
        $dataSBTArray = [];

        if(!$dataSalahBeginningTimes->isEmpty()){
            foreach($dataSalahBeginningTimes as $dataSalahBeginningTimesObject){
                //
                // 12-hour time to 24-hour time 
                $timeSalahBeginningTimes = $dataSalahBeginningTimesObject->time_salah_beginning_times;
                
                if($dataSalahBeginningTimesObject->type_salah_beginning_times <= 2){
                    $time_in_24_hour_format  = date("H:i:s", strtotime($timeSalahBeginningTimes." AM"));
                //////////////////////////////////////////////////////////////////////////////////////////    
                }else if($dataSalahBeginningTimesObject->type_salah_beginning_times == 3){
                    $timeExplode = explode(':', $timeSalahBeginningTimes);
                    $HourExplode = $timeExplode[0];
                    if($HourExplode > 02 ){
                        $time_in_24_hour_format  = date("H:i:s", strtotime($timeSalahBeginningTimes." AM"));
                    }else{
                        $time_in_24_hour_format  = date("H:i:s", strtotime($timeSalahBeginningTimes." PM"));
                    }
                //////////////////////////////////////////////////////////////////////////////////////////    
                }else if($dataSalahBeginningTimesObject->type_salah_beginning_times >= 4){
                    $time_in_24_hour_format  = date("H:i:s", strtotime($timeSalahBeginningTimes." PM"));
                //////////////////////////////////////////////////////////////////////////////////////////    
                }else{
                     $time_in_24_hour_format = '00:00:00';
                }
                $dateTimeSBT = Carbon::parse($dataSalahBeginningTimesObject->date_salah_beginning_times.' '.$time_in_24_hour_format);
                $dateTimeSBTDiff = Carbon::parse($nowSalahBeginningDateTimes)->diffInSeconds($dateTimeSBT, false);
                if($dateTimeSBTDiff < 0){
                    continue ;
                }else{
                    if($dataSalahBeginningTimesObject->type_salah_beginning_times === 1){
                        $stringTypeSalah = 'Fajr';
                    }else if($dataSalahBeginningTimesObject->type_salah_beginning_times === 2){
                        $stringTypeSalah = 'Sunsise';
                    }else if($dataSalahBeginningTimesObject->type_salah_beginning_times === 3){
                        $stringTypeSalah = 'Zuhr';
                    }else if($dataSalahBeginningTimesObject->type_salah_beginning_times === 4){
                        $stringTypeSalah = 'Asr 1st Mithl';
                    }else if($dataSalahBeginningTimesObject->type_salah_beginning_times === 5){
                        $stringTypeSalah = 'Asr 2nd Mithl';
                    }else if($dataSalahBeginningTimesObject->type_salah_beginning_times === 6){
                        $stringTypeSalah = 'Maghrib';
                    }else if($dataSalahBeginningTimesObject->type_salah_beginning_times === 7){
                        $stringTypeSalah = 'Isha';
                    }

                    $dateTimeSBTDiff = gmdate('H:i:s', $dateTimeSBTDiff);
                    $dataSBT = new StdClass();
                    $dataSBT->dateSalahBeginningTimes = $dataSalahBeginningTimesObject->date_salah_beginning_times;
                    $dataSBT->typeSalahBeginningTimes = $dataSalahBeginningTimesObject->type_salah_beginning_times;
                    $dataSBT->typeStringSalahBeginningTimes = $stringTypeSalah;
                    $dataSBT->timeSalahBeginningTimes = $time_in_24_hour_format;
                    $dataSBT->dateTimeSBT = $dateTimeSBT;
                    $dataSBT->dateTimeSBTDiff = $dateTimeSBTDiff;
                    $dataSBTArray[] = $dataSBT;
                    break ;
                }
            }
        }else{
            $dataSalahBeginningTimes = null;
        }
        
        /////////////////////////////////////////////////////////////////////
        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data']['currentTime'] = $currentTime;
        $res['data']['convertCurrentTimeto12H'] = $convertCurrentTimeto12H;
        $res['data']['current_date'] = $data->gregorian->date;
        $res['data']['current_date_month'] = $data->gregorian->month;
        $res['data']['hijri_date'] = $data->hijri->date;
        $res['data']['hijri_date_month'] = $data->hijri->month;
        $res['data']['hijri_date_event'] = $data->hijri->holidays;
        $res['data']['dataSBTExplain'] = "DataSBT is Data Salah Beginning Times for the next pray times.";
        $res['data']['dataSBT'] = $dataSBTArray;
        $res['data']['SalahBeginningTimes'] = $dataSalahBeginningTimes;

        return response($res, 200);
        /////////////////////////////////////////////////////////////////////
    }
}
