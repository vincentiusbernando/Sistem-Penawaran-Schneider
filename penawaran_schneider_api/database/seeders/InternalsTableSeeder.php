<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internal;

class InternalsTableSeeder extends Seeder
{
    public function run()
    {
        Internal::insert([
            ['nama' => 'Admin User', 'email' => 'admin', 'password' => bcrypt('123'), 'teams_id' => 1, 'role' => 'admin', 'akses_ganti' => 'all'],
            ['nama' => 'Supervisor User', 'email' => 'supervisor', 'password' => bcrypt('123'), 'teams_id' => 2, 'role' => 'tim', 'akses_ganti' => 'tim'],
            ['nama' => 'Team Member User', 'email' => 'teammember', 'password' => bcrypt('123'), 'teams_id' => 3, 'role' => 'spv', 'akses_ganti' => 'none'],
            // Add more seed data as needed
        ]);
    }
}
