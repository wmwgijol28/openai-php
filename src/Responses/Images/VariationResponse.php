<?php

declare(strict_types=1);

namespace OpenAI\Responses\Images;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @implements ResponseContract<array{created: int, data: array<int, array{url?: string, b64_json?: string}>}>
 */
final class VariationResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{created: int, data: array<int, array{url?: string, b64_json?: string}>}>
     */
    use ArrayAccessible;

    use Fakeable;

    public $data;
    public $created;

    /**
     * @param  array<int, VariationResponseData>  $data
     */
    private function __construct(int $created, array $data)
    {
        $this->created = $created;
        $this->data = $data;
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{created: int, data: array<int, array{url?: string, b64_json?: string}>}  $attributes
     */
    public static function from(array $attributes): self
    {
        $results = array_map(function (array $result): VariationResponseData {
            return VariationResponseData::from(
                $result
            );
        }, $attributes['data']);

        return new self(
            $attributes['created'],
            $results,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'created' => $this->created,
            'data' => array_map(
                static function (VariationResponseData $result): array {
                    return $result->toArray();
                },
                $this->data,
            ),
        ];
    }
}
