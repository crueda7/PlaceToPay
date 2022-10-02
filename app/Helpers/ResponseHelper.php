<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function response(int $status, string $message, array $data): array
    {
        return array('status' => $status, 'message' => $message, 'data' => $data);
    }
}
