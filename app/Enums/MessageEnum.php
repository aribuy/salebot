<?php

namespace App\Enums;

enum MessageEnum: string
{
    case TEXT   = 'text';
    case IMAGE   = 'image';
    case MEDIA   = 'media';
    case LOCATION   = 'location';
    case AUDIO   = 'audio';
    case VIDEO   = 'video';
    case DOCUMENT   = 'document';
    case CONTACTS   = 'contacts';
    case CONTACT   = 'contact';
    case BUTTON   = 'button';
    case CONDITION   = 'condition';
    case REPLY_BUTTON   = 'reply_button';// Reply From Contact for quick reply or interactive_button
    case REPLY_LIST     = 'reply_list';// Reply From Contact for quick reply or interactive_button
    case INTERACTIVE   = 'interactive';
    case INTERACTIVE_BUTTON   = 'interactive_button';
    case INTERACTIVE_LIST   = 'interactive_list';
    case TEMPLATE   = 'template';
    case CAROUSEL   = 'carousel';
    case REACTION   = 'reaction';
    case UNSUPPORTED   = 'unsupported';
   
}
