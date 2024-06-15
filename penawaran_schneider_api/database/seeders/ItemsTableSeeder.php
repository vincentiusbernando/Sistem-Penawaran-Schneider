<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        // Product::insert([
        //     ['id' => 1, 'ref' => 'REF001', 'material' => 'Material A', 'description' => 'Description A', 'price' => 100.00, 'bu' => 'DE', 'activity' => 'DPLVP', 'standard_discounts_mpg' => 'ZI', 'type' => 'ED', 'sub_type' => 'ED', 'time' => 'PBQ', 'activity_detail' => 'DPLVP LVC', 'stock' => 50],
        //     ['id' => 2, 'ref' => 'REF002', 'material' => 'Material B', 'description' => 'Description B', 'price' => 150.00, 'bu' => 'DE', 'activity' => 'DPLVP', 'standard_discounts_mpg' => 'ZI', 'type' => 'ED', 'sub_type' => 'ED', 'time' => 'PBQ', 'activity_detail' => 'DPLVP LVC', 'stock' => 75],
        //     ['id' => 3, 'ref' => 'REF003', 'material' => 'Material C', 'description' => 'Description C', 'price' => 200.00, 'bu' => 'DE', 'activity' => 'DPLVP', 'standard_discounts_mpg' => 'ZI', 'type' => 'ED', 'sub_type' => 'ED', 'time' => 'PBQ', 'activity_detail' => 'DPLVP LVC', 'stock' => 100],
        //     // Add more seed data as needed
        // ]);
        for ($i = 5; $i <= 1000; $i++) {
            $products[] = [
                'id' => $i,
                'ref' => sprintf('REF%03d', $i),
                'material' => 'Material ' . chr(64 + ($i % 26)),
                'description' => 'Description ' . chr(64 + ($i % 26)),
                'price' => mt_rand(100, 500) + mt_rand(0, 99) / 100,
                'bu' => 'DE',
                'activity' => 'DPLVP',
                'standard_discounts_mpg' => 'ZI',
                'type' => 'ED',
                'sub_type' => 'ED',
                'time' => 'PBQ',
                'activity_detail' => 'DPLVP LVC',
                'stock' => mt_rand(0, 200),
            ];
        }

        Product::insert($products);
    }
}
