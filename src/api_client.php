<?php
// api_client.php

require_once 'exceptions.php';

class ApiClient {
    private $apiKey;
    private $baseUrl;

    public function __construct($apiKey, $env = null) {
        $this->apiKey = $apiKey;
        $this->baseUrl = $this->getBaseUrl($env);
    }

    private function getBaseUrl($env) {
        return ($env && strtolower($env) === 'sandbox') ? 'https://sandbox-apis.boxpay.tech/v0' : 'https://apis.boxpay.in/v0';
    }

    public function makeRequest($method, $endpoint, $data = null) {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $url = $this->baseUrl . '/' . $endpoint;
        $response = $this->sendRequest($method, $url, $headers, $data);

        if ($response['status_code'] != 201) {
            $this->handleApiError($response);
        }

        return json_decode($response['body'], true);
    }

    private function sendRequest($method, $url, $headers, $data) {
        $options = [
            'http' => [
                'header' => $this->buildHeaders($headers),
                'method' => $method,
                'content' => json_encode($data),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return [
            'status_code' => $http_response_header[0] ?? null,
            'body' => $result,
        ];
    }

    private function buildHeaders($headers) {
        return implode("\r\n", array_map(function ($key, $value) {
            return "$key: $value";
        }, array_keys($headers), $headers));
    }

    private function handleApiError($response) {
        // Error handling logic...
    }
}
