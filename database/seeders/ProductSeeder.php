<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table("products")->insert([
                [
                    'title' => 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops',
                    'description' => 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday',
                    'image' => 'https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg',
                    'price' => 20800,
                ], [
                    'title' => 'Mens Casual Premium Slim Fit T-Shirts',
                    'description' => 'Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing. And Solid stitched shirts with round neck made for durability and a great fit for casual fashion wear and diehard baseball fans. The Henley style round neckline includes a three-button placket.',
                    'image' => 'https://fakestoreapi.com/img/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg',
                    'price' => 26990,
                ], [
                    'title' => 'Mens Cotton Jacket',
                    'description' => 'great outerwear jackets for Spring/Autumn/Winter, suitable for many occasions, such as working, hiking, camping, mountain/rock climbing, cycling, traveling or other outdoors. Good gift choice for you or your family member. A warm hearted love to Father, husband or son in this thanksgiving or Christmas Day.',
                    'image' => 'https://fakestoreapi.com/img/71li-ujtlUL._AC_UX679_.jpg',
                    'price' => 39990,
                ], [
                    'title' => 'Mens Casual Slim Fit',
                    'description' => 'The color could be slightly different between on the screen and in practice. / Please note that body builds vary by person, therefore, detailed size information should be reviewed below on the product description.',
                    'image' => 'https://fakestoreapi.com/img/71YXzeOuslL._AC_UY879_.jpg',
                    'price' => 69000,
                ], [
                    'title' => 'John Hardy Women\'s Legends Naga Gold & Silver Dragon Station Chain Bracelet',
                    'description' => 'From our Legends Collection, the Naga was inspired by the mythical water dragon that protects the ocean\'s pearl. Wear facing inward to be bestowed with love and abundance, or outward for protection.',
                    'image' => 'https://fakestoreapi.com/img/71pWzhdJNwL._AC_UL640_QL65_ML3_.jpg',
                    'price' => 87650,
                ], [
                    'title' => 'Solid Gold Petite Micropave',
                    'description' => 'Satisfaction Guaranteed. Return or exchange any order within 30 days.Designed and sold by Hafeez Center in the United States. Satisfaction Guaranteed. Return or exchange any order within 30 days.',
                    'image' => 'https://fakestoreapi.com/img/61sbMiUnoGL._AC_UL640_QL65_ML3_.jpg',
                    'price' => 648000,
                ]
            ]
        );
    }
}
