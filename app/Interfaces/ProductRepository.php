<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepository
{
    public function products(): Collection;
}
