<?php

declare(strict_types=1);

namespace OpenAI\Enums\Transporter;

final class Method
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';

    private function __construct()
    {
        // 禁止实例化
    }

}
