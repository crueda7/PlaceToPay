<?php

namespace App\Jobs;

use App\Constants\AppConfig;
use App\Factory\FactoryApiWalletGateway;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InformationRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    public Order $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$this->order, $this->order->requestId, $this->order->id, AppConfig::GATEWAY]);


        Log::info($paymentGateway->requestStatus());
    }
}
