<?php

namespace App\Console\Commands;

use App\Constants\AppConfig;
use App\Factory\FactoryApiWalletGateway;
use App\Interfaces\OrderRepository;
use App\Jobs\InformationRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateStatusGateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updategateway';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updated orders status gateway';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(OrderRepository $orderRepository)
    {
        foreach ($orderRepository->pendingOrders() as $order) {
            $paymentGateway = app()->make(FactoryApiWalletGateway::class, [$order, $order->requestId, $order->id, AppConfig::GATEWAY]);
            Log::info($paymentGateway->requestStatus());
        }
        return Command::SUCCESS;
    }
}
