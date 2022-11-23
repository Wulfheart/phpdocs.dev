<?php

namespace App\Console\Commands;

use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Actions\ApiGen\SaveAnalyzeResultAction;
use Archive_Tar;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Thettler\LaravelConsoleToolkit\Attributes\Argument;
use Thettler\LaravelConsoleToolkit\Attributes\ArtisanCommand;
use Thettler\LaravelConsoleToolkit\Concerns\UsesConsoleToolkit;

#[ArtisanCommand('suki:index')]
class TestCommand extends Command
{
    use UsesConsoleToolkit;

    #[Argument('projectDir', 'The project directory to index')]
    public string $directory;

    public function handle(AnalyzeProjectAction $indexProjectAction, SaveAnalyzeResultAction $saveAnalyzeResultAction): int
    {

        $this->line('Indexing project in ' . realpath($this->directory));
        $projectdir = realpath($this->directory);

        $result = $indexProjectAction->execute($projectdir);
        $this->info("Done indexing project");
        $compressed = gzencode(serialize($result));
        file_put_contents('test.json', $compressed);

        Benchmark::dd(function (){
           $s = file_get_contents('test.json');
           $s = gzdecode($s);
              $r = unserialize($s);
              var_dump(get_class($r));
        });

        //$saveAnalyzeResultAction->execute(null, $result);

        return Command::SUCCESS;
    }
}
