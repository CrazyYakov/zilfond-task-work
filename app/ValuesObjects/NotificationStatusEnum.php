<?php

namespace App\ValuesObjects;

enum NotificationStatusEnum
{
    case ERROR;

    case PROGRESS;

    case SENT;
}
