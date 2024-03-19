<?php
require_once 'api_client.php';

class BoxpayClient {
    private $api_client;
    private $merchant_id;

    public function __construct(string $api_key, string $merchant_id, string $env = null) {
        $this->api_client = new ApiClient($api_key, $env);
        $this->merchant_id = $merchant_id;
    }

   // Example method for making API calls through the ApiClient
    public function create_checkout_session($request_data) {
        
    }
}
