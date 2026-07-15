<?php

declare(strict_types=1);

namespace Saloon\Barstool;

use Saloon\Http\Response;
use Saloon\Http\PendingRequest;
use Saloon\Barstool\Enums\RecordingType;
use Saloon\Barstool\Jobs\RecordBarstoolJob;
use Saloon\Exceptions\Request\FatalRequestException;

class Barstool
{
    public static function shouldRecord(PendingRequest|Response|FatalRequestException $data): bool
    {
        if (config('barstool.enabled') !== true) {
            return false;
        }

        [$connector, $request] = match (true) {
            $data instanceof PendingRequest => [$data->getConnector(), $data->getRequest()],
            $data instanceof Response, $data instanceof FatalRequestException => [$data->getPendingRequest()->getConnector(), $data->getPendingRequest()->getRequest()],
        };

        if (in_array(get_class($connector), config('barstool.ignore.connectors', []))) {
            return false;
        }

        if (in_array(get_class($request), config('barstool.ignore.requests', []))) {
            return false;
        }

        return true;
    }

    public static function calculateDuration(Response|PendingRequest $data): int
    {
        $config = $data->getConnector()->config();

        $requestTime = (int) $config->get('barstool-request-time');
        $responseTime = (int) $config->get('barstool-response-time', microtime(true) * 1000);

        return $responseTime - $requestTime;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function persist(RecordingType $type, array $payload, string $uuid): void
    {
        if (self::shouldQueue()) {
            RecordBarstoolJob::dispatch($type, $payload, $uuid)
                ->onConnection(config('barstool.queue.connection'))
                ->onQueue(config('barstool.queue.queue'));

            return;
        }

        Models\Barstool::query()->updateOrCreate(
            ['uuid' => $uuid],
            $payload,
        );
    }

    private static function shouldQueue(): bool
    {
        return config('barstool.queue.enabled', false) === true;
    }
}
