<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('cart_items')->where('session_id', 'initial_session')->count() === 0) {
            DB::table('cart_items')->insert([
                [
                    'session_id'   => 'initial_session',
                    'product_id'   => 1,
                    'product_name' => 'Product 1',
                    'price'        => 100.15,
                    'quantity'     => 1,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'session_id'   => 'initial_session',
                    'product_id'   => 2,
                    'product_name' => 'Product 2',
                    'price'        => 1005.14,
                    'quantity'     => 2,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'session_id'   => 'initial_session',
                    'product_id'   => 3,
                    'product_name' => 'Product 3',
                    'price'        => 10.99,
                    'quantity'     => 15,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
            ]);
        }
    }
}
