<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemHasPenawaran;
use Illuminate\Support\Facades\DB;

class ItemsHasPenawaransTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products_has_penawarans')->insert([
            ['products_id' => 1, 'penawarans_id' => 1, 'quantity' => 10, 'unit_price' => 100.00, 'total_price' => 1000.00, 'delivery_time' => 'Next week', 'remarks' => 'No remarks', 'standard_discount' => 5, 'additional_discount' => 2, 'cash_discount' => 3, 'coef' => 1.5, 'pl_aft_coef' => 150.00, 'price_list_excl' => 225.00, 'status' => 'Done'],
            ['products_id' => 2, 'penawarans_id' => 2, 'quantity' => 5, 'unit_price' => 150.00, 'total_price' => 750.00, 'delivery_time' => 'Next month', 'remarks' => 'Urgent delivery required', 'standard_discount' =>1, 'additional_discount' => 3, 'cash_discount' => 5, 'coef' => 2.0, 'pl_aft_coef' => 300.00, 'price_list_excl' => 600.00, 'status' => 'On Process']
            // Add more seed data as needed
        ]);
    }
}
