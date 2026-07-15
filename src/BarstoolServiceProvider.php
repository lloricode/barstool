<?php

declare(strict_types=1);

namespace Saloon\Barstool;

use Saloon\Config;
use Saloon\Http\Response;
use Saloon\Enums\PipeOrder;
use Saloon\Http\PendingRequest;
use Spatie\LaravelPackageTools\Package;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Barstool\Support\Config as BarstoolConfig;
use Saloon\Barstool\Actions\RecordSaloonRequestAction;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Saloon\Barstool\Actions\RecordSaloonResponseAction;
use Saloon\Barstool\Actions\RecordSaloonFatalExceptionAction;

class BarstoolServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('barstool')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_barstools_table');
    }

    public function packageRegistered(): void
    {
        Config::globalMiddleware()
            ->onFatalException(static function (FatalRequestException $exception): void {

                BarstoolConfig::getAction(
                    'record_fatal_exception',
                    RecordSaloonFatalExceptionAction::class
                )->execute($exception);

            }, order: PipeOrder::FIRST)
            ->onRequest(static function (PendingRequest $request): void {

                BarstoolConfig::getAction(
                    'record_request',
                    RecordSaloonRequestAction::class
                )->execute($request);

            })
            ->onResponse(static function (Response $response): void {

                BarstoolConfig::getAction(
                    'record_response',
                    RecordSaloonResponseAction::class
                )->execute($response);

            });
    }
}
