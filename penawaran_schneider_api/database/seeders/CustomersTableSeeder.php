<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        Customer::insert([
            ['nama' => 'John Doe', 'email' => 'johndoe', 'password' => bcrypt('123'), 'perusahaans_id' => 1],
            ['nama' => 'Jane Smith', 'email' => 'janesmith', 'password' => bcrypt('123'), 'perusahaans_id' => 1],
            // Add more seed data as needed
        ]);
    }
}
