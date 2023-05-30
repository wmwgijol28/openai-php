<?php

declare(strict_types=1);

namespace OpenAI;

use OpenAI\Contracts\ClientContract;
use OpenAI\Contracts\TransporterContract;
use OpenAI\Resources\Audio;
use OpenAI\Resources\Chat;
use OpenAI\Resources\Completions;
use OpenAI\Resources\Edits;
use OpenAI\Resources\Embeddings;
use OpenAI\Resources\Files;
use OpenAI\Resources\FineTunes;
use OpenAI\Resources\Images;
use OpenAI\Resources\Models;
use OpenAI\Resources\Moderations;

final class Client implements ClientContract
{
    /**
     * @var \OpenAI\Contracts\TransporterContract
     */
    private $transporter;
    /**
     * Creates a Client instance with the given API token.
     */
    public function __construct(TransporterContract $transporter)
    {
        $this->transporter = $transporter;
    }

    /**
     * Given a prompt, the model will return one or more predicted completions, and can also return the probabilities
     * of alternative tokens at each position.
     *
     * @see https://beta.openai.com/docs/api-reference/completions
     */
    public function completions(): Contracts\Resources\CompletionsContract
    {
        return new Completions($this->transporter);
    }

    /**
     * Given a chat conversation, the model will return a chat completion response.
     *
     * @see https://platform.openai.com/docs/api-reference/chat
     */
    public function chat(): Contracts\Resources\ChatContract
    {
        return new Chat($this->transporter);
    }

    /**
     * Get a vector representation of a given input that can be easily consumed by machine learning models and algorithms.
     *
     * @see https://beta.openai.com/docs/api-reference/embeddings
     */
    public function embeddings(): Contracts\Resources\EmbeddingsContract
    {
        return new Embeddings($this->transporter);
    }

    /**
     * Learn how to turn audio into text.
     *
     * @see https://platform.openai.com/docs/api-reference/audio
     */
    public function audio(): Contracts\Resources\AudioContract
    {
        return new Audio($this->transporter);
    }

    /**
     * Given a prompt and an instruction, the model will return an edited version of the prompt.
     *
     * @see https://beta.openai.com/docs/api-reference/edits
     */
    public function edits(): Contracts\Resources\EditsContract
    {
        return new Edits($this->transporter);
    }

    /**
     * Files are used to upload documents that can be used with features like Fine-tuning.
     *
     * @see https://beta.openai.com/docs/api-reference/files
     */
    public function files(): Contracts\Resources\FilesContract
    {
        return new Files($this->transporter);
    }

    /**
     * List and describe the various models available in the API.
     *
     * @see https://beta.openai.com/docs/api-reference/models
     */
    public function models(): Contracts\Resources\ModelsContract
    {
        return new Models($this->transporter);
    }

    /**
     * Manage fine-tuning jobs to tailor a model to your specific training data.
     *
     * @see https://beta.openai.com/docs/api-reference/fine-tunes
     */
    public function fineTunes(): Contracts\Resources\FineTunesContract
    {
        return new FineTunes($this->transporter);
    }

    /**
     * Given a input text, outputs if the model classifies it as violating OpenAI's content policy.
     *
     * @see https://beta.openai.com/docs/api-reference/moderations
     */
    public function moderations(): Contracts\Resources\ModerationsContract
    {
        return new Moderations($this->transporter);
    }

    /**
     * Given a prompt and/or an input image, the model will generate a new image.
     *
     * @see https://beta.openai.com/docs/api-reference/images
     */
    public function images(): Contracts\Resources\ImagesContract
    {
        return new Images($this->transporter);
    }
}
