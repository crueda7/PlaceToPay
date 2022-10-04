<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserShoppingCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = DB::table('products')->take(1)->get();

        foreach ($products as $product) {
            DB::table('shopping_carts')->insert([
                'product_id' => $product->id,
                'user_id' => 2,
            ]);
        }
    }
}
