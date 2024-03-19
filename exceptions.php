<?php
// exceptions.php

class CheckoutSDKError extends Exception {}

class APIError extends CheckoutSDKError {
    public $statusCode;
    public $errorCode;
    public $fieldErrorItems;

    public function __construct($statusCode, $errorCode, $message, $fieldErrorItems = null) {
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->fieldErrorItems = $fieldErrorItems ?? [];

        parent::__construct("API Error: Status Code $statusCode, Error Code: $errorCode, Message: $message");
    }
}

class InvalidParameterError extends CheckoutSDKError {
    public $parameterName;
    public $fieldErrorItems;

    public function __construct($parameterName, $message = null, $fieldErrorItems = null) {
        $this->parameterName = $parameterName;
        $this->fieldErrorItems = $fieldErrorItems;

        parent::__construct("Invalid parameter '$parameterName'." . ($message ? " $message" : ""));
    }
}


class TimeoutError extends CheckoutSDKError {}

class AuthenticationError extends CheckoutSDKError {}

class ResourceNotFoundError extends CheckoutSDKError {
    public $resourceType;
    public $resourceId;

    public function __construct($resourceType, $resourceId) {
        parent::__construct("$resourceType with ID '$resourceId' not found");
    }
}

class PaymentDeclinedError extends CheckoutSDKError {}

