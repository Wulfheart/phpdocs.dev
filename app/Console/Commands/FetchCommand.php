<?php

namespace App\Console\Commands;

use App\Actions\Packagist\InitializePackageFromPackagistAction;
use App\Models\Package;
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
        $packagePresent = Package::where(['vendor' => $this->vendor, 'package' => $this->package])->exists();
        if($packagePresent){
            $this->error("Package {$this->vendor}/{$this->package} already present");
            return Command::FAILURE;
        }

        $initializePackageFromPackagistAction->execute($this->vendor, $this->package);
        return Command::SUCCESS;
    }
}
