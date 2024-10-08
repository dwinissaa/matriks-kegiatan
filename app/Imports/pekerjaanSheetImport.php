<?php

namespace App\Imports;

use App\Models\Pekerjaan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\Failure;

class pekerjaanSheetImport implements
    ToModel,
    WithHeadingRow
    ,SkipsOnError
    ,WithValidation
    // ,SkipsOnFailure
    // WithEvents
// , WithBatchInserts, WithChunkReading,ShouldQueue
{
    use
        SkipsErrors,
        Importable
        // ,SkipsFailures
        ;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $id_keg;
    private $rows = 0;

    public function __construct(string $id_keg)
    {
        $this->id_keg = $id_keg;
    }

    public function model(array $row)
    {
        ++$this->rows;
        // dd($row['id_anggota']);
        return new Pekerjaan([
            'id_keg' => $this->id_keg,
            'id_anggota' => $row['id_anggota'],
            'uraian_pekerjaan' => $row['uraian_pekerjaan'],
            'target' => $row['target'],
            'satuan' => $row['satuan'],
            'harga_satuan' => $row['harga_per_satuan']
        ]);
    }

    public function rules(): array
    {
        // dd("rule",$this->id_keg);
        return [
            'id_anggota' => 'required|exists:alokasikegiatan,id_anggota,id_keg,'.$this->id_keg,
            'uraian_pekerjaan' => 'required',
            'target' => 'required|numeric',
            'harga_per_satuan' => 'required|numeric'
        ];
    }

    // public function getRowCount(): int
    // {
        
    //     return $this->rows;
    // }
    

    // public function registerEvents(): array
    // {
    //     return [
    //         BeforeImport::class => function (BeforeImport $event) {
    //             $totalRows = $event->getReader()->getTotalRows();

    //             dd($totalRows);
    //         }
    //     ];
    // }

    // public function headingRow(): int
    // {
    //     return 1;
    // }
    // public function onFailure(Failure ...$failure){

    // }

    // public function batchSize(): int
    // {
    //     return 1000;
    // }

    // public function chunkSize(): int
    // {
    //     return 1000;
    // }

}
