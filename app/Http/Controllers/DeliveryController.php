<?php

namespace App\Http\Controllers;

use App\Services\DeliveryService;
use App\Services\NovaPoshtaService;
use App\Services\UkrPoshtaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    protected DeliveryService $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function sendDeliveryData(Request $request)
    {
        $packageData = $request->only(['width', 'height', 'length', 'weight']);
        $recipientData = $request->only(['customer_name', 'phone_number', 'email', 'delivery_address']);

        $this->selectCourier();
        $this->sendData($packageData, $recipientData);
    }

    public function sendData(array $packageData, array $recipientData)
    {
        try {
            $this->deliveryService->sendDeliveryData($packageData, $recipientData);
        } catch (\Exception $e) {
            Log::error('Error sending delivery data: ' . $e->getMessage());
            Log::error($e);
        }
    }

    protected function selectCourier()
    {
        $selectedCourier = config('app.selected_courier');

        switch ($selectedCourier) {
            case 'nova_poshta':
                $this->deliveryService = app(NovaPoshtaService::class);
                break;
            case 'ukr_poshta':
                $this->deliveryService = app(UkrPoshtaService::class);
                break;
            default:
                Log::warning('Unknown courier selected: ' . $selectedCourier);
        }
    }
}
