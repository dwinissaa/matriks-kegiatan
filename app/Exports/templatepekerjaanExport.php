<?php

namespace App\Exports;

use App\Exports\Sheets\pekerjaanFrontSheet;
use App\Exports\Sheets\pekerjaanSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class templatepekerjaanExport implements
    WithMultipleSheets
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */

    private $id_keg;

    public function __construct(string $id_keg)
    {
        $this->id_keg = $id_keg;
    }

    public function sheets(): array
    {
        $sheets = [new pekerjaanFrontSheet($this->id_keg), new pekerjaanSheet($this->id_keg, 'dat'), new pekerjaanSheet($this->id_keg, 'cth'), ];
        return $sheets;
    }
}
