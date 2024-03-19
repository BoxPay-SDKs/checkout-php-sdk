<?php

class LegalEntity {
    public $code;
}

class Context {
    public $legalEntity;
    public $countryCode;
    public $clientPosId;
    public $orderId;
    public $localeCode;

    public function __construct($legalEntity, $countryCode, $orderId, $localeCode = null, $clientPosId = null) {
        $this->legalEntity = $legalEntity;
        $this->countryCode = $countryCode;
        $this->clientPosId = $clientPosId;
        $this->orderId = $orderId;
        $this->localeCode = $localeCode;
    }
}

class DeliveryAddress {
    public $address1;
    public $address2;
    public $address3;
    public $city;
    public $state;
    public $countryCode;
    public $postalCode;
    public $countryName;

    public function __construct($address1, $city, $state, $countryCode, $postalCode, $countryName, $address2 = null, $address3 = null) {
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->address3 = $address3;
        $this->city = $city;
        $this->state = $state;
        $this->countryCode = $countryCode;
        $this->postalCode = $postalCode;
        $this->countryName = $countryName;
    }
}

class Item {
    public $id;
    public $itemName;
    public $description;
    public $quantity;
    public $manufacturer;
    public $brand;
    public $color;
    public $productUrl;
    public $imageUrl;
    public $categories;
    public $amountWithoutTax;
    public $taxAmount;
    public $taxPercentage;

    public function __construct($id, $itemName, $description, $quantity, $manufacturer, $amountWithoutTax, $taxAmount, $taxPercentage, $brand = null, $color = null, $productUrl = null, $imageUrl = null, $categories = []) {
        $this->id = $id;
        $this->itemName = $itemName;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->manufacturer = $manufacturer;
        $this->brand = $brand;
        $this->color = $color;
        $this->productUrl = $productUrl;
        $this->imageUrl = $imageUrl;
        $this->categories = $categories;
        $this->amountWithoutTax = $amountWithoutTax;
        $this->taxAmount = $taxAmount;
        $this->taxPercentage = $taxPercentage;
    }

    public function validate() {
        if ($this->quantity <= 0) {
            throw new Exception('Quantity must be positive');
        }

        if ($this->amountWithoutTax < 0 || $this->taxAmount < 0 || $this->taxPercentage < 0) {
            throw new Exception('Price and tax values cannot be negative');
        }
    }
}

class Order {
    public $voucherCode;
    public $shippingAmount;
    public $taxAmount;
    public $originalAmount;
    public $items;
}

class Money {
    public $amount;
    public $currencyCode;

    public function __construct($amount, $currencyCode) {
        $this->amount = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function amount_must_be_positive() {
        if ($this->amount <= 0) {
            throw new Exception('Amount must be positive');
        }
        return $this->amount;
    }
}

class BillingAddress {
    public $address1;
    public $address2;
    public $address3;
    public $city;
    public $state;
    public $countryCode;
    public $postalCode;
    public $countryName;

    public function __construct($address1, $city, $state, $countryCode, $postalCode, $countryName, $address2 = null, $address3 = null) {
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->address3 = $address3;
        $this->city = $city;
        $this->state = $state;
        $this->countryCode = $countryCode;
        $this->postalCode = $postalCode;
        $this->countryName = $countryName;
    }
}

class Shopper {
    public $firstName;
    public $lastName;
    public $gender;
    public $phoneNumber;
    public $email;
    public $uniqueReference;
    public $deliveryAddress;

    public function __construct($firstName, $lastName, $gender, $phoneNumber, $email, $uniqueReference, $deliveryAddress) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->uniqueReference = $uniqueReference;
        $this->deliveryAddress = $deliveryAddress;
    }
}

class ShopperAuthentication {
    public $threeDSAuthentication;

    public function __construct($threeDSAuthentication) {
        $this->threeDSAuthentication = $threeDSAuthentication;
    }
}

class Descriptor {
    public $line1;
    public $line2;

    public function __construct($line1, $line2) {
        $this->line1 = $line1;
        $this->line2 = $line2;
    }
}

class CheckoutSessionRequest {
    public $context;
    public $paymentType;
    public $shopper;
    public $order;
    public $frontendReturnUrl;
    public $frontendBackUrl;
    public $statusNotifyUrl;
    public $money;
    public $descriptor;
    public $billingAddress;
    public $expiryDurationSec;
    public $metadata;

    public function __construct($context, $paymentType, $shopper, $order, $frontendReturnUrl, $frontendBackUrl, $statusNotifyUrl, $money, $descriptor, $billingAddress, $expiryDurationSec = null, $metadata = null) {
        $this->context = $context;
        $this->paymentType = $paymentType;
        $this->shopper = $shopper;
        $this->order = $order;
        $this->frontendReturnUrl = $frontendReturnUrl;
        $this->frontendBackUrl = $frontendBackUrl;
        $this->statusNotifyUrl = $statusNotifyUrl;
        $this->money = $money;
        $this->descriptor = $descriptor;
        $this->billingAddress = $billingAddress;
        $this->expiryDurationSec = $expiryDurationSec;
        $this->metadata = $metadata;
    }

    public function to_dict() {
        return array_filter(get_object_vars($this), function ($value) {
            return $value !== null;
        });
    }
}

class CheckoutSessionResponse {
    public $statusCode;
    public $token;
    public $url;
    public $errorCode;
    public $message;
    public $timestamp;
    public $fieldErrorItems;
    public $retryable;
    public $reasonCode;

    public function __construct($statusCode, $token = null, $url = null, $errorCode = null, $message = null, $timestamp = null, $fieldErrorItems = null, $retryable = null, $reasonCode = null) {
        $this->statusCode = $statusCode;
        $this->token = $token;
        $this->url = $url;
        $this->errorCode = $errorCode;
        $this->message = $message;
        $this->timestamp = $timestamp;
        $this->fieldErrorItems = $fieldErrorItems;
        $this->retryable = $retryable;
        $this->reasonCode = $reasonCode;
    }

    public static function from_dict($response_dict) {
        $statusCode = $response_dict['statusCode'] ?? "";
        $token = $response_dict['token'] ?? '';
        $url = $response_dict['url'] ?? '';
        $errorCode = $response_dict['errorCode'] ?? '';
        $message = $response_dict['message'] ?? '';
        $timestamp = $response_dict['timestamp'] ?? '';
        $fieldErrorItems = $response_dict['fieldErrorItems'] ?? [];
        $retryable = $response_dict['retryable'] ?? false;
        $reasonCode = $response_dict['reasonCode'] ?? '';

        return new self(
            $statusCode,
            $token,
            $url,
            $errorCode,
            $message,
            $timestamp,
            $fieldErrorItems,
            $retryable,
            $reasonCode
        );
    }
}

// // Sample usage
// $legalEntity = new LegalEntity();
// $legalEntity->code = 'ABC';

// $context = new Context($legalEntity, 'US', '123456');

// $deliveryAddress = new DeliveryAddress('123 Main St', 'City', 'State', 'US', '12345', 'Country');

// $item = new Item('1', 'Item 1', 'Description 1', 2, 'Manufacturer 1', 20.0, 2.0, 10.0, 'Brand 1', 'Red', 'http://example.com/item1', 'http://example.com/item1.jpg', ['Category1', 'Category2']);
// $item->validate();

// $order = new Order();
// $order->voucherCode = 'VOUCHER123';
// $order->shippingAmount = 5.0;
// $order->taxAmount = 3.0;
// $order->originalAmount = 50.0;
// $order->items = [$item];

// $money = new Money(100.0, 'USD');
// $billingAddress = new BillingAddress('456 Billing St', 'Billing City', 'Billing State', 'US', '54321', 'Billing Country');

// $shopper = new Shopper('John', 'Doe', 'Male', '+123456789', 'john.doe@example.com', '12345', $deliveryAddress);
// $shopperAuthentication = new ShopperAuthentication('3DS_AUTH_TOKEN');

// $descriptor = new Descriptor('MerchantName', 'Purchase');

// $checkoutRequest = new CheckoutSessionRequest(
//     $context,
//     'CREDIT_CARD',
//     $shopper,
//     $order,
//     'http://example.com/return',
//     'http://example.com/back',
//     'http://example.com/notify',
//     $money,
//     $descriptor,
//     $billingAddress,
//     3600, // Expiry duration in seconds
//     ['key' => 'value']
// );

// // Convert to array for demonstration purposes
// $requestArray = $checkoutRequest->to_dict();

// // Display the array
// echo '<pre>';
// print_r($requestArray);
// echo '</pre>';

// $responseArray = [
//     'statusCode' => 200,
//     'token' => 'TOKEN123',
//     'url' => 'http://example.com/checkout',
//     'errorCode' => null,
//     'message' => 'Success',
//     'timestamp' => '2024-03-12T12:00:00',
//     'fieldErrorItems' => [],
//     'retryable' => false,
//     'reasonCode' => null,
// ];

// $response = CheckoutSessionResponse::from_dict($responseArray);

// // Display the response
// echo '<pre>';
// print_r($response);
// echo '</pre>';

// ?>
