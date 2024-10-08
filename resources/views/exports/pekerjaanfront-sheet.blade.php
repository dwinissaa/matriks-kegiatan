<?php
$bulan_arr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
?>
<table>
    <tbody>
        <tr>
            <td></td>
            <td>TAHUN PELAKSAAN</td>
            <td>BULAN PELAKSANAAN</td>
            <td>ID KEGIATAN</td>
            <td>NAMA KEGIATAN</td>
        </tr>
        <tr>
            <td></td>
            <td>{{ $kegiatan->tahun }}</td>
            <td>{{ $bulan_arr[($kegiatan->bulan) - 1] }}</td>
            <td>{{ $kegiatan->id_keg }}</td>
            <td>{{ $kegiatan->kegiatan }}</td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr><th></th><th>Petunjuk:</th></tr>
        <tr>
            <th>1.</th>
            <th>Sheet yang dibaca oleh sistem hanya: "Template Impor"</th>
        </tr>
        <tr>
            <th>2.</th>
            <th>Sheet ini hanya untuk mengimpor pekerjaan pada kegiatan:  {{ $kegiatan->id_keg . '_' . $kegiatan->kegiatan }}. Saat sedang mengimpor, pastikan pilihan kegiatan yang terpilih pada dropdown list sudah benar</th>
        </tr>
        <tr>
            <th>3.</th>
            <th>Jangan mengubah nama kolom yang terdapat pada "Template Impor"</th>
        </tr>
        <tr>
            <th>4.</th>
            <th>Id Anggota dan Nama Anggota yang terdapat pada Sheet "Template Impor" adalah anggota yang terlibat
                pada kegiatan: {{ $kegiatan->id_keg . '_' . $kegiatan->kegiatan }}</th>
        </tr>
        <tr>
            <th>5.</th>
            <th>Satu anggota bisa mengerjakan lebih dari 1 pekerjaan</th>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <th></th>
            <th>
                <b>** TERIMA KASIH **</b>
            </th>
        </tr>
    </tbody>
</table>
