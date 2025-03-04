<?php

namespace App\Enums;

enum StatusEnum: string
{
    case DRAFT      = 'draft';
    case PENDING    = 'pending';
    case HOLD       = 'hold';
    case ANSWERED   = 'answered';
    case ACTIVE     = 'active';
    case INACTIVE   = 'inactive';
    case REJECTED   = 'rejected';
    case APPROVED   = 'approved';
    case MISSED     = 'missed';
    case UNREACHED  = 'unreached';
    case CONNECTED  = 'connected';
    case RECEIVED   = 'received';
    case COMPLETE   = 'complete';
    case CANCELED   = 'canceled';
    case PROCESSED  = 'processed';
    case QUEUED     = 'queued';
    case STOPPED    = 'stopped';
    case UNKNOWN    = 'unknown';
    case EXECUTED   = 'executed';

}