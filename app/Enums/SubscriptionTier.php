<?php

namespace App\Enums;

enum SubscriptionTier: string
{
    case Free = 'free';
    case Pro = 'pro';
    case Enterprise = 'enterprise';
}
