<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $users = [
            [
                'name'  => 'Admin LostFound',
                'email' => 'admin@lostnfound.local',
                'role'  => 'admin',
            ],
            [
                'name'  => 'Raka Pratama',
                'email' => 'raka.pratama@mail.test',
                'role'  => 'user',
            ],
            [
                'name'  => 'Siti Aulia',
                'email' => 'siti.aulia@mail.test',
                'role'  => 'user',
            ],
            [
                'name'  => 'Bima Saputra',
                'email' => 'bima.saputra@mail.test',
                'role'  => 'user',
            ],
            [
                'name'  => 'Nadya Putri',
                'email' => 'nadya.putri@mail.test',
                'role'  => 'user',
            ],
        ];

        $rows = [];

        foreach ($users as $index => $user) {
            $createdAt = $now->copy()->subDays(20 - ($index * 3));

            $rows[] = [
                'name'       => $user['name'],
                'email'      => $user['email'],
                'password'   => bcrypt('password123'),
                'role'       => $user['role'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('users')->insert($rows);
    }
}
