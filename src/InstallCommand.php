<?php /**
 * @noinspection PhpMissingFieldTypeInspection
 * @noinspection ReturnTypeCanBeDeclaredInspection
 */

namespace Authanram\LaravelJetstreamPackage;

use Illuminate\Support\Facades\File;

class InstallCommand extends \Laravel\Jetstream\Console\InstallCommand
{
    private $shouldDumpOptimized = false;

    public function handle()
    {
        //parent::handle();

        $this->moveFiles();

        $this->replaceInFiles();

        $this->dumpOptimized();

        $this->components->warn('Please execute the [php artisan test] command to ensure everything worked out well.');
    }

    private function moveFiles()
    {
        $paths = config('laravel-jetstream-package.paths');

        $this->components->info('Moving files');

        foreach ($paths as $source => $destination) {
            $this->output->write("  <fg=green>Moving</> $source... ");

            if (File::exists($source) === false) {
                $this->output->write("<fg=yellow>skipped</>\n");
                continue;
            }

            $this->move($source, $destination);

            $this->output->write("<fg=green>done</>\n");

            if (str_contains($source, app_path()) === false) {
                continue;
            }

            $this->shouldDumpOptimized = true;
        }
    }

    private function move($source, $destination)
    {
        $isDirectory = File::isDirectory($source);

        File::ensureDirectoryExists($isDirectory ? $destination : dirname($destination));

        $method = $isDirectory ? 'moveDirectory' : 'move';

        File::{$method}($source, $destination);
    }

    private function replaceInFiles()
    {
        $replace = config('laravel-jetstream-package.replace');

        foreach ($replace as $path => $contents) {
            $relativePath = str_replace(base_path().'/', '', $path);

            $this->components->info("Replacing contents [$relativePath]");

            foreach ($contents as $content) {
                $searchContent = $content['search'];
                $replaceContent = $content['replace'];

                $this->output->write(
                    "  <fg=green>Search:</>\n$searchContent\n\n  <fg=green>Replace:</>\n$replaceContent\n\n  ... ",
                );

                if (str_contains(file_get_contents($path), $searchContent) === false) {
                    $this->output->write("<fg=yellow>skipped</>\n");
                    continue;
                }

                $this->replaceInFile($searchContent, $replaceContent, $path);

                $this->output->write("<fg=green>done</>\n");
            }
        }
    }

    private function dumpOptimized()
    {
        if ($this->shouldDumpOptimized === false) {
            return;
        }

        $this->components->info("Generating optimized autoload files (authoritative)");

        resolve('composer')->dumpAutoloads([
            '--optimize',
            '--classmap-authoritative',
        ]);
    }
}
