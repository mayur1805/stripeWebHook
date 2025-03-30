<?php

if (!function_exists('successResponse')) {
    function successResponse($message, $data = [])
    {
        return [
            "status" => true,
            "data" => $data,
            "message" => $message
        ];
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($message)
    {
        return [
            "status" => false,
            "message" => $message
        ];
    }
}