<?php /**
 * @noinspection PhpMissingFieldTypeInspection
 * @noinspection ReturnTypeCanBeDeclaredInspection
 */

declare(strict_types=1);

namespace Authanram\LaravelJetstreamPackage;

use Illuminate\Support\Facades\File;

final class InstallCommand extends \Laravel\Jetstream\Console\InstallCommand
{
    public function handle(): int
    {
        parent::handle();

        $this->moveFiles();

        $this->replaceInFiles();

        $this->dumpOptimized();

        $this->components->warn(
            'Please execute the [php artisan test] command to ensure everything worked out well.'
        );

        return self::SUCCESS;
    }

    protected function dumpOptimized(): void
    {
        $this->components->info('Generating optimized autoload files');

        resolve('composer')->dumpAutoloads(['--optimize']);
    }

    /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
    private function moveFiles(): void
    {
        $paths = config('laravel-jetstream-package.paths');

        $this->components->info('Moving files');

        foreach ($paths as $source => $destination) {
            $this->output->write("  <fg=green>Moving</> {$source}... ");

            if (File::exists($source) === false) {
                $this->output->write("<fg=yellow>skipped</>\n");
                continue;
            }

            $this->move($source, $destination);

            $this->output->write("<fg=green>done</>\n");
        }
    }

    private function move(string $source, string $destination): void
    {
        if (File::isDirectory($source)) {
            File::ensureDirectoryExists($destination);
            File::moveDirectory($source, $destination);
            return;
        }

        File::ensureDirectoryExists(dirname($destination));
        File::move($source, $destination);
    }

    /** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
    private function replaceInFiles(): void
    {
        $replace = config('laravel-jetstream-package.replace');

        foreach ($replace as $path => $contents) {
            $relativePath = str_replace(base_path().'/', '', $path);

            $this->components->info("Replacing contents [{$relativePath}]");

            $this->replaceAndOutput($contents, $path);
        }
    }

    /**
     * @param array<int, string> $contents
     *
     * @noinspection PhpDocSignatureIsNotCompleteInspection
     */
    private function replaceAndOutput(array $contents, string $path): void
    {
        foreach ($contents as $content) {
            $this->output->write(implode('', [
                "  <fg=green>Search:</>\n{$content['search']}\n\n",
                "  <fg=green>Replace:</>\n{$content['replace']}\n\n",
                '  ... ',
            ]));

            if (str_contains(file_get_contents($path), $content['search']) === false) {
                $this->output->write("<fg=yellow>skipped</>\n");
                continue;
            }

            $this->replaceInFile($content['search'], $content['replace'], $path);

            $this->output->write("<fg=green>done</>\n");
        }
    }
}
