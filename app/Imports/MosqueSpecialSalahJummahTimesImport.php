<?php

namespace App\Imports;

use App\Model\Mosque\Mosque;
use App\Model\Mosque\MosqueSpecialSalahTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class MosqueSpecialSalahJummahTimesImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $data; 

    public function __construct($data)
    {
        $this->data = $data; 
    }

    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {
        // dd($row['date_special_salah_times']);
        // Retrieve by name, or instantiate with the name, delayed, and arrival_time attributes...
        
        if($this->data !== null){
            $dataMosque = Mosque::find($this->data);

            $dateexplode = explode('-', $row[1]);
            $year = '20'.$dateexplode[2];
            $month = $dateexplode[1];
            if($month == 'Jan'){
                $month = '01';
            }else if($month == 'Feb'){
                $month = '02';
            }else if($month == 'Mar'){
                $month = '03';
            }else if($month == 'Apr'){
                $month = '04';
            }else if($month == 'May'){
                $month = '05';
            }else if($month == 'Jun'){
                $month = '06';
            }else if($month == 'Jul'){
                $month = '07';
            }else if($month == 'Aug'){
                $month = '08';
            }else if($month == 'Sep'){
                $month = '09';
            }else if($month == 'Oct'){
                $month = '10';
            }else if($month == 'Nov'){
                $month = '11';
            }else if($month == 'Dec'){
                $month = '12';
            }
            $day = $dateexplode[0];
            $date = $year.'-'.$month.'-'.$day;
            // $date = $row[1];
            
            if($date !== null){            
                if($row[2] !== null){ // 1st
                    $data = MosqueSpecialSalahTime::firstOrNew(['id_mosque' => $dataMosque->id_mosque,
                    'date_special_salah_times' => $date,
                    'category_special_salah' => 3,
                    'order_special_salah_times' => '1st']);

                    $data->date_special_salah_times = $date;
                    $data->category_special_salah = 3;
                    $data->order_special_salah_times = '1st';
                    $data->time_special_salah_times = $row[2];
                    $data->save();
                }
                if($row[3] !== null){ // 2nd
                    $data = MosqueSpecialSalahTime::firstOrNew(['id_mosque' => $dataMosque->id_mosque,
                    'date_special_salah_times' => $date,
                    'category_special_salah' => 3,
                    'order_special_salah_times' => '2nd']);

                    $data->date_special_salah_times = $date;
                    $data->category_special_salah = 3;
                    $data->order_special_salah_times = '2nd';
                    $data->time_special_salah_times = $row[3];
                    $data->save();
                }
                if($row[4] !== null){ // 3rd
                    $data = MosqueSpecialSalahTime::firstOrNew(['id_mosque' => $dataMosque->id_mosque,
                    'date_special_salah_times' => $date,
                    'category_special_salah' => 3,
                    'order_special_salah_times' => '3rd']);

                    $data->date_special_salah_times = $date;
                    $data->category_special_salah = 3;
                    $data->order_special_salah_times = '3rd';
                    $data->time_special_salah_times = $row[4];
                    $data->save();
                }
                if($row[5] !== null){ // 4th
                    $data = MosqueSpecialSalahTime::firstOrNew(['id_mosque' => $dataMosque->id_mosque,
                    'date_special_salah_times' => $date,
                    'category_special_salah' => 3,
                    'order_special_salah_times' => '4th']);
                    
                    $data->date_special_salah_times = $date;
                    $data->category_special_salah = 3;
                    $data->order_special_salah_times = '4th';
                    $data->time_special_salah_times = $row[5];
                    $data->save();
                }
            }
        }
    }
}
