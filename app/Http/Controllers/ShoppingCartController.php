<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $cart = DB::table('products')
            ->join('shopping_carts', 'products.id', '=', 'shopping_carts.product_id')
            ->select('products.*', 'shopping_carts.id AS cart_id')
            ->where('shopping_carts.user_id', '=', Auth::user()->id)->get();


        return Inertia::render('Shop/ShoppingCart', [
            'shoppingCarts' => $cart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return false|string
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return json_encode(ResponseHelper::response('2', $validator->errors()->first(), []));
        }

        $validated = $validator->validated();
        $request->user()->shoppingCarts()->create($validated);
        return json_encode(ResponseHelper::response('1', 'Product added!', []));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return false|string
     */
    public function destroy(int $id)
    {
        $shoppingCart = ShoppingCart::find($id);
        $result = $shoppingCart->delete();

        return json_encode(
            ($result == 0) ? ResponseHelper::response('2', 'Error deleting product', []) :
                ResponseHelper::response('1', 'Product deleted!', [])
        );
    }
}
