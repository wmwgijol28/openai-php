<?php

declare(strict_types=1);

namespace OpenAI\ValueObjects\Transporter;

/**
 * @internal
 */
final class QueryParams
{
    /**
     * @var string[]|int[]
     */
    private $params;
    /**
     * Creates a new Query Params value object.
     *
     * @param array<string, string|int> $params
     */
    private function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Creates a new Query Params value object
     */
    public static function create(): self
    {
        return new self([]);
    }

    /**
     * Creates a new Query Params value object, with the newly added param, and the existing params.
     */
    public function withParam(string $name, $value): self
    {
        return new self(array_merge(
            $this->params,
            [$name => $value]
        ));
    }

    /**
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        return $this->params;
    }
}
