<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penawaran;

class PenawaransTableSeeder extends Seeder
{
    public function run()
    {
        Penawaran::insert([
            ['tgl' => '2024-04-27 12:00:00', 'customers_id' => 1, 'teams_id' => 1, 'internals_id' => 1],
            ['tgl' => '2024-04-28 12:00:00', 'customers_id' => 2, 'teams_id' => 2, 'internals_id' => 2],
        ]);
    }
}
