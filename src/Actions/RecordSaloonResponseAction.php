<?php

declare(strict_types=1);

namespace Saloon\Barstool\Actions;

use Saloon\Http\Response;
use Illuminate\Support\Str;
use Saloon\Barstool\Barstool;
use Psr\Http\Message\UriInterface;
use Saloon\Barstool\Enums\RecordingType;

class RecordSaloonResponseAction
{
    public function execute(Response $response): void
    {

        if (Barstool::shouldRecord($response) === false) {
            return;
        }

        $response->getConnector()->config()->add(
            'barstool-response-time',
            microtime(true) * 1000
        );

        if ($response->successful() && config('barstool.keep_successful_responses') === false) {
            return;
        }

        $psrRequest = $response->getPsrRequest();

        $uuid = $psrRequest->getHeader('X-Barstool-UUID')[0] ?? null;
        if (is_null($uuid)) {
            return;
        }

        $payload = [
            'duration' => Barstool::calculateDuration($response),
            ...self::getResponseData($response),
        ];

        Barstool::persist(RecordingType::RESPONSE, $payload, $uuid);
    }

    /**
     * @return array{
     *      url: UriInterface,
     *      response_headers: array<string, mixed>,
     *      response_body: string,
     *      response_status: int,
     *      successful: bool
     * }
     */
    private static function getResponseData(Response $response): array
    {
        $responseBody = self::getResponseBody($response);

        return [
            'url' => $response->getPsrRequest()->getUri(),
            'response_headers' => $response->headers()->all(),
            'response_body' => $responseBody,
            'response_status' => $response->status(),
            'successful' => $response->successful(),
        ];
    }

    private static function getResponseBody(Response $response): string
    {
        $excludedBodies = config('barstool.excluded_response_body', []);

        // Check if all bodies are excluded
        if (in_array('*', $excludedBodies)) {
            return 'REDACTED';
        }

        // Check if the connector class is excluded
        if (in_array(get_class($response->getConnector()), $excludedBodies)) {
            return 'REDACTED';
        }

        // Check if the request class is excluded
        if (in_array(get_class($response->getRequest()), $excludedBodies)) {
            return 'REDACTED';
        }

        // Non-seekable bodies (e.g. Guzzle's `stream => true`) can only be read once, so reading
        // one here would leave it at EOF and hand the application an empty body. Record a
        // placeholder instead, matching how streamed request bodies are handled.
        if (! $response->getPsrResponse()->getBody()->isSeekable()) {
            return '<Streamed Body>';
        }

        $contentTypeHeaderKey = $response->headers()->get('Content-Type') ? 'Content-Type' : 'content-type';

        if (! Str::startsWith(mb_strtolower((string) $response->headers()->get($contentTypeHeaderKey)), self::supportedContentTypes())) {
            return '<Unsupported Barstool Response Content>';
        }

        $body = $response->body();

        return self::checkContentSize($body) ? $body : '<Unsupported Barstool Response Content>';
    }

    /**
     * Check if the content is within limits
     */
    private static function checkContentSize(mixed $body): bool
    {
        try {
            $body = (string) $body;

            return intdiv(mb_strlen($body), 1000) <= config('barstool.max_response_size', 100);
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get the supported content types for response bodies.
     *
     * @return string[]
     */
    private static function supportedContentTypes(): array
    {
        return [
            'application/json',
            'application/xml',
            'application/soap+xml',
            'text/xml',
            'text/html',
            'text/plain',
        ];
    }
}
