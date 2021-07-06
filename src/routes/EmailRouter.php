<?php

class EmailRouter {

    public function __construct() {

    }

    public function handleRequest($path, $method, $params) {
        return new APIResponse(200,'OK');
        return new Error('This route is not implemented', 500);
    }

}