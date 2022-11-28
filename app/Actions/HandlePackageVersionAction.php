<?php

namespace App\Actions;

use App\Actions\ApiGen\AnalyzeProjectAction;
use App\Models\PackageVersion;
use Illuminate\Support\Facades\Bus;

class HandlePackageVersionAction
{
    public function __construct(
        protected AnalyzeProjectAction $analyzePackageVersionAction,
    )
    {
    }

    public function execute(PackageVersion $packageVersion): void
    {


    }

}
