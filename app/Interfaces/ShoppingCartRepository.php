<?php

namespace App\Interfaces;

use \Illuminate\Support\Collection;

interface ShoppingCartRepository
{
    public function cart(): Collection;

    public function saveItem(array $newCart): string;

    public function removeItem(int $id): array;
}
