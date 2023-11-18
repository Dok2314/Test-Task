<?php

namespace App\Services;

class NovaPoshtaService extends AbstractDeliveryService
{
    protected string $url = 'https://novaposhta.test/api/delivery';

    protected function prepareRequestData(array $packageData, array $recipientData): array
    {
        return [
            'customer_name' => $recipientData['customer_name'],
            'phone_number' => $recipientData['phone_number'],
            'email' => $recipientData['email'],
            'sender_address' => config('app.sender_address'),
            'delivery_address' => $recipientData['delivery_address'],
            'width' => $packageData['width'],
            'height' => $packageData['height'],
            'length' => $packageData['length'],
            'weight' => $packageData['weight'],
        ];
    }
}
