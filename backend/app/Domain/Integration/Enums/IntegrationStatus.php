<?php

namespace App\Domain\Integration\Enums;

enum IntegrationStatus: string
{
    case PENDING = 'PENDING';
    case PROCESSING = 'PROCESSING';
    case SUCCESS = 'SUCCESS';
    case ERROR = 'ERROR';
}
