<?php

namespace App\Enums;

enum SaleStatusEnum: string {
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELED = 'canceled';
    case REFUNDED = 'refunded';
    case FAIL = 'fail';
}
