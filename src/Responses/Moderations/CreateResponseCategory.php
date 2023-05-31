<?php

declare(strict_types=1);

namespace OpenAI\Responses\Moderations;

use OpenAI\Enums\Moderations\Category;

final class CreateResponseCategory
{
    public $score;
    public $violated;
    public $category;

    private function __construct($category,
                                 bool     $violated,
                                 float    $score)
    {
        $this->category = $category;
        $this->violated = $violated;
        $this->score = $score;
    }

    /**
     * @param  array{category: string, violated: bool, score: float}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            Category::from($attributes['category']),
            $attributes['violated'],
            $attributes['score'],
        );
    }
}
