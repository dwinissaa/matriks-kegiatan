<?php

namespace App\Imports;

use App\Models\Pekerjaan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

HeadingRowFormatter::default('none');

class valpekerjaanImport implements
    // WithMultipleSheets
    // SkipsUnknownSheets
    ToArray
    ,WithHeadingRow
    ,WithEvents
// ,SkipsOnError
// ,SkipsOnFailure
{
    use
        // SkipsErrors, 
        Importable
        // ,SkipsFailures
    ;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $id_keg;
    public $sheetNames;
    public $sheetData;

    
    public function __construct(string $id_keg)
    {
        $this->id_keg = $id_keg;
        // dd($this->id_keg);
        $this->sheetNames = [];
        $this->sheetData = [];
    }


    // public function sheets(): array
    // {
    //     return [
    //         'Template Impor' => new valpekerjaanSheetImport($this->id_keg),
    //     ];
    // }

    // public function onUnknownSheet($sheetName)
    // {
    //     // E.g. you can log that a sheet was not found.
    //     info("Sheet {$sheetName} was skipped");
    // }
    public function array(array $array)
    {
        $this->sheetData[$this->sheetNames[count($this->sheetNames)-1]] = $array;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getTitle();
            }
        ];
    }

    public function getSheetNames() {
        // dd("alla",$this->sheetNames);
        return $this->sheetNames;
    }

}
