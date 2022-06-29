<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;


class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            'name'=>'Burger',
            'available_stock'=>10
        ]);   
        Product::insert([
            'name'=>'Fries',
            'available_stock'=>54
        ]);   
        Product::insert([
            'name'=>'Ice Cream',
            'available_stock'=>100
        ]);   
    }
}
