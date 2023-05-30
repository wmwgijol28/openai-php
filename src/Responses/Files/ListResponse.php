<?php

declare(strict_types=1);

namespace OpenAI\Responses\Files;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @implements ResponseContract<array{object: string, data: array<int, array{id: string, object: string, created_at: int, bytes: int, filename: string, purpose: string, status: string, status_details: array<array-key, mixed>|string|null}>}>
 */
final class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{object: string, data: array<int, array{id: string, object: string, created_at: int, bytes: int, filename: string, purpose: string, status: string, status_details: array<array-key, mixed>|string|null}>}>
     */
    use ArrayAccessible;

    use Fakeable;

    public $data;
    public $object;

    /**
     * @param  array<int, RetrieveResponse>  $data
     */
    private function __construct(string $object, array $data)
    {
        $this->object = $object;
        $this->data = $data;
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{object: string, data: array<int, array{id: string, object: string, created_at: int, bytes: int, filename: string, purpose: string, status: string, status_details: array<array-key, mixed>|string|null}>}  $attributes
     */
    public static function from(array $attributes): self
    {
        $data = array_map(function (array $result): RetrieveResponse {
            return RetrieveResponse::from(
                $result
            );
        }, $attributes['data']);

        return new self(
            $attributes['object'],
            $data,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'object' => $this->object,
            'data' => array_map(
                static function (RetrieveResponse $response): array {
                    return $response->toArray();
                },
                $this->data,
            ),
        ];
    }
}
