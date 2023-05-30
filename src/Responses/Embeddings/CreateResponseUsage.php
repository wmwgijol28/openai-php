<?php

declare(strict_types=1);

namespace OpenAI\Responses\Embeddings;

final class CreateResponseUsage
{
    public $totalTokens;
    public $promptTokens;

    private function __construct(int $promptTokens, int $totalTokens)
    {
        $this->promptTokens = $promptTokens;
        $this->totalTokens = $totalTokens;
    }

    /**
     * @param  array{prompt_tokens: int, total_tokens: int}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['prompt_tokens'],
            $attributes['total_tokens'],
        );
    }

    /**
     * @return array{prompt_tokens: int, total_tokens: int}
     */
    public function toArray(): array
    {
        return [
            'prompt_tokens' => $this->promptTokens,
            'total_tokens' => $this->totalTokens,
        ];
    }
}
