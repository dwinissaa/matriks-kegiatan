<?php

namespace App\Exports;

use App\Models\Pekerjaan;
use Dotenv\Util\Str;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class pekerjaanExport extends DefaultValueBinder implements
    // FromCollection, 
    // ShouldAutoSize,
    // WithMapping, 
    // WithCustomValueBinder, 
    WithStyles,
    // WithHeadings, 
    WithEvents,
    // WithCalculatedFormulas,
    // WithCustomStartCell, 
    FromView,
    WithDrawings,
    WithColumnWidths
{
    use Exportable, RegistersEventListeners;
    /**
     * @return \Illuminate\Support\Collection
     */

    private $tahun;
    private $bulan;
    private $format;
    private $id_anggota;
    protected $index = 0;
    private $current_row = 4;

    public function __construct(int $tahun, int $bulan, string $id_anggota, string $format)
    {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
        $this->id_anggota = $id_anggota;
        $this->format = $format;
    }

    public function collection()
    {
        $anggota = DB::table('users')->select(DB::raw('users.nip as id_anggota'), 'users.nama', 'users.jabatan')->union(DB::table('mitra')->select(DB::raw('mitra.sobatid as id_anggota, mitra.nama,"Mitra" as jabatan')))->get()->where('id_anggota', '=', $this->id_anggota);
        // dd($anggota);
        $res_peg =  DB::table('pekerjaan')
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'pekerjaan.id_keg', 'left')
            ->join('users', 'users.nip', '=', 'pekerjaan.id_anggota', 'left')
            ->rightJoin('alokasikegiatan', function($join){
                $join->on('alokasikegiatan.id_keg','=','pekerjaan.id_keg');
                $join->on('alokasikegiatan.id_anggota','=','pekerjaan.id_anggota');
            })
            ->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->select('id_pekerjaan', 'pekerjaan.id_keg', 'alokasikegiatan.id_anggota', 'uraian_pekerjaan', 'target', 'satuan', 'harga_satuan', 'tahun', 'bulan', 'kegiatan', 'subject_meter', 'nama', 'tim', 'jabatan', DB::raw('target*harga_satuan as total'))
            ->whereNotNull('nama')
            ->where('kegiatan.tahun', '=', $this->tahun)
            ->where('kegiatan.bulan', '=', $this->bulan)
            ->where('pekerjaan.id_anggota', '=', $this->id_anggota);

        $res_mit =  DB::table('pekerjaan')
            ->join('kegiatan', 'kegiatan.id_keg', '=', 'pekerjaan.id_keg', 'left')
            ->join('mitra', 'mitra.sobatid', '=', 'pekerjaan.id_anggota', 'left')
            ->rightJoin('alokasikegiatan', function($join){
                $join->on('alokasikegiatan.id_keg','=','pekerjaan.id_keg');
                $join->on('alokasikegiatan.id_anggota','=','pekerjaan.id_anggota');
            })
            ->join('tim', 'tim.id_tim', '=', 'kegiatan.subject_meter', 'left')
            ->select('id_pekerjaan', 'pekerjaan.id_keg', 'alokasikegiatan.id_anggota', 'uraian_pekerjaan', 'target', 'satuan', 'harga_satuan', 'tahun', 'bulan', 'kegiatan', 'subject_meter', 'nama', 'tim', DB::raw('"Mitra" AS jabatan, target*harga_satuan as total'))
            ->whereNotNull('nama')
            ->where('kegiatan.tahun', '=', $this->tahun)
            ->where('kegiatan.bulan', '=', $this->bulan)
            ->where('pekerjaan.id_anggota', '=', $this->id_anggota);

        $res = $res_peg->union($res_mit)->get();
        // dd($res);
        return [$res, $anggota];
    }

    public function view(): View
    {
        return view('exports.report', [
            'pek' => $this->collection()[0],
            'ang' => $this->collection()[1],
            'tahun' => $this->tahun,
            'bulan' => $this->bulan,
        ]);
    }


    // public function bindValue(Cell $cell, $value)
    // {
    //     $numericalColumns = ['A', 'D', 'F', 'G']; // columns with numerical values

    //     if (!in_array($cell->getColumn(), $numericalColumns)) {
    //         $cell->setValueExplicit($value, DataType::TYPE_STRING);
    //         return true;
    //     }

    //     if (in_array($cell->getColumn(), $numericalColumns)) {
    //         if (is_numeric($value)) {
    //             $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    //         } else if (!$value) {
    //             $cell->setValueExplicit(0, DataType::TYPE_NUMERIC);
    //         } else {
    //             $cell->setValueExplicit($value, DataType::TYPE_STRING);
    //         }
    //         return true;
    //     }
    //     return parent::bindValue($cell, $value);
    // }

    // public function headings(): array
    // {
    //     return [
    //         'NO',
    //         'URAIAN PEKERJAAN',
    //         'TIM',
    //         'TARGET',
    //         'SATUAN',
    //         'HARGA PER SATUAN',
    //         'JUMLAH',

    //     ];
    // }
    // public function startCell(): string
    // {
    //     return 'A4';
    // }

    // public function map($pek): array
    // {

    //     return [
    //         ++$this->index,
    //         $pek->uraian_pekerjaan,
    //         $pek->tim,
    //         $pek->target,
    //         $pek->satuan,
    //         $pek->harga_satuan,
    //         $pek->total,
    //     ];
    // }

    public static function afterSheet(AfterSheet $event)
    {
        // STYLE BODY TABLE
        if ($event->sheet->getHighestRow() > 10) {
            $lastColumn = 'G';
            $lastRow = $event->sheet->getHighestRow() - 10;

            $range = 'A11:' . $lastColumn . $lastRow;

            $event->sheet->getStyle($range)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '#000000'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);

            $event->sheet->getStyle('A' . $lastRow . ':B' . $lastRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);

            $event->sheet->getStyle('G' . $lastRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);

            $event->sheet
                ->getPageSetup()
                ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        }

        $event->sheet->getStyle('A10:G10')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
            ]
        );

        $event->sheet->getStyle('C' . $lastRow . ':F' . $lastRow)->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);


        //     $event->sheet->appendRows([
        //         ['', '', '', '', ''],
        //         ['', '', '', '', ''],
        //         ['', '', '', '', ''],
        //         ['', '', '', '', ''],
        //         ['', 'Pegawai yang Dinilai', '', '', 'Pejabat Penilai'],
        //         ['', '', '', '', ''],
        //     ], $event);
    }

    // public static function beforeSheet(BeforeSheet $event)
    // {
    //     $event->sheet->getActiveSheet()->getPageSetup()
    //     ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    // }


    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:'.$sheet->getHighestColumn().''.$sheet->getHighestRow())->applyFromArray(
            [
                'font' => [
                    'name' => 'Times New Roman',
                ]
            ]
        );

        // STYLE JUDUL
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]
        );

        // STYLE HEADER TABEL
        $sheet->getStyle('A10:G10')->applyFromArray(
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
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '#000000'],
                    ],
                ],
            ]
        );
        // $sheet->getStyle('A4:G4')->applyFromArray(
        //     [
        //         'borders' => [
        //             'bottom' => [
        //                 'borderStyle' => Border::BORDER_DOUBLE,
        //                 'color' => [
        //                     'rgb' => '000000'
        //                 ]
        //             ],
        //             'top' => [
        //                 'borderStyle' => Border::BORDER_MEDIUM,
        //                 'color' => [
        //                     'rgb' => '000000'
        //                 ]
        //             ]
        //         ],
        //         'alignment' => [
        //             'horizontal' => Alignment::HORIZONTAL_CENTER,
        //             'vertical' => Alignment::VERTICAL_CENTER,
        //             'wrapText' => true,
        //         ],
        //     ]
        // );
    }


    public function drawings()
    {
        // dd('F'.(count($this->collection()[0])+17));
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('assets\img\specimen_pak_herman_removebg.png'));
        if ($this->format=='Excel') {
            $drawing->setHeight(120);
            $drawing->setCoordinates('E' . (count($this->collection()[0]) + 15));
        } else if ($this->format=='Pdf') {
            $drawing->setHeight(100);
            $drawing->setCoordinates('F' . (count($this->collection()[0]) + 17));
        }
        

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8.4,
            'B' => 32.73,
            'C' => 29.07,
            'D' => 9.40,
            'E' => 10.47,
            'F' => 13.67,
            'G' => 11.13,
        ];
    }
}
