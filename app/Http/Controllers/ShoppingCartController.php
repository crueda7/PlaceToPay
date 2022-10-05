<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\ResponseHelper;
use App\Interfaces\ShoppingCartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ShoppingCartController extends Controller
{
    private ShoppingCartRepository $shoppingCartRepository;

    public function __construct(ShoppingCartRepository $shoppingCartRepository)
    {
        $this->shoppingCartRepository = $shoppingCartRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Shop/ShoppingCart', [
            'shoppingCarts' => $this->shoppingCartRepository->cart()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return false|string
     * @throws ValidationException
     */
    public function store(Request $request): bool|string
    {
        $validator = ControllerHelper::validateRequest($request, [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return ControllerHelper::encodeStringResponse(ResponseHelper::Error($validator->errors()->first()));
        }

        $response = $this->shoppingCartRepository->saveItem($validator->validated());

        return ControllerHelper::encodeStringResponse(ResponseHelper::Success($response, []));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return false|string
     */
    public function destroy(int $id): bool|string
    {
        $result = $this->shoppingCartRepository->removeItem($id);
        return ControllerHelper::encodeStringResponse(
            ($result[0] == 0) ? ResponseHelper::Error($result[1]) : ResponseHelper::Success($result[1], [])
        );
    }
}
