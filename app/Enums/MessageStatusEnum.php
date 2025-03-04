<?php

namespace App\Enums;

enum MessageStatusEnum: string
{
    case SCHEDULED   = 'scheduled';
    case SENDING   = 'sending';
    case SENT   = 'sent';//queued
    case DELIVERED   = 'delivered';
    case READ   = 'read';
    case FAILED   = 'failed';
  
}
