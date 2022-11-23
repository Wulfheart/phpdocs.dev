<?php

namespace App\Actions\ApiGen;

use ApiGen\Analyzer\AnalyzeResult;
use ApiGen\Index\Index;
use ApiGen\Indexer;
use App\ApiGen\CustomIndexer;
use App\Models\PackageVersion;
use Illuminate\Support\Facades\Storage;

class RetrieveIndexAction
{
    public function execute(PackageVersion $packageVersion): Index
    {
        // create index
        $compressed = Storage::disk('analyzed')->get($packageVersion->serialized_location);

        $analyzeProjectAction = new AnalyzeProjectAction();
        $analyzeResult = $analyzeProjectAction->execute($packageVersion);

        //$analyzeResult = unserialize(gzdecode($compressed));
        return $this->index($analyzeResult);

    }

    protected function index(AnalyzeResult $analyzeResult): Index {
        $indexer = new CustomIndexer();
        $index = new Index();

        foreach ($analyzeResult->classLike as $info) {
            $indexer->indexFile($index, $info->file, $info->primary);
            $indexer->indexNamespace($index, $info->name->namespace, $info->name->namespaceLower, $info->primary, $info->isDeprecated());
            $indexer->indexClassLike($index, $info);
        }

        foreach ($analyzeResult->function as $info) {
            $indexer->indexFile($index, $info->file, $info->primary);
            $indexer->indexNamespace($index, $info->name->namespace, $info->name->namespaceLower, $info->primary, $info->isDeprecated());
            $indexer->indexFunction($index, $info);
        }


        $indexer->postProcess($index);
        return $index;
    }

}
