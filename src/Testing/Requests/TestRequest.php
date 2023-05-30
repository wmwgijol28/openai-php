<?php

namespace OpenAI\Testing\Requests;

final class TestRequest
{
    private $parameters;
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $resource;

    /**
     * @param array<string, mixed>|string|null $parameters
     */
    public function __construct(string $resource, string $method, $parameters)
    {
        $this->resource = $resource;
        $this->method = $method;
        $this->parameters = $parameters;
    }

    public function resource(): string
    {
        return $this->resource;
    }

    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return array<string, mixed>|string|null
     */
    public function parameters()
    {
        return $this->parameters;
    }
}
