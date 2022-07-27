<?php /** @noinspection ReturnTypeCanBeDeclaredInspection */

namespace Authanram\LaravelJetstreamPackage;

use Illuminate\Support\ServiceProvider;

class LaravelJetstreamPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
       $this->commands([
           UpdateCommand::class,
       ]);
    }
}
