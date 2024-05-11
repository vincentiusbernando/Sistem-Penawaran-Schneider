<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        Customer::insert([
            ['nama' => 'John Doe', 'username' => 'johndoe', 'password' => bcrypt('123'), "standard_discount" => 0, "additional_discount" => 0],
            ['nama' => 'Jane Smith', 'username' => 'janesmith', 'password' => bcrypt('123'), "standard_discount" => 33, "additional_discount" => 10],
            // Add more seed data as needed
        ]);
    }
}
