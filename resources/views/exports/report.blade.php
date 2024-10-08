<?php
$bulan_arr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$total_bi = 0;
?>

{{-- <style>
   table { border-spacing: 0px; }
</style> --}}
<table>
    <thead>
        <tr></tr>
        <tr>
            <th>TARGET KINERJA {{ $ang->first()->jabatan == 'Mitra' ? 'MITRA' : 'PEGAWAI' }} TAHUN {{ $tahun }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th>Satker</th>
            <th>: BPS Kabupaten Deli Serdang</th>
        </tr>
        <tr>
            <th>Nama</th>
            <th>: {{ $ang->first()->nama }}</th>
        </tr>
        <tr>
            <th>Jabatan</th>
            <th>: {{ $ang->first()->jabatan }}</th>
        </tr>
        <tr>
            <th>Bulan</th>
            <th>: {{ $bulan_arr[$bulan - 1] }}</th>
        </tr>
        <tr> </tr>
        <tr> </tr>
    </thead>
    <tbody>
        <tr>
            <th>NO</th>
            <th>URAIAN PEKERJAAN</th>
            <th>TIM</th>
            <th>TARGET</th>
            <th>SATUAN</th>
            <th>HARGA SATUAN</th>
            <th>JUMLAH</th>
        </tr>
        @foreach ($pek as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->uraian_pekerjaan }}</td>
                <td>{{ $p->tim }}</td>
                <td>{{ $p->target }}</td>
                <td>{{ $p->satuan }}</td>
                <td>{{ $p->harga_satuan }}</td>
                <td>{{ $p->total }}</td>
            </tr>
            <?php
            $total_bi += $p->target * $p->harga_satuan;
            ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">JUMLAH</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>{{ $total_bi }}</th>
        </tr>
        <tr> </tr>
        <tr> </tr>
        <tr> </tr>
        <tr> </tr>
        <tr>
            <th> </th>
            <th>{{ $ang->first()->jabatan == 'Mitra' ? 'Mitra' : 'Pegawai' }} yang dinilai</th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th>Pejabat Penilai</th>
            <th> </th>
        </tr>
        <tr>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
        </tr>
        <tr>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
        </tr>
        <tr>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th> </th>
        </tr>
        <tr>
            <th> </th>
            <th>{{ $ang->first()->nama }}</th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th>{{ 'Herman SE., M.Si.' }}</th>
            <th> </th>
        </tr>
        <tr>
            <th> </th>
            <th>{{ ($ang->first()->jabatan != 'Mitra' ? 'NIP. ' : 'ID. ') . $ang->first()->id_anggota }}</th>
            <th> </th>
            <th> </th>
            <th> </th>
            <th>{{ 'NIP. 340011421' }}</th>
            <th> </th>
        </tr>
        <tr> </tr>
    </tfoot>
</table>

{{-- <script>
    $('table tbody th').attr('style', 'font-weight:bold;text-align:center;');
</script> --}}
