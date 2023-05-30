<?php

declare(strict_types=1);

namespace OpenAI\Responses\Audio;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{id: int, seek: int, start: float, end: float, text: string, tokens: array<int, int>, temperature: float, avg_logprob: float, compression_ratio: float, no_speech_prob: float, transient: bool}>
 */
final class TranslationResponseSegment implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{id: int, seek: int, start: float, end: float, text: string, tokens: array<int, int>, temperature: float, avg_logprob: float, compression_ratio: float, no_speech_prob: float, transient: bool}>
     */
    use ArrayAccessible;

    public $id;
    public $seek;
    public $start;
    public $end;
    public $text;
    public $tokens;
    public $temperature;
    public $avgLogprob;
    public $compressionRatio;
    public $noSpeechProb;
    public $transient;

    /**
     * @param array<int, int> $tokens
     */
    private function __construct(int    $id,
                                 int    $seek,
                                 float  $start,
                                 float  $end,
                                 string $text,
                                 array  $tokens,
                                 float  $temperature,
                                 float  $avgLogprob,
                                 float  $compressionRatio,
                                 float  $noSpeechProb,
                                 bool   $transient)
    {
        $this->transient = $transient;
        $this->noSpeechProb = $noSpeechProb;
        $this->compressionRatio = $compressionRatio;
        $this->avgLogprob = $avgLogprob;
        $this->temperature = $temperature;
        $this->tokens = $tokens;
        $this->text = $text;
        $this->end = $end;
        $this->start = $start;
        $this->seek = $seek;
        $this->id = $id;
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param array{id: int, seek: int, start: float, end: float, text: string, tokens: array<int, int>, temperature: float, avg_logprob: float, compression_ratio: float, no_speech_prob: float, transient: bool} $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['id'],
            $attributes['seek'],
            $attributes['start'],
            $attributes['end'],
            $attributes['text'],
            $attributes['tokens'],
            $attributes['temperature'],
            $attributes['avg_logprob'],
            $attributes['compression_ratio'],
            $attributes['no_speech_prob'],
            $attributes['transient'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'seek' => $this->seek,
            'start' => $this->start,
            'end' => $this->end,
            'text' => $this->text,
            'tokens' => $this->tokens,
            'temperature' => $this->temperature,
            'avg_logprob' => $this->avgLogprob,
            'compression_ratio' => $this->compressionRatio,
            'no_speech_prob' => $this->noSpeechProb,
            'transient' => $this->transient,
        ];
    }
}
