<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandardDiscount;

class StandardDiscountsTableSeeder extends Seeder
{
    public function run()
    {
        StandardDiscount::insert([
            ['mpg' => 'ZI', 'activity' => 'Activity 1', 'description' => 'Description 1', 'basic_disc' => 21],
            ['mpg' => 'YK', 'activity' => 'Activity 2', 'description' => 'Description 2', 'basic_disc' => 33],
            ['mpg' => 'YQ', 'activity' => 'Activity 3', 'description' => 'Description 3', 'basic_disc' => 22],
            // Add more seed data as needed
        ]);
    }
}
