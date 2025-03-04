<?php

namespace App\Enums;

enum BotReplyType: string
{
    case CANNED_RESPONSE = 'canned_response';
    case EXACT_MATCH     = 'exact_match';
    case CONTAINS        = 'contains';

}
