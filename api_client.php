<?php

require_once 'exceptions.php'; // Adjust the path accordingly

class ApiClient {
    private $apiKey;
    private $baseUrl;

    public function __construct($apiKey, $env = null) {
        $this->apiKey = $apiKey;
        $this->baseUrl = $this->getBaseUrl($env);
    }

    private function getBaseUrl($env) {
        if ($env && strtolower($env) === 'sandbox') {
            return 'https://sandbox-apis.boxpay.tech/v0';
        } else {
            return 'https://apis.boxpay.in/v0';
        }
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
        $headerStrings = [];
        foreach ($headers as $key => $value) {
            $headerStrings[] = "$key: $value";
        }

        return implode("\r\n", $headerStrings);
    }

    private function handleApiError($response) {
        $statusCode = $response['status_code'];
        $errorCode = $this->getJsonValue($response['body'], 'errorCode', '');
        $message = $this->getJsonValue($response['body'], 'message', 'Unknown error');

        if ($statusCode == 401) {
            throw new AuthenticationError($message);
        } elseif ($statusCode == 400) {
            if ($errorCode == 'invalid_parameter') {
                $fieldErrorItems = $this->getJsonValue($response['body'], 'fieldErrorItems', []);
                if ($fieldErrorItems) {
                    foreach ($fieldErrorItems as $fieldError) {
                        if ($fieldError['fieldErrorCode'] == 'required_value') {
                            throw new InvalidParameterError($fieldError['message']);
                        } elseif ($fieldError['fieldErrorCode'] == 'invalid_value') {
                            throw new InvalidParameterError($fieldError['message']);
                        }
                    }
                }
            } else {
                // Handle other 400 errors if needed
            }
        } elseif ($statusCode == 404) {
            throw new ResourceNotFoundError('Transaction', $message);
        } elseif ($statusCode == 504) {
            throw new TimeoutError();
        } elseif ($statusCode == 422) {
            if ($errorCode == 'payment_declined') {
                throw new PaymentDeclinedError($message);
            }
        } else {
            throw new APIError($statusCode, $errorCode, $message);
        }
    }

    private function getJsonValue($jsonString, $key, $default) {
        $jsonData = json_decode($jsonString, true);
        return $jsonData[$key] ?? $default;
    }
}

?>
