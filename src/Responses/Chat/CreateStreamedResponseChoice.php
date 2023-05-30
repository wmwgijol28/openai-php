<?php

declare(strict_types=1);

namespace OpenAI\Responses\Chat;

final class CreateStreamedResponseChoice
{
    public $finishReason;
    public $delta;
    public $index;

    private function __construct(int $index, CreateStreamedResponseDelta $delta, ?string $finishReason)
    {
        $this->index = $index;
        $this->delta = $delta;
        $this->finishReason = $finishReason;
    }

    /**
     * @param  array{index: int, delta: array{role?: string, content?: string}, finish_reason: string|null}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['index'],
            CreateStreamedResponseDelta::from($attributes['delta']),
            $attributes['finish_reason'],
        );
    }

    /**
     * @return array{index: int, delta: array{role?: string, content?: string}, finish_reason: string|null}
     */
    public function toArray(): array
    {
        return [
            'index' => $this->index,
            'delta' => $this->delta->toArray(),
            'finish_reason' => $this->finishReason,
        ];
    }
}
