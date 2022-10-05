<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepositoryImpl implements \App\Interfaces\ProductRepository
{

    public function products(): Collection
    {
        return Product::all();
    }
}
