<?php

namespace App\Console\Commands;

use App\Models\Package;
use App\Repositories\PackageRepository;
use Illuminate\Console\Command;
use Thettler\LaravelConsoleToolkit\Attributes\Argument;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:cache')]
class CacheCommand extends Command
{
    use UsesConsoleToolkit;

    #[Argument]
    public string $packageName;
    #[Argument('version')]
    public string $version;

    public function handle(
        PackageRepository $packageRepository
    ): int
    {
        [$vendor, $package] = explode('/', $this->packageName);
        $package = Package::with('versions')->where(['vendor' => $vendor, 'package' => $package])->firstOrFail();

        $packageVersion = $package->versions()->where('name', $this->version)->firstOrFail();

        $packageRepository->set($packageVersion);

        $packageRepository->cache();

        $nav = $packageRepository->getNavigation();

        $this->info("Cached {$packageVersion->name}");

        return Command::SUCCESS;
    }
}
