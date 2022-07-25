<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert('INSERT INTO pelapak VALUES (?, ?, ?, ?)', [1, 'Pelapak', 'pelapak@gmail.com', bcrypt('pelapak123')]);
        DB::insert('INSERT INTO pelanggan VALUES (?, ?, ?, ?)', [1, 'Pelanggan', 'pelanggan@gmail.com', bcrypt('pelanggan123')]);
    }
}
