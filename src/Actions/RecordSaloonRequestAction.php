<?php

declare(strict_types=1);

namespace Saloon\Barstool\Actions;

use Illuminate\Support\Str;
use Saloon\Barstool\Barstool;
use Saloon\Http\PendingRequest;
use Saloon\Barstool\Enums\RecordingType;
use Saloon\Contracts\Body\BodyRepository;
use Saloon\Repositories\Body\StreamBodyRepository;
use Saloon\Repositories\Body\MultipartBodyRepository;

class RecordSaloonRequestAction
{
    public function execute(PendingRequest $request): void
    {

        if (Barstool::shouldRecord($request) === false) {
            return;
        }

        $request->getConnector()->config()->add(
            'barstool-request-time',
            microtime(true) * 1000
        );

        $uuid = Str::uuid()->toString();

        $request->headers()->add('X-Barstool-UUID', $uuid);

        Barstool::persist(RecordingType::REQUEST, self::getRequestData($request), $uuid);
    }

    /**
     * @return array{
     *      connector_class: class-string,
     *      request_class: class-string,
     *      method: string,
     *      url: string,
     *      request_headers: array<string, string>|null,
     *      request_body: BodyRepository|string|null,
     *      successful: false
     * }
     */
    private static function getRequestData(PendingRequest $request): array
    {
        $body = $request->body();

        $body = match (true) {
            $body instanceof StreamBodyRepository => '<Streamed Body>',
            $body instanceof MultipartBodyRepository => '<Multipart Body>',
            default => $body,
        };

        return [
            'connector_class' => get_class($request->getConnector()),
            'request_class' => get_class($request->getRequest()),
            'method' => $request->getMethod()->value,
            'url' => $request->getUrl(),
            'request_headers' => self::getRequestHeaders($request),
            'request_body' => $body,
            'successful' => false,
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function getRequestHeaders(PendingRequest $request): array
    {
        $excludedHeaders = config('barstool.excluded_request_headers', []);
        $headers = collect($request->headers()->all());

        // Check if all headers are excluded
        if (in_array('*', $excludedHeaders)) {
            return $headers->reject(fn ($value, $key) => $key !== 'X-Barstool-UUID')->toArray();
        }

        // Check if the connector class is excluded
        if (in_array(get_class($request->getConnector()), $excludedHeaders)) {
            return $headers->reject(fn ($value, $key) => $key !== 'X-Barstool-UUID')->toArray();
        }

        // Check if the request class is excluded
        if (in_array(get_class($request->getRequest()), $excludedHeaders)) {
            return $headers->reject(fn ($value, $key) => $key !== 'X-Barstool-UUID')->toArray();
        }

        return $headers->map(function ($value, $key) use ($excludedHeaders) {
            if (in_array($key, $excludedHeaders)) {
                $value = 'REDACTED';
            }

            return $value;
        })->toArray();
    }
}
