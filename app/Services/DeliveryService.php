<?php

namespace App\Services;

interface DeliveryService
{
    public function sendDeliveryData(array $packageData, array $recipientData): void;
}
