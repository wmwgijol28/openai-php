<?php

declare(strict_types=1);

namespace OpenAI\Responses\Moderations;

use OpenAI\Enums\Moderations\Category;

final class CreateResponseResult
{
    public $flagged;
    public $categories;

    /**
     * @param  array<string, CreateResponseCategory>  $categories
     */
    private function __construct(
        array $categories,
        bool $flagged
    ) {
        $this->categories = $categories;
        $this->flagged = $flagged;
        // ..
    }

    /**
     * @param  array{categories: array<string, bool>, category_scores: array<string, float>, flagged: bool}  $attributes
     */
    public static function from(array $attributes): self
    {
        /** @var array<string, CreateResponseCategory> $categories */
        $categories = [];

        foreach (Category::cases() as $category) {
            $categories[$category] = CreateResponseCategory::from([
                'category' => $category,
                'violated' => $attributes['categories'][$category],
                'score' => $attributes['category_scores'][$category],
            ]);
        }

        return new CreateResponseResult(
            $categories,
            $attributes['flagged']
        );
    }

    /**
     * @return array{categories: array<string, bool>, category_scores: array<string, float>, flagged: bool}
     */
    public function toArray(): array
    {
        $categories = [];
        $categoryScores = [];
        foreach ($this->categories as $category) {
            $categories[$category->category] = $category->violated;
            $categoryScores[$category->category] = $category->score;
        }

        return [
            'categories' => $categories,
            'category_scores' => $categoryScores,
            'flagged' => $this->flagged,
        ];
    }
}
