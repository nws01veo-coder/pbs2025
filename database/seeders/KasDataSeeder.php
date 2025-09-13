<?php

namespace Database\Seeders;

use App\Models\KasMasuk;
use App\Models\KasKeluar;
use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KasDataSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $anggotas = Anggota::all();
        
        // Data Kas Masuk
        $kasMasukData = [
            ['tanggal' => now()->subDays(30), 'deskripsi' => 'Iuran Bulanan Januari', 'jumlah' => 2000000],
            ['tanggal' => now()->subDays(25), 'deskripsi' => 'Iuran Bulanan Februari', 'jumlah' => 2100000],
            ['tanggal' => now()->subDays(20), 'deskripsi' => 'Donasi Kegiatan', 'jumlah' => 500000],
            ['tanggal' => now()->subDays(15), 'deskripsi' => 'Iuran Bulanan Maret', 'jumlah' => 1900000],
            ['tanggal' => now()->subDays(10), 'deskripsi' => 'Iuran Arisan', 'jumlah' => 1500000],
            ['tanggal' => now()->subDays(5), 'deskripsi' => 'Iuran Bulanan April', 'jumlah' => 2200000],
        ];

        foreach ($kasMasukData as $data) {
            KasMasuk::create($data);
        }

        // Data Kas Keluar (simulasi 80% dari total masuk untuk testing)
        $totalMasuk = collect($kasMasukData)->sum('jumlah');
        $targetKeluar = $totalMasuk * 0.8; // 80% untuk testing status merah

        $kasKeluarData = [
            ['tanggal' => now()->subDays(28), 'deskripsi' => 'Konsumsi Rapat', 'jumlah' => 300000, 'anggota_id' => $anggotas->first()?->id],
            ['tanggal' => now()->subDays(22), 'deskripsi' => 'Biaya Acara', 'jumlah' => 1500000, 'anggota_id' => $anggotas->skip(1)->first()?->id],
            ['tanggal' => now()->subDays(18), 'deskripsi' => 'Bantuan Sosial', 'jumlah' => 1000000, 'anggota_id' => $anggotas->skip(2)->first()?->id],
            ['tanggal' => now()->subDays(12), 'deskripsi' => 'Operasional', 'jumlah' => 500000, 'anggota_id' => $anggotas->first()?->id],
            ['tanggal' => now()->subDays(8), 'deskripsi' => 'Hadiah Arisan', 'jumlah' => 2000000, 'anggota_id' => $anggotas->skip(3)->first()?->id],
            ['tanggal' => now()->subDays(3), 'deskripsi' => 'Pemeliharaan', 'jumlah' => 640000, 'anggota_id' => $anggotas->skip(4)->first()?->id],
        ];

        foreach ($kasKeluarData as $data) {
            if ($data['anggota_id']) {
                KasKeluar::create($data);
            }
        }

        $this->command->info('Kas data seeded successfully!');
        $this->command->info('Total Kas Masuk: Rp ' . number_format($totalMasuk, 0, ',', '.'));
        
        $totalKeluar = collect($kasKeluarData)->sum('jumlah');
        $this->command->info('Total Kas Keluar: Rp ' . number_format($totalKeluar, 0, ',', '.'));
        $this->command->info('Persentase Kas Keluar: ' . round(($totalKeluar / $totalMasuk) * 100, 1) . '%');
    }
}
