<?php

namespace OpenAI;

use Closure;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use Http\Discovery\Psr18ClientDiscovery;
use OpenAI\Transporters\HttpTransporter;
use OpenAI\ValueObjects\ApiKey;
use OpenAI\ValueObjects\Transporter\BaseUri;
use OpenAI\ValueObjects\Transporter\Headers;
use OpenAI\ValueObjects\Transporter\QueryParams;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\Psr18Client;

final class Factory
{
    /**
     * The API key for the requests.
     * @var string|null
     */
    private  $apiKey;

    /**
     * The organization for the requests.
     * @var string|null
     */
    private  $organization;

    /**
     * The HTTP client for the requests.
     * @var \Psr\Http\Client\ClientInterface|null
     */
    private  $httpClient;

    /**
     * The base URI for the requests.
     * @var string|null
     */
    private  $baseUri;

    /**
     * The HTTP headers for the requests.
     *
     * @var string[]
     */
    private  $headers = [];

    /**
     * The query parameters for the requests.
     *
     * @var string[]
     */
    private  $queryParams = [];

    /**
     * @var Closure|null
     */
    private  $streamHandler;

    /**
     * Sets the API key for the requests.
     */
    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = trim($apiKey);

        return $this;
    }

    /**
     * Sets the organization for the requests.
     */
    public function withOrganization(?string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Sets the HTTP client for the requests.
     * If no client is provided the factory will try to find one using PSR-18 HTTP Client Discovery.
     */
    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Sets the stream handler for the requests. Not required when using Guzzle.
     */
    public function withStreamHandler(Closure $streamHandler): self
    {
        $this->streamHandler = $streamHandler;

        return $this;
    }

    /**
     * Sets the base URI for the requests.
     * If no URI is provided the factory will use the default OpenAI API URI.
     */
    public function withBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Adds a custom HTTP header to the requests.
     */
    public function withHttpHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Adds a custom query parameter to the request url.
     */
    public function withQueryParam(string $name, string $value): self
    {
        $this->queryParams[$name] = $value;

        return $this;
    }

    /**
     * Creates a new Open AI Client.
     */
    public function make(): Client
    {
        $headers = Headers::create();

        if ($this->apiKey !== null) {
            $headers = Headers::withAuthorization(ApiKey::from($this->apiKey));
        }

        if ($this->organization !== null) {
            $headers = $headers->withOrganization($this->organization);
        }

        foreach ($this->headers as $name => $value) {
            $headers = $headers->withCustomHeader($name, $value);
        }

        $baseUri = BaseUri::from($this->baseUri ?: 'api.openai.com/v1');

        $queryParams = QueryParams::create();
        foreach ($this->queryParams as $name => $value) {
            $queryParams = $queryParams->withParam($name, $value);
        }

        $client = $this->httpClient ?? Psr18ClientDiscovery::find();

        $sendAsync = $this->makeStreamHandler($client);

        $transporter = new HttpTransporter($client, $baseUri, $headers, $queryParams, $sendAsync);

        return new Client($transporter);
    }

    /**
     * Creates a new stream handler for "stream" requests.
     */
    private function makeStreamHandler(ClientInterface $client): Closure
    {
        if (!is_null($this->streamHandler)) {
            return $this->streamHandler;
        }

        if ($client instanceof GuzzleClient) {
            return function (RequestInterface $request) use ($client): ResponseInterface {
                return $client->send($request, ['stream' => true]);
            };
        }

        if ($client instanceof Psr18Client) {
            return function (RequestInterface $request) use ($client): ResponseInterface {
                return $client->sendRequest($request);
            };
        }

        return function (RequestInterface $_): void { // @phpstan-ignore-line
            throw new Exception('To use stream requests you must provide an stream handler closure via the OpenAI factory.');
        };
    }
}
