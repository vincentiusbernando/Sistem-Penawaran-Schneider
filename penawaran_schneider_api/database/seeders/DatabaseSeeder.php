<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TeamsTableSeeder::class,
            CustomersTableSeeder::class,
            InternalsTableSeeder::class,
            StandardDiscountsTableSeeder::class,
            ItemsTableSeeder::class,
            PenawaransTableSeeder::class,
            ItemsHasPenawaransTableSeeder::class,
        ]);
    }
}
