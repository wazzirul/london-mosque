<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use StdClass;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;

class QuranController extends Controller
{
    //

    
    
    //////////////////////////////////////////////////////////////////////////////////////
    // About Us
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * @SWG\Post(
     *   path="/quran/showSurah",
     *     tags={"Quran"},
     *     summary="Returning / Show data Quran Show Al Kahf response",
     *     description="Returning / Show data Quran Show Al Kahf response<br>
     *     Verse = Verse Arabic Ayah Quran<br>
     *     Transliteration = Transliteration From Verse Arabic <br>
     *     Translate = Translate From Verse Arabic to English",
     *     operationId="showQuranAlKahf",
     * 
     *   @SWG\Parameter(
     *     name="number_surah",
     *     in="formData",
     *     description="Number Surah (Al Kahf = 18, Yaseen = 36, Al Waqiah = 56 ) ...",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     */
    public function showSurah(Request $request){

        
        $this->validate($request,[
            'number_surah'=>'required|numeric|min:1|max:144',
        ]);  
        $number_surah = $request->number_surah;
        
        $client = new Client();
        // FETCH API Quran
        $responseVerse = $client->request('GET', 'https://api.alquran.cloud/v1/surah/'.$number_surah.'/quran-unicode');
        $bodyVerse = json_decode($responseVerse->getBody()->getContents());
        
        $responseTranslate = $client->request('GET', 'https://api.alquran.cloud/v1/surah/'.$number_surah.'/en.asad');
        $bodyTranslate = json_decode($responseTranslate->getBody()->getContents());

        $responseTransliteration = $client->request('GET', 'https://api.alquran.cloud/v1/surah/'.$number_surah.'/en.transliteration');
        $bodyTransliteration = json_decode($responseTransliteration->getBody()->getContents());

        $data = new StdClass();
        $data->Verse = $bodyVerse->data;
        $data->Transliteration = $bodyTransliteration->data;
        $data->Translate = $bodyTranslate->data;
        
        if($data === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Cannot get Quran Surah Data.";

            return response($res, 200);
        }else{
            $res['status_code'] = 200;
            $res['success'] = true;
            $res['message'] = 'Success Showing Quran Surah Data';
            $res['data'] = $data;

            return response($res, 200);
        }
    }
}
