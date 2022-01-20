<?php

namespace App\Imports;

use App\Model\RamadhanSalahTimes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class RamadhanSalahTimesImport implements ToModel, WithStartRow
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
        // dd($row['date_salah_times']);
        // Retrieve by name, or instantiate with the name, delayed, and arrival_time attributes...
        
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
            if($row[2] !== null){ // fajr / subuh
                $data = RamadhanSalahTimes::firstOrNew(['date_ramadhan_salah_times' => $date,
                'type_ramadhan_salah_times' => 1]);

                $data->date_ramadhan_salah_times = $date;
                $data->type_ramadhan_salah_times = 1;
                $data->time_ramadhan_salah_times = $row[2];
                $data->save();
            }
            if($row[3] !== null){ // dhuhur
                $data = RamadhanSalahTimes::firstOrNew(['date_ramadhan_salah_times' => $date,
                'type_ramadhan_salah_times' => 2]);

                $data->date_ramadhan_salah_times = $date;
                $data->type_ramadhan_salah_times = 2;
                $data->time_ramadhan_salah_times = $row[3];
                $data->save();
            }
            if($row[4] !== null){ // ashar
                $data = RamadhanSalahTimes::firstOrNew(['date_ramadhan_salah_times' => $date,
                'type_ramadhan_salah_times' => 3]);

                $data->date_ramadhan_salah_times = $date;
                $data->type_ramadhan_salah_times = 3;
                $data->time_ramadhan_salah_times = $row[4];
                $data->save();
            }
            if($row[5] !== null){ // magrib
                $data = RamadhanSalahTimes::firstOrNew(['date_ramadhan_salah_times' => $date,
                'type_ramadhan_salah_times' => 4]);
                
                $data->date_ramadhan_salah_times = $date;
                $data->type_ramadhan_salah_times = 4;
                $data->time_ramadhan_salah_times = $row[5];
                $data->save();
            }
            if($row[6] !== null){  // isha
                $data = RamadhanSalahTimes::firstOrNew(['date_ramadhan_salah_times' => $date,
                'type_ramadhan_salah_times' => 5]);
                
                $data->date_ramadhan_salah_times = $date;
                $data->type_ramadhan_salah_times = 5;
                $data->time_ramadhan_salah_times = $row[6];
                $data->save();
            }
        }
    }
}
