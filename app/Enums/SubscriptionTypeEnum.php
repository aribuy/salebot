<?php

namespace App\Enums;

enum SubscriptionTypeEnum: int
{
    case DEACTIVE = 0;
    case ACTIVE = 1;
    case PENDING = 2;
    case TERMINATED = 3;
    case REJECTED = 4;
}
