<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     * Start permissions, group and user
     */
    public function run(): void {
        if (Product::first()) return;

        $products = [
            [
                'sku' => 'PRO1',
                'name' => 'Produto 1',
                'description' => 'Descrição Produto 1',
                'cost_price' => 2.0,
                'sale_price' => 5.0
            ],
            [
                'sku' => 'PRO2',
                'name' => 'Produto 2',
                'description' => 'Descrição Produto 2',
                'cost_price' => 4.0,
                'sale_price' => 6.5
            ],
            [
                'sku' => 'PRO3',
                'name' => 'Produto 3',
                'description' => 'Descrição Produto 3',
                'cost_price' => 3.0,
                'sale_price' => 5.3
            ],
            [
                'sku' => 'PRO4',
                'name' => 'Produto 4',
                'description' => 'Descrição Produto 4',
                'cost_price' => 2.2,
                'sale_price' => 5.8
            ],
            [
                'sku' => 'PRO5',
                'name' => 'Produto 5',
                'description' => 'Descrição Produto 5',
                'cost_price' => 2.1,
                'sale_price' => 5.8
            ]
        ];

        foreach ($products as $p) {
            $product = Product::create($p);
            $product->save();
        }
    }
}
