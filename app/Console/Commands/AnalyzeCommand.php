<?php

namespace App\Console\Commands;

use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Actions\ApiGen\SaveAnalyzeResultAction;
use App\Models\Package;
use Illuminate\Console\Command;
use Thettler\LaravelConsoleToolkit\Attributes\Argument;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:analyze')]
class AnalyzeCommand extends Command
{
    use UsesConsoleToolkit;

    #[Argument]
    public string $packageName;

    public function handle(
        AnalyzeProjectAction $analyzeProjectAction,
        SaveAnalyzeResultAction $saveAnalyzeResultAction,
    ): int
    {
        [$vendor, $package] = explode('/', $this->packageName);
        $package = Package::with('versions')->where(['vendor' => $vendor, 'package' => $package])->firstOrFail();

        foreach ($package->versions as $version) {
            $this->info("Analyzing {$package->vendor}/{$package->package} {$version->name}");
            if($version->alreadyAnalyzed()){
                $this->warn("Already analyzed");
                continue;
            }
            $analyzed = $analyzeProjectAction->execute($version);
            $saveAnalyzeResultAction->execute($version, $analyzed);
        }
        return Command::SUCCESS;
    }
}
