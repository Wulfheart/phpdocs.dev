<?php

namespace App\Console\Commands;

use ApiGen\Bootstrap;
use App\Actions\IndexProjectAction;
use Illuminate\Console\Command;
use Thettler\LaravelConsoleToolkit\Attributes\Argument;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:index')]
class TestCommand extends Command
{
    use UsesConsoleToolkit;

    #[Argument('projectDir', 'The project directory to index')]
    public string $directory;

    public function handle(IndexProjectAction $indexProjectAction)
    {

        $this->line('Indexing project in ' . realpath($this->directory));
        $projectdir = realpath($this->directory);

        $result = $indexProjectAction->execute($projectdir);


        return Command::SUCCESS;
    }
}
