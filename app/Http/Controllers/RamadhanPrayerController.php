<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class RamadhanPrayerController extends Controller
{
    //////////////////////////////////////////////////////////////////////////////////////
    // Ramadhan Prayer
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/ramadhan-prayer/date",
     *     tags={"Ramadhan Prayer"},
     *     summary="Show ramadhan prayer by date response",
     *     description="Show ramadhan prayer by date response",
     *     operationId="dateRamadhanPrayer",
     * 
     *   @SWG\Parameter(
     *     name="date",
     *     in="formData",
     *     description="Date (nb: 31-12-2021 -> DD-MM-YYYY) [default: now]...",
     *     required=false,
     *     type="string"
     *   ),
     * 
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function date(Request $request){ 
        $date = $request->input('date') ?? Carbon::now();
        $timestamps = Carbon::parse($date)->format('d-m-Y');

        $aladhan_url = "https://api.aladhan.com/v1/";
        try {
            $response = json_decode(file_get_contents($aladhan_url."gToH?date=".$timestamps));
            
        } catch (\Throwable $th) {
            $fail['status_code'] = 406;
            $fail['success'] = false;
            $fail['detail'] = $th;

            return response($fail, 200);
        }

        $res['gregorian'] = $response->data->gregorian->day." ".$response->data->gregorian->month->en." ".$response->data->gregorian->year;
        $res['hijri'] = $response->data->hijri->day." ".$response->data->hijri->month->en." ".$response->data->hijri->year;
        $hijri_year = $response->data->hijri->year;

        try {
            $response2 = json_decode(file_get_contents($aladhan_url."hijriCalendarByCity?city=London&country=United%20Kingdom&method=2&month=09&year=".$hijri_year));
            
        } catch (\Throwable $th) {
            $fail['status_code'] = 406;
            $fail['success'] = false;
            $fail['detail'] = $th;

            return response($fail, 200);
        }

        foreach ($response2->data as $key => $value) {
            $dataPrayer[$key]['gregorian'] = $value->date->gregorian->day." ".$value->date->gregorian->month->en." ".$value->date->gregorian->year;
            $dataPrayer[$key]['hijri'] = $value->date->hijri->day." ".$value->date->hijri->month->en." ".$value->date->hijri->year;
            $dataPrayer[$key]['weekday_gregorian'] = $value->date->gregorian->weekday->en;
            $dataPrayer[$key]['weekday_hijri'] = $value->date->hijri->weekday->en;
            $dataPrayer[$key]['date_gregorian'] = $value->date->gregorian->date;
            $dataPrayer[$key]['date_hijri'] = $value->date->hijri->date;
            $dataPrayer[$key]['timings'] = $value->timings;
        }

        $res['status_code'] = 200;
        $res['success'] = true;
        $res['data'] = $dataPrayer;
        
        return response($res, 200);
    }
}
