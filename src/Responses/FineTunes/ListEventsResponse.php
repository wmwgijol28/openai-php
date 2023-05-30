<?php

declare(strict_types=1);

namespace OpenAI\Responses\FineTunes;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @implements ResponseContract<array{object: string, data: array<int, array{object: string, created_at: int, level: string, message: string}>}>
 */
final class ListEventsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{object: string, data: array<int, array{object: string, created_at: int, level: string, message: string}>}>
     */
    use ArrayAccessible;

    use Fakeable;

    public $data;
    public $object;

    /**
     * @param  array<int, RetrieveResponseEvent>  $data
     */
    private function __construct(string $object, array $data)
    {
        $this->object = $object;
        $this->data = $data;
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{object: string, data: array<int, array{object: string, created_at: int, level: string, message: string}>}  $attributes
     */
    public static function from(array $attributes): self
    {
        $data = array_map(function (array $result): RetrieveResponseEvent {
            return RetrieveResponseEvent::from(
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
                static function (RetrieveResponseEvent $response): array {
                    return $response->toArray();
                },
                $this->data,
            ),
        ];
    }
}
