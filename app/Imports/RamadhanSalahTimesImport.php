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
    public function startRow(): int
    {
        return 3;
    }
    public function model(array $row)
    {
        // dd($row['type_salah']);
        // Retrieve by name, or instantiate with the name, delayed, and arrival_time attributes...
        if($row[1] !== null){
            $data = RamadhanSalahTimes::firstOrNew(['date_ramadhan_salah_times' => $row[1],
            'type_ramadhan_salah_times' => $row[2]]);
            $data->date_ramadhan_salah_times = $row[1];
            $data->type_ramadhan_salah_times = $row[2];
            $data->time_ramadhan_salah_times = $row[3];
            $data->save();
        }
    }
}
