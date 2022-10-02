<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Jobs\InformationRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $orders = Order::with(['orderDetails.product'])
            ->where('orders.user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'DESC')->get();

        return Inertia::render('Order/Orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Example queue job for pending orders.
     *
     * @return void
     */
    public function sync()
    {
        $failedOrders = Order::where('status', '=', 'PENDING')->get();
        foreach ($failedOrders as $order)
            $this->dispatch(new InformationRequest($order));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        $order = new Order;
        $order['customer_name'] = $user['name'];
        $order['customer_email'] = $user['email'];
        $order['user_id'] = $user['id'];
        $cart = DB::table('shopping_carts')
            ->join('products', 'shopping_carts.product_id', '=', 'products.id')
            ->select('products.*', 'shopping_carts.id AS cart_id')
            ->where('shopping_carts.user_id', '=', $user->id)
            ->get();

        return Inertia::render('Order/Order', [
            'order' => $order,
            'cart' => $cart,
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
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'customer_email' => 'required|string',
            'customer_mobile' => 'required|string',
            'status' => 'required|string',
            'user_id' => 'required|integer',
            'cart' => 'required|array',
        ]);

        if ($validator->fails()) {
            return json_encode(ResponseHelper::response('2',$validator->errors()->first(),[]));
        }

        $validated = $validator->validated();

        $order = $request->user()->orders()->create($validated);

        collect($request->only(['cart']))
            ->map(function ($array) use ($order) {
                foreach ($array as $item) {
                    $orderDetail = new OrderDetail([
                        'quantity' => 1,
                        'product_id' => $item['id'],
                        'order_id' => $order->id,
                    ]);
                    $orderDetail->save();
                    ShoppingCart::find($item['cart_id'])->delete();
                }
            });

        return redirect(route('orders.checkout', [$order, 'placetopay']));
    }

}
