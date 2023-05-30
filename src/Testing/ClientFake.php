<?php

namespace OpenAI\Testing;

use OpenAI\Contracts\ClientContract;
use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\StreamResponse;
use OpenAI\Testing\Requests\TestRequest;
use OpenAI\Testing\Resources\AudioTestResource;
use OpenAI\Testing\Resources\ChatTestResource;
use OpenAI\Testing\Resources\CompletionsTestResource;
use OpenAI\Testing\Resources\EditsTestResource;
use OpenAI\Testing\Resources\EmbeddingsTestResource;
use OpenAI\Testing\Resources\FilesTestResource;
use OpenAI\Testing\Resources\FineTunesTestResource;
use OpenAI\Testing\Resources\ImagesTestResource;
use OpenAI\Testing\Resources\ModelsTestResource;
use OpenAI\Testing\Resources\ModerationsTestResource;
use PHPUnit\Framework\Assert as PHPUnit;
use Throwable;

/**
 * @noRector Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector
 */
class ClientFake implements ClientContract
{
    /**
     * @var mixed[]
     */
    protected $responses = [];
    /**
     * @var array<array-key, TestRequest>
     */
    private $requests = [];

    /**
     * @param array<array-key, ResponseContract|StreamResponse|string> $responses
     */
    public function __construct(array $responses = [])
    {
        $this->responses = $responses;
    }

    /**
     * @param array<array-key, Response> $responses
     */
    public function addResponses(array $responses): void
    {
        $this->responses = array_merge($this->responses, $responses);
    }

    /**
     * @param callable|int|null $callback
     */
    public function assertSent(string $resource, $callback = null): void
    {
        if (is_int($callback)) {
            $this->assertSentTimes($resource, $callback);

            return;
        }

        PHPUnit::assertTrue(
            $this->sent($resource, $callback) !== [],
            "The expected [{$resource}] request was not sent."
        );
    }

    protected function assertSentTimes(string $resource, int $times = 1): void
    {
        $count = count($this->sent($resource));

        PHPUnit::assertSame(
            $times, $count,
            "The expected [{$resource}] resource was sent {$count} times instead of {$times} times."
        );
    }

    /**
     * @return mixed[]
     */
    protected function sent(string $resource, callable $callback = null): array
    {
        if (!$this->hasSent($resource)) {
            return [];
        }

        $callback = $callback ?: function (): bool {
            return true;
        };

        return array_filter($this->resourcesOf($resource), function (TestRequest $resource) use ($callback) {
            return $callback($resource->method(), $resource->parameters());
        });
    }

    protected function hasSent(string $resource): bool
    {
        return $this->resourcesOf($resource) !== [];
    }

    public function assertNotSent(string $resource, callable $callback = null): void
    {
        PHPUnit::assertCount(
            0, $this->sent($resource, $callback),
            "The unexpected [{$resource}] request was sent."
        );
    }

    public function assertNothingSent(): void
    {
        $resourceNames = implode(
            ', ',
            array_map(function (TestRequest $request): string {
                return $request->resource();
            }, $this->requests)
        );

        PHPUnit::assertEmpty($this->requests, 'The following requests were sent unexpectedly: ' . $resourceNames);
    }

    /**
     * @return array<array-key, TestRequest>
     */
    protected function resourcesOf(string $type): array
    {
        return array_filter($this->requests, function (TestRequest $request) use ($type): bool {
            return $request->resource() === $type;
        });
    }

    public function record(TestRequest $request)
    {
        $this->requests[] = $request;

        $response = array_shift($this->responses);

        if (is_null($response)) {
            throw new \Exception('No fake responses left.');
        }

        if ($response instanceof Throwable) {
            throw $response;
        }

        return $response;
    }

    public function completions(): \OpenAI\Contracts\Resources\CompletionsContract
    {
        return new CompletionsTestResource($this);
    }

    public function chat(): \OpenAI\Contracts\Resources\ChatContract
    {
        return new ChatTestResource($this);
    }

    public function embeddings(): \OpenAI\Contracts\Resources\EmbeddingsContract
    {
        return new EmbeddingsTestResource($this);
    }

    public function audio(): \OpenAI\Contracts\Resources\AudioContract
    {
        return new AudioTestResource($this);
    }

    public function edits(): \OpenAI\Contracts\Resources\EditsContract
    {
        return new EditsTestResource($this);
    }

    public function files(): \OpenAI\Contracts\Resources\FilesContract
    {
        return new FilesTestResource($this);
    }

    public function models(): \OpenAI\Contracts\Resources\ModelsContract
    {
        return new ModelsTestResource($this);
    }

    public function fineTunes(): \OpenAI\Contracts\Resources\FineTunesContract
    {
        return new FineTunesTestResource($this);
    }

    public function moderations(): \OpenAI\Contracts\Resources\ModerationsContract
    {
        return new ModerationsTestResource($this);
    }

    public function images(): \OpenAI\Contracts\Resources\ImagesContract
    {
        return new ImagesTestResource($this);
    }
}
