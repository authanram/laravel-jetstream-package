<?php

declare(strict_types=1);

namespace Authanram\LaravelJetstreamPackage\Tests;

use Authanram\LaravelJetstreamPackage\LaravelJetstreamPackageServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelJetstreamPackageServiceProvider::class,
        ];
    }
}
