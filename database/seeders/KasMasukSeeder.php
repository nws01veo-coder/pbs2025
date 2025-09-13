<?php

namespace Database\Seeders;

use App\Models\KasMasuk;
use Illuminate\Database\Seeder;

class KasMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KasMasuk::create([
            'tanggal' => '2024-01-15',
            'deskripsi' => 'Iuran Bulanan Januari',
            'jumlah' => 500000.00,
        ]);

        KasMasuk::create([
            'tanggal' => '2024-02-15',
            'deskripsi' => 'Iuran Bulanan Februari',
            'jumlah' => 500000.00,
        ]);

        KasMasuk::create([
            'tanggal' => '2024-03-15',
            'deskripsi' => 'Iuran Bulanan Maret',
            'jumlah' => 500000.00,
        ]);

        KasMasuk::create([
            'tanggal' => '2024-04-15',
            'deskripsi' => 'Iuran Bulanan April',
            'jumlah' => 500000.00,
        ]);

        KasMasuk::create([
            'tanggal' => '2024-05-15',
            'deskripsi' => 'Iuran Bulanan Mei',
            'jumlah' => 500000.00,
        ]);
    }
}
