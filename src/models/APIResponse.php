<?php

namespace Src\models;

/**
 * Class APIResponse
 * @package Src\models
 */
class APIResponse {

    private int $statusCode;
    private string $message;

    /**
     * APIResponse constructor.
     * @param $statusCode
     * @param $message
     */
    public function __construct($statusCode, $message) {
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    /**
     * Returns internal status code
     * @return int
     */
    public function getStatusCode (): int {
        return $this->statusCode;
    }

    /**
     * Returns internal message
     * @return string
     */
    public function getMessage (): string {
        return $this->message;
    }

}
