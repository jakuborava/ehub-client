<?php

namespace JakubOrava\EhubClient;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JakubOrava\EhubClient\Commands\EhubClientCommand;

class EhubClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ehub-client')
            ->hasConfigFile();
    }
}
