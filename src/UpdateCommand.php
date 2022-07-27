<?php /**
 * @noinspection PhpMissingFieldTypeInspection
 * @noinspection PhpMissingReturnTypeInspection
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

    private $sourcePath = '';
    private $destinationPath = '';

    public function handle()
    {
        $this->sourcePath = base_path('vendor/laravel/jetstream/stubs');
        $this->destinationPath = __DIR__.'/../stubs';

        $this->task('Installing Laravel', function () {
            return true;
        });

        if ($this->deleteStubsDirectory() === false) {
            throw new RuntimeException(
                "Failed to delete directory: $this->sourcePath",
            );
        }

        $this->info('');

        if ($this->copyStubsDirectory() === false) {
            throw new RuntimeException(
                "Failed to copy directory $this->sourcePath to $this->destinationPath",
            );
        }

        $this->info("Directory copied from $this->sourcePath to $this->destinationPath");

        return static::SUCCESS;
    }

    private function deleteStubsDirectory()
    {
        return File::deleteDirectory($this->sourcePath);
    }

    private function copyStubsDirectory()
    {
        return File::copyDirectory($this->destinationPath, $this->sourcePath);
    }
}

