<?php

namespace JakubOrava\EhubClient;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JakubOrava\EhubClient\Exceptions\ApiErrorException;
use JakubOrava\EhubClient\Exceptions\AuthenticationException;
use JakubOrava\EhubClient\Exceptions\UnexpectedResponseException;
use JakubOrava\EhubClient\Exceptions\ValidationException;

class BaseEhubClient
{
    private const string BASE_URL = 'https://api.ehub.cz/v3';

    private string $apiKey;

    public function __construct()
    {
        $apiKey = config('ehub-client.api_key', '');
        assert(is_string($apiKey));
        $this->apiKey = $apiKey;
    }

    /**
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function get(string $userId, string $path, array $queryParams = []): array
    {
        $url = $this->buildUrl($userId, $path);
        $response = $this->makeRequest('GET', $url, $queryParams);

        return $this->handleResponse($response);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function post(string $userId, string $path, array $data = [], array $queryParams = []): array
    {
        $url = $this->buildUrl($userId, $path);
        $response = $this->makeRequest('POST', $url, $queryParams, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function patch(string $userId, string $path, array $data = [], array $queryParams = []): array
    {
        $url = $this->buildUrl($userId, $path);
        $response = $this->makeRequest('PATCH', $url, $queryParams, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function put(string $userId, string $path, array $data = [], array $queryParams = []): array
    {
        $url = $this->buildUrl($userId, $path);
        $response = $this->makeRequest('PUT', $url, $queryParams, $data);

        return $this->handleResponse($response);
    }

    /**
     * @param array<string, mixed> $queryParams
     * @return array<mixed>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    public function delete(string $userId, string $path, array $queryParams = []): array
    {
        $url = $this->buildUrl($userId, $path);
        $response = $this->makeRequest('DELETE', $url, $queryParams);

        return $this->handleResponse($response);
    }

    private function buildUrl(string $userId, string $path): string
    {
        $path = ltrim($path, '/');

        return self::BASE_URL . "/publishers/$userId/$path";
    }

    /**
     * @param array<string, mixed> $queryParams
     * @param array<string, mixed> $data
     */
    private function makeRequest(string $method, string $url, array $queryParams = [], array $data = []): Response
    {
        $client = $this->prepareClient();

        // Add API key to query parameters
        $queryParams['apiKey'] = $this->apiKey;

        return match (strtoupper($method)) {
            'GET' => $client->get($url, $queryParams),
            'POST' => $client->post($url, array_merge($data, $queryParams)),
            'PATCH' => $client->patch($url, array_merge($data, $queryParams)),
            'PUT' => $client->put($url, array_merge($data, $queryParams)),
            'DELETE' => $client->delete($url, $queryParams),
            default => throw new UnexpectedResponseException("Unsupported HTTP method: $method"),
        };
    }

    private function prepareClient(): PendingRequest
    {
        return Http::acceptJson()
            ->contentType('application/json')
            ->timeout(30);
    }

    /**
     * @return array<mixed>
     *
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws ApiErrorException
     * @throws UnexpectedResponseException
     */
    private function handleResponse(Response $response): array
    {
        // Handle authentication errors
        if ($response->status() === 401) {
            throw new AuthenticationException('Authentication failed. Please check your API key.');
        }

        // Handle validation errors
        if ($response->status() === 422) {
            $message = $this->extractErrorMessage($response);
            throw new ValidationException("Validation failed: $message");
        }

        // Handle forbidden
        if ($response->status() === 403) {
            $message = $this->extractErrorMessage($response);
            throw new ApiErrorException("Access forbidden: $message", 403);
        }

        // Handle not found
        if ($response->status() === 404) {
            throw new ApiErrorException('Resource not found', 404);
        }

        // Handle other client errors
        if ($response->clientError()) {
            $message = $this->extractErrorMessage($response);
            throw new ApiErrorException("Client error: $message", $response->status());
        }

        // Handle server errors
        if ($response->serverError()) {
            throw new ApiErrorException('Server error occurred', $response->status());
        }

        // Ensure successful response
        if (!$response->successful()) {
            throw new ApiErrorException("Request failed with status {$response->status()}", $response->status());
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new UnexpectedResponseException('Response is not an array');
        }

        return $data;
    }

    private function extractErrorMessage(Response $response): string
    {
        $data = $response->json();

        if (is_array($data) && isset($data['message']) && is_string($data['message'])) {
            return $data['message'];
        }

        if (is_array($data) && isset($data['error']) && is_string($data['error'])) {
            return $data['error'];
        }

        return $response->body();
    }
}
