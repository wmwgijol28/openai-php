<?php

declare(strict_types=1);

namespace OpenAI\Responses\Embeddings;

final class CreateResponseEmbedding
{
    public $embedding;
    public $index;
    public $object;

    /**
     * @param  array<int, float>  $embedding
     */
    private function __construct(string $object, int $index, array $embedding)
    {
        $this->object = $object;
        $this->index = $index;
        $this->embedding = $embedding;
    }

    /**
     * @param  array{object: string, index: int, embedding: array<int, float>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['object'],
            $attributes['index'],
            $attributes['embedding'],
        );
    }

    /**
     * @return array{object: string, index: int, embedding: array<int, float>}
     */
    public function toArray(): array
    {
        return [
            'object' => $this->object,
            'index' => $this->index,
            'embedding' => $this->embedding,
        ];
    }
}
