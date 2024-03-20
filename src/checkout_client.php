<?php
require_once 'exceptions.php'; // Adjust the path accordingly
require_once 'models.php'; // Adjust the path accordingly
require_once 'boxpay_client.php'; // Adjust the path accordingly

class CheckoutClient {
    private $boxpayClient;

    public function __construct($apiKey, $env) {
        $this->boxpayClient = new BoxpayClient($apiKey, $env);
    }

    public function createCheckoutSession($requestData) {
        try {
            $response = $this->boxpayClient->getApiClient()->makeRequest('POST', 'sessions', ['data' => $requestData->toArray()]);
            return new CheckoutSessionResponse($response);
        } catch (APIError $e) {
            $this->boxpayClient->getApiClient()->handleAPIError($e);
        }
    }

    public function verifyPayment($token, $inquiryDetails) {
        $data = [
            "token" => $token,
            "inquiryDetails" => $inquiryDetails->toArray()
        ];

        try {
            $response = $this->boxpayClient->getApiClient()->makeRequest('POST', 'transactions/inquiries', ['data' => $data]);

            if ($response['statusCode'] == 200) {
                // Construct TransactionInquiryResponse instance
                return new TransactionInquiryResponse(
                    $response['operationId'],
                    $response['eventId'],
                    $response['status'],
                    $response['captureRequired'],
                    $response['legalEntityCode'],
                    $response['clientPosId'],
                    $response['orderId'],
                    $response['caller'],
                    $response['pspCode'],
                    $response['pspReference'],
                    $response['pspOperationItems'],
                    $response['timestamp'],
                    $response['authCode'],
                    $response['money'],
                    $response['actions'],
                    $response['additionalData'],
                    $response['pspErrorDetails'],
                    $response['shopper'],
                    $response['paymentMethod'],
                    $response['recurring']
                );
            } else {
                $this->handleNon200Response($response);
            }
        } catch (APIError $e) {
            $this->boxpayClient->getApiClient()->handleAPIError($e);
        }
    }

    private function handleNon200Response($response) {
        $statusCode = $response['statusCode'] ?? 500;
        $errorCode = $response['errorCode'] ?? '';
        $message = $response['message'] ?? 'Unknown error';

        echo $statusCode . "\n";

        if ($statusCode == 400 || $statusCode == 500) {
            if ($errorCode == 'invalid_body_fields') {
                $fieldErrorItems = $response['fieldErrorItems'] ?? [];

                if ($fieldErrorItems) {
                    $errorMessages = [];

                    foreach ($fieldErrorItems as $fieldError) {
                        $fieldMessage = $fieldError['message'] ?? 'Field validation error';
                        $errorMessages[] = $fieldMessage;
                        echo "Field Error: $fieldMessage\n";
                    }

                    echo "Overall Message: $message\n";
                    echo "Individual Field Errors: " . implode(', ', $errorMessages) . "\n";

                    throw new InvalidParameterError('', implode(', ', $errorMessages), $fieldErrorItems);
                }
            } else {
                throw new InvalidParameterError("Error Code: $errorCode, Message: $message");
            }
        } else {
            echo "Message: $message\n";
        }
    }
}

