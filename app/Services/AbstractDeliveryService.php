<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

abstract class AbstractDeliveryService implements DeliveryService
{
    protected string $url;

    abstract protected function prepareRequestData(array $packageData, array $recipientData): array;

    public function sendDeliveryData(array $packageData, array $recipientData): void
    {
        $requestData = $this->prepareRequestData($packageData, $recipientData);

        try {
            $response = $this->sendRequest($this->url, $requestData);

            if ($this->isResponseSuccessful($response)) {
                $this->handleSuccessfulResponse($response);
            } else {
                $this->handleFailedResponse($response);
            }
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    protected function sendRequest($url, $data)
    {
        $client = new Client;
        $response = $client->post($url, ['json' => $data]);

        return json_decode($response->getBody(), true);
    }

    protected function isResponseSuccessful($response): bool
    {
        return isset($response['status']) && $response['status'] === 'success';
    }

    protected function handleSuccessfulResponse($response)
    {
        // Logic for successful response
    }

    protected function handleFailedResponse($response)
    {
        // Logic for failed response
    }

    protected function handleException(\Exception $e): void
    {
        Log::error('Exception during delivery: ' . $e->getMessage());
    }
}
