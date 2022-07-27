<?php /** @noinspection PhpUnusedParameterInspection */

/**
 * @noinspection PhpMissingFieldTypeInspection
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection PhpUndefinedMethodInspection
 * @noinspection ReturnTypeCanBeDeclaredInspection
 */

namespace Authanram\LaravelJetstreamPackage;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;

class UpdateCommand extends Command
{
    protected $signature = 'jetstream-package:update';
    protected $description = 'Update package stubs';

    public function handle()
    {
        $paths = [
            [
                'source' => __DIR__.'/../vendor/laravel/jetstream/stubs',
                'destination' => __DIR__.'/../stubs/jetstream',
            ],
            [
                'source' => __DIR__.'/../vendor/laravel/fortify/stubs',
                'destination' => __DIR__.'/../stubs/fortify',
            ],
        ];

        foreach ($paths as $path) {
            $this->handleDirectory($path['source'], $path['destination']);
        }

        return static::SUCCESS;
    }

    private function handleDirectory($sourcePath, $destinationPath)
    {
        File::ensureDirectoryExists($destinationPath);

        $this->task("<fg=green>Delete directory</> $destinationPath", function () use ($destinationPath) {
            if (File::deleteDirectory($destinationPath) === false) {
                throw new RuntimeException(
                    "Failed to delete directory: $destinationPath",
                );
            }
        });

        $this->task("<fg=green>Copy</> $sourcePath <fg=green>to</> $destinationPath", function () use ($sourcePath, $destinationPath) {
            if (File::copyDirectory($sourcePath, $destinationPath) === false) {
                throw new RuntimeException(
                    "Failed to copy $sourcePath to $destinationPath",
                );
            }
        });
    }

    private function task($task, $callable)
    {
        $this->output->write("$task ");

        $callable();

        $this->output->write('<fg=green>✔</>');

        $this->newLine();
    }
}
