<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anggota</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Data Anggota</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Lokasi</th>
                <th>Jenis Kelamin</th>
                <th>No Telp</th>
                <th>Alamat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggotas as $anggota)
            <tr>
                <td>{{ $anggota->id }}</td>
                <td>{{ $anggota->name }}</td>
                <td>{{ $anggota->jabatan->name ?? '' }}</td>
                <td>{{ $anggota->lokasi->name ?? '' }}</td>
                <td>{{ $anggota->jenis_kelamin }}</td>
                <td>{{ $anggota->no_telp }}</td>
                <td>{{ $anggota->alamat }}</td>
                <td>{{ $anggota->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
