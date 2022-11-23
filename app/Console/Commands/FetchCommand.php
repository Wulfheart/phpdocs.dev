<?php

namespace App\Console\Commands;

use App\Actions\Packagist\InitializePackageFromPackagistAction;
use Illuminate\Console\Command;
use Thettler\LaravelConsoleToolkit\Attributes\Argument;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:fetch')]
class FetchCommand extends Command
{
    use UsesConsoleToolkit;

    #[Argument]
    public string $vendor;
    #[Argument]
    public string $package;

    public function handle(
        InitializePackageFromPackagistAction $initializePackageFromPackagistAction
    ): int
    {
        $initializePackageFromPackagistAction->execute($this->vendor, $this->package);
        return Command::SUCCESS;
    }
}
