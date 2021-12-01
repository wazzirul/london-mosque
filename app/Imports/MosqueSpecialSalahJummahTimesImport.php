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
    public function startRow(): int
    {
        return 3;
    }
    public function model(array $row)
    {
        // dd($row['date_special_salah_times']);
        // Retrieve by name, or instantiate with the name, delayed, and arrival_time attributes...
        if($row[1] !== null){
            $data = Mosque::where('name_mosque','like','%'. $row[1].'%')->first();

            if($data !== null){
                if($row[2] !== null){
                    $data = MosqueSpecialSalahTime::firstOrNew(['id_mosque' => $data->id_mosque,
                    'category_special_salah' => 3,
                    'date_special_salah_times' => $row[2],
                    'order_special_salah_times' => $row[3]]);
                    ////////////////////////////////////
                    $data->id_mosque = $data->id_mosque;
                    $data->category_special_salah = 3;
                    $data->date_special_salah_times = $row[2];
                    $data->order_special_salah_times = $row[3];
                    $data->time_special_salah_times = $row[4];
                    $data->save();
                }
            }
        }
    }
}
