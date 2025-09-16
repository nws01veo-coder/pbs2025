<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kas Masuk</title>
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
    <h1>Data Kas Masuk</h1>
    <table>
        <thead>
            <tr>
                <th class="text-center" width="8%">ID</th>
                <th class="text-center" width="15%">Tanggal</th>
                <th width="50%">Deskripsi</th>
                <th class="currency-header" width="27%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kasMasuks as $kasMasuk)
            <tr>
                <td class="text-center">{{ $kasMasuk->id }}</td>
                <td class="text-center">{{ $kasMasuk->tanggal->format('d/m/Y') }}</td>
                <td>{{ $kasMasuk->deskripsi }}</td>
                <td class="currency-value">{{ number_format($kasMasuk->jumlah, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>