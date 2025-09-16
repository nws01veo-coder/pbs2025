<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kas Keluar</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #f8f9fa; 
            font-weight: bold;
            color: #333;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .currency-header { text-align: left; }
        .currency-value { text-align: right; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Data Kas Keluar</h1>
    <table>
        <thead>
            <tr>
                <th class="text-center" width="6%">ID</th>
                <th class="text-center" width="12%">Tanggal</th>
                <th width="35%">Deskripsi</th>
                <th class="currency-header" width="22%">Jumlah</th>
                <th width="25%">Anggota</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kasKeluars as $kasKeluar)
            <tr>
                <td class="text-center">{{ $kasKeluar->id }}</td>
                <td class="text-center">{{ $kasKeluar->tanggal->format('d/m/Y') }}</td>
                <td>{{ $kasKeluar->deskripsi }}</td>
                <td class="currency-value">{{ number_format($kasKeluar->jumlah, 0, ',', '.') }}</td>
                <td>{{ $kasKeluar->anggota->name ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>