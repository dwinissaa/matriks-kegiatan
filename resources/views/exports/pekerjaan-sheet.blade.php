<table>
    <thead>
        <tr style="font-weight:bold;">
            <th>ID ANGGOTA</th>
            <th>NAMA ANGGOTA</th>
            <th>URAIAN PEKERJAAN</th>
            <th>TARGET</th>
            <th>SATUAN</th>
            <th>HARGA PER SATUAN</th>
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i < count($tempek); $i++)
            <tr>
                <td>{{ $tempek[$i]->id_anggota }}</td>
                <td>{{ $tempek[$i]->nama }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endfor
    </tbody>
</table>
