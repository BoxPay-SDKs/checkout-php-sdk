<?php
require_once 'exceptions.php';
require_once 'models.php';
require_once 'checkout_client.php';

$apiKey = 'your_api_key_here';
$env = 'sandbox';

$checkoutClient = new CheckoutClient($apiKey, $env);

try {
    $requestData = new CheckoutSessionRequest(
        new Context(new LegalEntity(), 'US', '123456'),
        'CREDIT_CARD',
        new Shopper('John', 'Doe', 'Male', '+123456789', 'john.doe@example.com', '12345', new DeliveryAddress('123 Main St', 'City', 'State', 'US', '12345', 'Country')),
        new Order(),
        'http://example.com/return',
        'http://example.com/back',
        'http://example.com/notify',
        new Money(100.0, 'USD'),
        new Descriptor('MerchantName', 'Purchase'),
        new BillingAddress('456 Billing St', 'Billing City', 'Billing State', 'US', '54321', 'Billing Country'),
        3600,
        ['key' => 'value']
    );

    $checkoutSessionResponse = $checkoutClient->createCheckoutSession($requestData);

    echo "Checkout Session Response:\n";
    print_r($checkoutSessionResponse);

    $token = 'your_payment_token_here';
    $inquiryDetails = new TransactionInquiryDetails();
    $transactionInquiryResponse = $checkoutClient->verifyPayment($token, $inquiryDetails);

    echo "Transaction Inquiry Response:\n";
    print_r($transactionInquiryResponse);
} catch (CheckoutSDKError $e) {
    echo "SDK Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

