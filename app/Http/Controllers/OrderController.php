<?php

namespace App\Http\Controllers;

use App\Constants\AppConfig;
use App\Helpers\ControllerHelper;
use App\Helpers\ResponseHelper;
use App\Interfaces\OrderRepository;
use App\Interfaces\ShoppingCartRepository;
use App\Jobs\InformationRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    private OrderRepository $orderRepository;
    private ShoppingCartRepository $shoppingCartRepository;

    public function __construct(OrderRepository $orderRepository, ShoppingCartRepository $shoppingCartRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->shoppingCartRepository = $shoppingCartRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Order/Orders', [
            'orders' => $this->orderRepository->orders(),
        ]);
    }

    /**
     * Example queue job for pending orders.
     *
     * @return void
     */
    public function sync(): void
    {
        foreach ($this->orderRepository->pendingOrders() as $order)
            $this->dispatch(new InformationRequest($order));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Order/Order', [
            'order' => $this->orderRepository->order(),
            'cart' => $this->shoppingCartRepository->cart(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return false|string
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = ControllerHelper::validateRequest($request, [
            'customer_name' => 'required|string',
            'customer_email' => 'required|string',
            'customer_mobile' => 'required|string',
            'status' => 'required|string',
            'user_id' => 'required|integer',
            'cart' => 'required|array',
        ]);

        if ($validator->fails()) {
            return ControllerHelper::encodeStringResponse(ResponseHelper::Error($validator->errors()->first()));
        }

        $order = $this->orderRepository->save($validator->validated());

        return redirect(route('orders.checkout', [$order]));
    }

}
