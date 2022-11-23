<?php

namespace App\Actions;

use ApiGen\Analyzer;
use ApiGen\Analyzer\AnalyzeResult;
use ApiGen\Locator;
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

class IndexProjectAction
{
    public function execute(string $projectDir): AnalyzeResult {
        $output = new NullOutput();
        $outputStyle = new SymfonyStyle(new StringInput(''), $output);
        // Build analyzer
        $locator = Locator::create($outputStyle, $projectDir);
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new Analyzer\NodeVisitors\BodySkipper());
        $nodeTraverser->addVisitor(new NameResolver());

        $phpdocparserlexer = new \PHPStan\PhpDocParser\Lexer\Lexer();
        $phpdocparser = new \PHPStan\PhpDocParser\Parser\PhpDocParser();
        $filter = new Analyzer\Filter(false, false, []);

        $analyzer = new Analyzer($locator, $parser, $nodeTraverser, $filter);

        $files = $this->findFiles($projectDir);
        $progressbar = new ProgressBar($output);
        return $analyzer->analyze($progressbar, $files);
    }

    /**
     * @return array<string>
     */
    protected function findFiles(string $projectDir): array {
        $finder = Finder::create()
            ->in($projectDir)
            ->files()
            ->name('*.php')
            ->exclude('vendor');
        $filenames = [];
        foreach ($finder as $file) {
            $filenames[] = $file->getRealPath();
        }
        return $filenames;
    }



}
