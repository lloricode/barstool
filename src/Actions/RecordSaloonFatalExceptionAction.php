<?php

declare(strict_types=1);

namespace Saloon\Barstool\Actions;

use Saloon\Barstool\Barstool;
use Psr\Http\Message\UriInterface;
use Saloon\Barstool\Enums\RecordingType;
use Saloon\Exceptions\Request\FatalRequestException;

class RecordSaloonFatalExceptionAction
{
    public function execute(FatalRequestException $exception): void
    {
        if (Barstool::shouldRecord($exception) === false) {
            return;
        }

        $pendingRequest = $exception->getPendingRequest();
        $uuid = $pendingRequest->headers()->get('X-Barstool-UUID');

        $payload = [
            'duration' => Barstool::calculateDuration($pendingRequest),
            ...self::getFatalData($exception),
        ];

        Barstool::persist(RecordingType::FATAL, $payload, $uuid);
    }

    /**
     * @return array{
     *      url: UriInterface,
     *      response_headers: null,
     *      response_body: null,
     *      response_status: null,
     *      successful: false,
     *      fatal_error: string
     * }
     */
    private static function getFatalData(FatalRequestException $exception): array
    {
        return [
            'url' => $exception->getPendingRequest()->getUri(),
            'response_headers' => null,
            'response_body' => null,
            'response_status' => null,
            'successful' => false,
            'fatal_error' => $exception->getMessage(),
        ];
    }
}
