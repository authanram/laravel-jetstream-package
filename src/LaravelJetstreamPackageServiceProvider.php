<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace Authanram\LaravelJetstreamPackage;

use Illuminate\Support\ServiceProvider;

class LaravelJetstreamPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-jetstream-package.php',
            'laravel-jetstream-package',
        );

        $this->app->extend(\Laravel\Jetstream\Console\InstallCommand::class, function() {
            return new InstallCommand();
        });
    }
}
