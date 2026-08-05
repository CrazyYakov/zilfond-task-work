<?php

namespace App\ValuesObjects;

enum NotificationStatusEnum: string
{
    case ERROR = 'error';

    case PROGRESS = 'progress';

    case SENT = 'sent';
}
