<?php

namespace App\Console\Commands;

use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Actions\ApiGen\RetrieveIndexAction;
use App\Actions\ApiGen\SaveAnalyzeResultAction;
use App\Models\Package;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Thettler\LaravelConsoleToolkit\Attributes\Argument;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:benchmark')]
class BenchmarkCommand extends Command
{
    use UsesConsoleToolkit;

    #[Argument('package')]
    public string $package;
    #[Argument('version')]
    public string $version;

    public function handle(RetrieveIndexAction $retrieveIndexAction): int
    {
        [$vendor, $name] = explode('/', $this->argument('package'));
        $package = Package::with('versions')
            ->where('vendor', $vendor)
            ->where('package', $name)
            ->firstOrFail();
        $packageVersion = $package->versions()->where('name', $this->argument('version'))->firstOrFail();
        Benchmark::dd(function () use ($retrieveIndexAction, $package, $packageVersion) {
            $index = $retrieveIndexAction->execute($packageVersion);
        });

        return Command::SUCCESS;
    }
}
