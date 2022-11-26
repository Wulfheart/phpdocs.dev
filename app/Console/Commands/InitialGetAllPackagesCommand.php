<?php

namespace App\Console\Commands;

use App\Actions\Packagist\InitializePackageFromPackagistAction;
use App\Jobs\Packagist\InitializePackageJob;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:init')]
class InitialGetAllPackagesCommand extends Command
{
    use UsesConsoleToolkit;

    public function handle(
        InitializePackageFromPackagistAction $initializePackageFromPackagistAction
    ): int
    {
        $this->line('Starting initial package fetch');
        $packageNames = Http::get(config('packagist.base_url') . '/packages/list.json')
            ->json()['packageNames'];
        $count = count($packageNames);
        $this->info('Fetched ' . $count . ' packages');
        $progressbar = $this->output->createProgressBar($count);
        collect($packageNames)->chunk(10_000)->each(
            function (Collection $chunk) use (
                $initializePackageFromPackagistAction,
                $progressbar,
            ) {
                $jobs = $chunk->map(
                    function ($packageName) use (
                        $initializePackageFromPackagistAction,
                        $progressbar,
                    ) {
                        $progressbar->advance();
                        return new  InitializePackageJob(...explode('/', $packageName));

                        //$initializePackageFromPackagistAction
                        //    ->onQueue()
                        //    ->execute();
                    }
                )->toArray();
                \Queue::bulk($jobs);
            });
        return Command::SUCCESS;
    }
}
