<?php

declare(strict_types=1);

namespace OpenAI\Transporters;

use Closure;
use GuzzleHttp\Exception\ClientException;
use JsonException;
use OpenAI\Contracts\TransporterContract;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Exceptions\UnserializableResponse;
use OpenAI\ValueObjects\Transporter\BaseUri;
use OpenAI\ValueObjects\Transporter\Headers;
use OpenAI\ValueObjects\Transporter\Payload;
use OpenAI\ValueObjects\Transporter\QueryParams;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
final class HttpTransporter implements TransporterContract
{
    private $streamHandler;
    /**
     * @var \OpenAI\ValueObjects\Transporter\QueryParams
     */
    private $queryParams;
    /**
     * @var \OpenAI\ValueObjects\Transporter\Headers
     */
    private $headers;
    /**
     * @var \OpenAI\ValueObjects\Transporter\BaseUri
     */
    private $baseUri;
    /**
     * @var \Psr\Http\Client\ClientInterface
     */
    private $client;

    /**
     * Creates a new Http Transporter instance.
     */
    public function __construct(
        ClientInterface $client,
        BaseUri         $baseUri,
        Headers         $headers,
        QueryParams     $queryParams,
        Closure         $streamHandler
    )
    {
        $this->client = $client;
        $this->baseUri = $baseUri;
        $this->headers = $headers;
        $this->queryParams = $queryParams;
        $this->streamHandler = $streamHandler;
        // ..
    }

    /**
     * {@inheritDoc}
     */
    public function requestObject(Payload $payload)
    {
        $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

        $response = $this->sendRequest(function () use ($request): \Psr\Http\Message\ResponseInterface {
            return $this->client->sendRequest($request);
        });

        $contents = $response->getBody()->getContents();

        if ($response->getHeader('Content-Type')[0] === 'text/plain; charset=utf-8') {
            return $contents;
        }

        $this->throwIfJsonError($response, $contents);

        try {
            /** @var array{error?: array{message: string, type: string, code: string}} $response */
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new UnserializableResponse($jsonException);
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function requestContent(Payload $payload): string
    {
        $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

        $response = $this->sendRequest(function () use ($request): \Psr\Http\Message\ResponseInterface {
            return $this->client->sendRequest($request);
        });

        $contents = $response->getBody()->getContents();

        $this->throwIfJsonError($response, $contents);

        return $contents;
    }

    /**
     * {@inheritDoc}
     */
    public function requestStream(Payload $payload): ResponseInterface
    {
        $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

        $response = $this->sendRequest(function () use ($request) {
            return ($this->streamHandler)($request);
        });

        $this->throwIfJsonError($response, $response);

        return $response;
    }

    private function sendRequest(Closure $callable): ResponseInterface
    {
        try {
            return $callable();
        } catch (ClientExceptionInterface $clientException) {
            if ($clientException instanceof ClientException) {
                $this->throwIfJsonError($clientException->getResponse(), $clientException->getResponse()->getBody()->getContents());
            }

            throw new TransporterException($clientException);
        }
    }

    private function throwIfJsonError(ResponseInterface $response, $contents): void
    {
        if ($response->getStatusCode() < 400) {
            return;
        }

        if ($response->getheader('Content-Type')[0] !== 'application/json; charset=utf-8') {
            return;
        }

        if ($contents instanceof ResponseInterface) {
            $contents = $contents->getBody()->getContents();
        }

        try {
            /** @var array{error?: array{message: string, type: string, code: string}} $response */
            $response = json_decode((string) $contents, true, 512, JSON_THROW_ON_ERROR);

            if (isset($response['error'])) {
                throw new ErrorException($response['error']);
            }
        } catch (JsonException $jsonException) {
            throw new UnserializableResponse($jsonException);
        }
    }
}
