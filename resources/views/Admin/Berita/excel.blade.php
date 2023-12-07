<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Header</th>
            <th>Body</th>
            <th>Footer</th>
            <th>Dibuat Oleh</th>
        </tr>
    </thead>
    <tbody>
        @foreach $no=0; ($data as $dt) $no++;
            <tr>
                <td><strong>{{ $dt->berita_kode }}</strong></td>
                <td>{{ $dt->berita_tanggal }}</td>
                <td>{{ $dt->header }}</td>
                <td>{{ $dt->body }}</td>
                <td>{{ $dt->footer }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
