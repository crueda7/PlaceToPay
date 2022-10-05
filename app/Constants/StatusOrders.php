<?php

namespace App\Constants;

use App\Interfaces\StatusOrder;

enum StatusOrders implements StatusOrder
{

    case OK;
    case FAILED;
    case APPROVED;
    case REJECTED;
    case PENDING;
    case PENDING_VALIDATION;
    case UNAUTHORIZED;
    case REFUNDED;
    case ERROR;

    public function status(): int
    {
        return match ($this) {
            self::FAILED, self::UNAUTHORIZED => 401,
            self::OK, self::APPROVED, self::PENDING, self::REJECTED,
            self::PENDING_VALIDATION => 200,
            self::REFUNDED => 404,
            self::ERROR => 500,
        };
    }
}
