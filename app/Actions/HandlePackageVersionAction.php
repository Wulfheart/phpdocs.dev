<?php

namespace App\Actions;

use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\PackageVersion;
use App\ViewModels\Navigation;
use Illuminate\Support\Facades\Bus;

class HandlePackageVersionAction
{
    public function __construct(
        protected AnalyzeProjectAction $analyzePackageVersionAction,
        protected RetrieveIndexAction $retrieveIndexAction,
    )
    {
    }

    public function execute(PackageVersion $packageVersion): void
    {
        $analyzeResult = $this->analyzePackageVersionAction->execute($packageVersion);
        $index = $this->retrieveIndexAction->index($analyzeResult);

        $nav = Navigation::fromIndex($index);
        $encoded = gzencode(serialize($nav));


    }

}
