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
        $types = ['lost', 'found'];
        $statuses = ['pending', 'resolved'];

        $now = Carbon::now();
        $items = [];

        foreach ($types as $type) {
            foreach ($statuses as $status) {
                $items[] = [
                    'user_id'    => 1,
                    'name'       => "Laporan Barang ({$type}, {$status})",
                    'description'=> "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
                    'location'   => "Lorem Ipsum",
                    'image'      => "https://res.cloudinary.com/dn2tic45s/image/upload/v1741420350/cld-sample-5.jpg",
                    'type'       => $type,
                    'status'     => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('items')->insert($items);
    }
}