<?php

namespace App\Actions\ApiGen;

use ApiGen\Analyzer;
use ApiGen\Analyzer\AnalyzeResult;
use ApiGen\Analyzer\NodeVisitors\PhpDocResolver;
use ApiGen\Locator;
use App\Models\PackageVersion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use ZanySoft\Zip\Zip;

class AnalyzeProjectAction
{
    public function execute(PackageVersion $packageVersion): AnalyzeResult{
        $temporaryDirectory = (new TemporaryDirectory())->create();
        $path = $this->downloadProject($packageVersion, $temporaryDirectory);

        $analyzeResult = $this->executeApiGen($path);
        $temporaryDirectory->delete();
        return $analyzeResult;
    }

    protected function downloadProject(PackageVersion $packageVersion, TemporaryDirectory $temporaryDirectory): string {
        if($packageVersion->dist_type != 'zip'){
            throw new \Exception('Unsupported dist type');
        }
        $response = Http::withOptions([
            'decode-content' => 'gzip',
        ])->get($packageVersion->dist_url);

        $zipPath = $temporaryDirectory->path('dist.zip');
        $fp = fopen($zipPath, 'w');
        fwrite($fp, $response->toPsrResponse()->getBody());
        fclose($fp);

        $zip = new Zip();
        $zip->open($zipPath);
        $extractedPath = $temporaryDirectory->path('extracted');
        $zip->extract($extractedPath);

        $zip->close();

        $dirs = Finder::create()->in($extractedPath)->depth(0)->directories()->getIterator();
        return iterator_to_array($dirs, false)[0]->getRealPath();
    }

    protected function executeApiGen(string $projectDir): AnalyzeResult {

        $output = new NullOutput();
        $outputStyle = new SymfonyStyle(new StringInput(''), $output);
        // Build analyzer
        $locator = Locator::create($outputStyle, $projectDir);
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new Analyzer\NodeVisitors\BodySkipper());
        $nameResolver = new NameResolver();
        $nodeTraverser->addVisitor($nameResolver);

        $phpdocparserlexer = new \PHPStan\PhpDocParser\Lexer\Lexer();

        $constExprParser = new ConstExprParser();
        $typeParser = new TypeParser($constExprParser);
        $phpdocparser = new PhpDocParser($typeParser, $constExprParser, true);

        $nodeTraverser->addVisitor(new PhpDocResolver($phpdocparserlexer, $phpdocparser, $nameResolver->getNameContext()));


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
