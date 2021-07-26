<?php

class APIResponse {

    private int $statusCode;
    private string $message;

    public function __construct($statusCode, $message) {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function getStatusCode (): int {
        return $this->statusCode;
    }

    public function getMessage (): string {
        return $this->message;
    }

}
