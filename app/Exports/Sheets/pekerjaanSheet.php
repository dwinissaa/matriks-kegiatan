<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class pekerjaanSheet extends DefaultValueBinder implements FromView, WithEvents, ShouldAutoSize, WithTitle, WithStyles, WithCustomValueBinder, WithColumnWidths
{
    use Exportable, RegistersEventListeners;
    /**
     * @return \Illuminate\Support\Collection
     */

    private $id_keg;
    private $dat;

    public function __construct(string $id_keg, string $dat)
    {
        $this->id_keg = $id_keg;
        $this->dat = $dat;
    }

    public function collection()
    {
        $kegiatan = DB::table('kegiatan')->where('id_keg', '=', $this->id_keg)->get();
        $tp =  DB::table('alokasikegiatan')
            ->join('users', 'users.nip', '=', 'alokasikegiatan.id_anggota', 'left')
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')
            ->select('alokasikegiatan.id_keg', 'kegiatan', 'id_anggota', 'nama')
            ->whereNotNull('users.nama')
            ->where('alokasikegiatan.id_keg', '=', $this->id_keg);

        $tm =  DB::table('alokasikegiatan')
            ->join('mitra', 'mitra.sobatid', '=', 'alokasikegiatan.id_anggota', 'left')
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'alokasikegiatan.id_keg', 'left')
            ->select('alokasikegiatan.id_keg', 'kegiatan', 'id_anggota', 'nama')
            ->whereNotNull('mitra.nama')
            ->where('alokasikegiatan.id_keg', '=', $this->id_keg);
        $table = $tp->union($tm)->get();
        return [$table, $kegiatan];
    }


    public function view(): View
    {
        if ($this->dat == 'dat') {
            return view('exports.pekerjaan-sheet', [
                'tempek' => $this->collection()[0],
                'id_keg' => $this->id_keg,
                'kegiatan' => $this->collection()[1],
            ]);
        } else {
            return view('exports.pekerjaancontoh-sheet', [
                'tempek' => $this->collection()[0],
                'id_keg' => $this->id_keg,
                'kegiatan' => $this->collection()[1],
            ]);
        }
    }

    public function title(): string
    {
        if ($this->dat == 'dat') {
            return 'Template Impor';
        } else {
            return 'Contoh';
        }
    }

    public function bindValue(Cell $cell, $value)
    {
        $numericalColumns = [];
        if ($this->dat == 'cth') {
            $numericalColumns = ['D', 'F']; // columns with numerical values
        }

        if (!in_array($cell->getColumn(), $numericalColumns)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        } else {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
            } else if (!$value) {
                $cell->setValueExplicit(0, DataType::TYPE_NUMERIC);
            } else {
                $cell->setValueExplicit($value, DataType::TYPE_STRING);
            }
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        // STYLE HEADER TABEL
        $sheet->getStyle('A1:F1')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_DOUBLE,
                        'color' => ['rgb' => '#000000'],
                    ],
                ],
            ]
        );
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14.4,
            'B' => 36.4,
            'C' => 29.07,
            'D' => 9.40,
            'E' => 10.47,
            'F' => 13.67,
            'G' => 11.13,
        ];
    }
}
