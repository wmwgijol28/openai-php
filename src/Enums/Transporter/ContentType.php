<?php

declare(strict_types=1);

namespace OpenAI\Enums\Transporter;

final class ContentType
{
    public const JSON = 'application/json';
    public const MULTIPART = 'multipart/form-data';

    private function __construct()
    {
        // 禁止实例化
    }

}

