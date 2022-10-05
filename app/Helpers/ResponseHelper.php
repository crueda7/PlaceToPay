<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function Error(string $message): array
    {
        return array('status' => '2', 'message' => $message, 'data' => []);
    }

    public static function Success(string $message, array $data): array
    {
        return array('status' => '1', 'message' => $message, 'data' => $data);
    }
}
