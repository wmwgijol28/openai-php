<?php

declare(strict_types=1);

namespace OpenAI\Responses\Completions;

final class CreateResponseChoiceLogprobs
{
    public $textOffset;
    public $topLogprobs;
    public $tokenLogprobs;
    public $tokens;

    /**
     * @param  array<int, string>  $tokens
     * @param  array<int, float>  $tokenLogprobs
     * @param  array<int, string>|null  $topLogprobs
     * @param  array<int, int>  $textOffset
     */
    private function __construct(array $tokens, array $tokenLogprobs, ?array $topLogprobs, array $textOffset)
    {
        $this->tokens = $tokens;
        $this->tokenLogprobs = $tokenLogprobs;
        $this->topLogprobs = $topLogprobs;
        $this->textOffset = $textOffset;
    }

    /**
     * @param  array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['tokens'],
            $attributes['token_logprobs'],
            $attributes['top_logprobs'],
            $attributes['text_offset'],
        );
    }

    /**
     * @return array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}
     */
    public function toArray(): array
    {
        return [
            'tokens' => $this->tokens,
            'token_logprobs' => $this->tokenLogprobs,
            'top_logprobs' => $this->topLogprobs,
            'text_offset' => $this->textOffset,
        ];
    }
}
