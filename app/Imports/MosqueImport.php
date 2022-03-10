<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Model\Borough;
use App\Model\Mosque\Mosque;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class MosqueImport implements ToModel, WithStartRow
{
    /**
    * @param Collection $collection
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
        if($row[1] !== null){
            $Borough = Borough::where('name_borough',$row[1])->first();
            if($Borough !== null){
                $data = Mosque::firstOrNew(['name_mosque' => $row[2]]);

                $data->id_borough = $Borough->id_borough;
                $data->slug_mosque = $row[3];
                $data->name_mosque = $row[2];
                $data->address = $row[4];
                $data->latitude = $row[5];
                $data->longitude = $row[6];
                $data->save();
            }
        }
    }
}
