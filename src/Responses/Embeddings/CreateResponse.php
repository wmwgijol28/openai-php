<?php

declare(strict_types=1);

namespace OpenAI\Responses\Embeddings;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @implements ResponseContract<array{object: string, data: array<int, array{object: string, embedding: array<int, float>, index: int}>, usage: array{prompt_tokens: int, total_tokens: int}}>
 */
final class CreateResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{object: string, data: array<int, array{object: string, embedding: array<int, float>, index: int}>, usage: array{prompt_tokens: int, total_tokens: int}}>
     */
    use ArrayAccessible;

    use Fakeable;

    public $usage;
    public $embeddings;
    public $object;

    /**
     * @param  array<int, CreateResponseEmbedding>  $embeddings
     */
    private function __construct(string $object, array $embeddings, CreateResponseUsage $usage)
    {
        $this->object = $object;
        $this->embeddings = $embeddings;
        $this->usage = $usage;
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{object: string, data: array<int, array{object: string, embedding: array<int, float>, index: int}>, usage: array{prompt_tokens: int, total_tokens: int}}  $attributes
     */
    public static function from(array $attributes): self
    {
        $embeddings = array_map(function (array $result): CreateResponseEmbedding {
            return CreateResponseEmbedding::from(
                $result
            );
        }, $attributes['data']);

        return new self(
            $attributes['object'],
            $embeddings,
            CreateResponseUsage::from($attributes['usage'])
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
                static function (CreateResponseEmbedding $result): array {
                    return $result->toArray();
                },
                $this->embeddings,
            ),
            'usage' => $this->usage->toArray(),
        ];
    }
}
