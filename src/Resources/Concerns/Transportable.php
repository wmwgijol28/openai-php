<?php

declare(strict_types=1);

namespace OpenAI\Resources\Concerns;

use OpenAI\Contracts\TransporterContract;

trait Transportable
{
    private $transporter;

    /**
     * Creates a Client instance with the given API token.
     */
    public function __construct(TransporterContract $transporter)
    {
        $this->transporter = $transporter;
    }
}
