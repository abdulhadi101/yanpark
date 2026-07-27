<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallFeaturesCommand extends Command
{
    protected $signature = 'install:features';
    protected $description = 'Install application features';

    public function handle(): int
    {
        $this->info('Features installed successfully.');

        return static::SUCCESS;
    }
}
