<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $items = [
            [
                'user_id'     => 2,
                'name'        => 'Kartu Mahasiswa dengan holder biru',
                'description' => 'Kartu atas nama Raka Pratama hilang setelah kelas malam. Terakhir terlihat di area parkir motor gedung B.',
                'location'    => 'Parkiran Gedung B, Kampus Utama',
                'image'       => 'https://images.pexels.com/photos/669615/pexels-photo-669615.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'type'        => 'lost',
                'status'      => 'pending',
                'created_at'  => $now->copy()->subDays(3)->subHours(4),
                'updated_at'  => $now->copy()->subDays(3)->subHours(4),
            ],
            [
                'user_id'     => 3,
                'name'        => 'Payung lipat hitam',
                'description' => 'Menemukan payung lipat hitam di depan perpustakaan saat hujan sore. Ada stiker kecil warna merah di gagangnya.',
                'location'    => 'Lobi Perpustakaan',
                'image'       => 'https://images.pexels.com/photos/2819073/pexels-photo-2819073.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'type'        => 'found',
                'status'      => 'pending',
                'created_at'  => $now->copy()->subDays(2)->subHours(1),
                'updated_at'  => $now->copy()->subDays(2)->subHours(1),
            ],
            [
                'user_id'     => 4,
                'name'        => 'Kunci motor Honda dengan gantungan kulit',
                'description' => 'Kunci motor sempat tertinggal di ruang diskusi lantai 2. Sudah diambil pemilik setelah verifikasi ciri-ciri gantungan.',
                'location'    => 'Ruang Diskusi 2.4',
                'image'       => 'https://images.pexels.com/photos/1094767/pexels-photo-1094767.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'type'        => 'lost',
                'status'      => 'resolved',
                'created_at'  => $now->copy()->subDays(7)->subHours(2),
                'updated_at'  => $now->copy()->subDays(5)->subHours(5),
            ],
            [
                'user_id'     => 5,
                'name'        => 'Dompet cokelat berisi kartu ATM',
                'description' => 'Dompet ditemukan oleh petugas kebersihan di kantin. Pemilik sudah menunjukkan KTP dan menerima kembali barang.',
                'location'    => 'Kantin Fakultas Teknik',
                'image'       => 'https://images.pexels.com/photos/259027/pexels-photo-259027.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'type'        => 'found',
                'status'      => 'resolved',
                'created_at'  => $now->copy()->subDays(10)->subHours(6),
                'updated_at'  => $now->copy()->subDays(8)->subHours(3),
            ],
            [
                'user_id'     => 2,
                'name'        => 'Earbuds putih dalam casing bening',
                'description' => 'Earbuds tertinggal setelah praktikum komputer. Casing ada goresan kecil di bagian atas.',
                'location'    => 'Laboratorium Komputer 1',
                'image'       => 'https://images.pexels.com/photos/3780681/pexels-photo-3780681.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'type'        => 'lost',
                'status'      => 'pending',
                'created_at'  => $now->copy()->subDay()->subHours(5),
                'updated_at'  => $now->copy()->subDay()->subHours(5),
            ],
            [
                'user_id'     => 3,
                'name'        => 'Jam tangan analog strap cokelat',
                'description' => 'Jam tangan ditemukan di musala kampus. Tidak ada ukiran nama pada bagian belakang.',
                'location'    => 'Area wudu Musala Kampus',
                'image'       => 'https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg?auto=compress&cs=tinysrgb&w=1200',
                'type'        => 'found',
                'status'      => 'pending',
                'created_at'  => $now->copy()->subHours(14),
                'updated_at'  => $now->copy()->subHours(14),
            ],
        ];

        DB::table('items')->insert($items);
    }
}
