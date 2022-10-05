<?php

namespace App\Repositories;

use App\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ShoppingCartRepositoryImpl implements \App\Interfaces\ShoppingCartRepository
{

    public function cart(): Collection
    {
        return ShoppingCart::with(['product'])
            ->where('user_id', '=', Auth::user()->id)
            ->get();
    }

    public function saveItem(array $newCart): string
    {
        $newCart['user_id'] = Auth::user()->id;
        $item = ShoppingCart::create($newCart);
        return ($item) ? 'Product added!' : 'Error creating product';
    }

    public function removeItem(int $id): array
    {
        $item = ShoppingCart::find($id);
        $result = $item->delete();
        return [$result, ($result == 0) ? 'Error deleting product' : 'Product deleted!'];
    }
}
