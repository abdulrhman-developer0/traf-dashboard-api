<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMobService
{
    protected $apiKey;
    protected $baseUrl;
    protected $integrationId;
    protected $hmacSecret;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->baseUrl = 'https://ksa.paymob.com/api';
        $this->integrationId = config('services.paymob.integration_id');
        $this->hmacSecret = config('services.paymob.hmac_secret');
    }

    /**
     * Create a payment order in PayMob
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createPaymentOrder($data)
    {
        //dd($this->apiKey);

        try {
            
            // Step 1: Authentication Request
            $authResponse = Http::post($this->baseUrl . '/auth/tokens', [
                'api_key' => $this->apiKey
            ])->throw()->json();

            $authToken = $authResponse['token'];

            // Step 2: Order Registration
            $orderResponse = Http::withToken($authToken)
                ->post($this->baseUrl . '/ecommerce/orders', [
                    'amount_cents' => $data['amount'],
                    'merchant_order_id' => uniqid($data['order_id'] . '_'),
                ])->throw()->json();


            // Step 3: Payment Key Request
            $paymentKeyResponse = Http::withToken($authToken)
                ->post($this->baseUrl . '/acceptance/payment_keys', [
                    'amount_cents' => $data['amount'],
                    'expiration' => 3600,
                    'order_id' => $orderResponse['id'],
                    'billing_data' => [
                        'first_name' => $data['customer']['first_name'],
                        'last_name'   => $data['customer']['first_name'],
                        'email' => $data['customer']['email'],
                        'phone_number' => $data['customer']['phone'],
                        'country'   => "NA",
                        'city'      => "NA",
                        'floor'     => "NA",
                        'apartment' => "NA",
                        'street'    => "NA",
                        'building'  => "NA"
                    ],
                    'currency'      => $data['currency'],
                    'integration_id' => $this->integrationId,
                    'lock_order_when_paid' => true
                ])->throw()->json();

            return [
                'id' => $orderResponse['id'],
                'payment_url' => "https://ksa.paymob.com/iframe/" . $paymentKeyResponse['token'],
                'token'       => $paymentKeyResponse['token']
            ];
        } catch (\Exception $e) {
            Log::error('PayMob API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Validate PayMob webhook data
     *
     * @param array $data
     * @return array|false
     */
    public function validateWebhook($data)
    {
        $calculatedHmac = $this->calculateHmac($data);
        if ($calculatedHmac !== $data['hmac']) {
            return false;
        }

        return [
            'success' => $data['success'] === 'true',
            'order_id' => $data['order'],
            'transaction_id' => $data['id'],
            'amount_cents' => $data['amount_cents']
        ];
    }

    /**
     * Calculate HMAC for webhook validation
     *
     * @param array $data
     * @return string
     */
    protected function calculateHmac($data)
    {
        $concatenatedString = $data['amount_cents'] .
            $data['created_at'] .
            $data['currency'] .
            $data['error_occured'] .
            $data['has_parent_transaction'] .
            $data['id'] .
            $data['integration_id'] .
            $data['is_3d_secure'] .
            $data['is_auth'] .
            $data['is_capture'] .
            $data['is_refunded'] .
            $data['is_standalone_payment'] .
            $data['is_voided'] .
            $data['order'] .
            $data['owner'] .
            $data['pending'] .
            $data['source_data_pan'] .
            $data['source_data_sub_type'] .
            $data['source_data_type'] .
            $data['success'];

        return hash_hmac('sha512', $concatenatedString, $this->hmacSecret);
    }
}
