<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

declare(strict_types=1);

namespace Authanram\LaravelJetstreamPackage;

use Illuminate\Support\ServiceProvider;

final class LaravelJetstreamPackageServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-jetstream-package.php',
            'laravel-jetstream-package',
        );

        $this->app->extend(\Laravel\Jetstream\Console\InstallCommand::class, function () {
            return new InstallCommand();
        });
    }
}
