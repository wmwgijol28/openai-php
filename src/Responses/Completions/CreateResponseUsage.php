<?php

declare(strict_types=1);

namespace OpenAI\Responses\Completions;

final class CreateResponseUsage
{
    public $totalTokens;
    public $completionTokens;
    public $promptTokens;

    private function __construct(int $promptTokens, ?int $completionTokens, int $totalTokens)
    {
        $this->promptTokens = $promptTokens;
        $this->completionTokens = $completionTokens;
        $this->totalTokens = $totalTokens;
    }

    /**
     * @param  array{prompt_tokens: int, completion_tokens: int|null, total_tokens: int}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['prompt_tokens'],
            $attributes['completion_tokens'] ?? null,
            $attributes['total_tokens'],
        );
    }

    /**
     * @return array{prompt_tokens: int, completion_tokens: int|null, total_tokens: int}
     */
    public function toArray(): array
    {
        return [
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
        ];
    }
}
