<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class pekerjaanFrontSheet implements FromView, WithEvents, WithTitle
{
    use Exportable, RegistersEventListeners;
    /**
     * @return \Illuminate\Support\Collection
     */

    private $id_keg;

    public function __construct(string $id_keg)
    {
        $this->id_keg = $id_keg;
    }

    public function collection()
    {
        $kegiatan = DB::table('kegiatan')->where('id_keg', '=', $this->id_keg)->get()->first();
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
        return view('exports.pekerjaanfront-sheet', [
            'tempek' => $this->collection()[0],
            'id_keg' => $this->id_keg,
            'kegiatan' => $this->collection()[1],
        ]);
    }

    public function title(): string
    {
        return 'Informasi';
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getStyle('B1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '#000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
    }
}
