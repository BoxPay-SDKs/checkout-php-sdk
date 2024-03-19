<?php

class CheckoutSDKError extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}

class APIError extends CheckoutSDKError {
    public $status_code;
    public $error_code;
    public $message;
    public $field_error_items;

    public function __construct($status_code, $error_code, $message, $field_error_items = null) {
        $this->status_code = $status_code;
        $this->error_code = $error_code;
        $this->message = $message;
        $this->field_error_items = $field_error_items ?? [];

        parent::__construct("API Error: Status Code $status_code, Error Code: $error_code, Message: $message");
    }
}

class InvalidParameterError extends CheckoutSDKError {
    public $parameter_name;
    public $field_error_items;

    public function __construct($parameter_name, $message = null, $field_error_items = null) {
        $this->parameter_name = $parameter_name;
        $this->field_error_items = $field_error_items ?? [];

        parent::__construct("Invalid parameter '$parameter_name'." . ($message ? " $message" : ""));
    }
}

class TimeoutError extends CheckoutSDKError {
    public function __construct($message = "Request to BoxPay API timed out") {
        parent::__construct($message);
    }
}

class AuthenticationError extends CheckoutSDKError {
    public function __construct($message = "Authentication failed") {
        parent::__construct($message);
    }
}

class ResourceNotFoundError extends CheckoutSDKError {
    public $resource_type;
    public $resource_id;

    public function __construct($resource_type, $resource_id) {
        parent::__construct("$resource_type with ID '$resource_id' not found");
    }
}

class PaymentDeclinedError extends CheckoutSDKError {
    public function __construct($reason = null) {
        $message = "Payment declined";
        if ($reason) {
            $message .= ": $reason";
        }
        parent::__construct($message);
    }
}

?>
