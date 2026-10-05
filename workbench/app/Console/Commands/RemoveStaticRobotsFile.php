<?php

declare(strict_types=1);

namespace Workbench\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveStaticRobotsFile extends Command
{
    protected $signature = 'workbench:remove-static-robots-file';

    protected $description = 'Delete a public/robots.txt left by the Export action, so Botly serves /robots.txt';

    public function handle(): int
    {
        File::delete(public_path('robots.txt'));

        $this->components->info('No static robots.txt in the public directory.');

        return self::SUCCESS;
    }
}
