<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Model\Borough;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithConditionalSheets;

class BoroughImport implements ToModel, WithStartRow
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
        $data = Borough::firstOrNew(['name_borough' => $row[1]]);
        
        $data->name_borough = $row[1];
        $data->latitude = $row[2];
        $data->longitude = $row[3];
        $data->save();
    }
}
