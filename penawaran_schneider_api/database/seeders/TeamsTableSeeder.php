<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {
        Team::insert([
            ['nama' => 'Team 1'],
            ['nama' => 'Team 2'],
            ['nama' => 'Team 3'],
            // Add more seed data as needed
        ]);
    }
}
