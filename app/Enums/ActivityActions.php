<?php

namespace App\Enums;

enum ActivityActions: string
{
    case NewClientRegistered = 'new_client_registered';
    case ServiceBooked = 'service_booked';
    case ServiceCanceled = 'service_canceled';
    case PackageSubscribed = 'package_subscribed';
    case PaymentMade = 'payment_made';
    case RefundProcessed = 'refund_processed';
}
