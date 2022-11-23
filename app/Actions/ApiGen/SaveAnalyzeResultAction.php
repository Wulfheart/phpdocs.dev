<?php

namespace App\Actions\ApiGen;

use ApiGen\Analyzer\AnalyzeResult;
use App\Models\PackageVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveAnalyzeResultAction
{
    public function execute(PackageVersion $packageVersion, AnalyzeResult $result): void
    {
        $uuid = Str::uuid()->toString();
        $path = $uuid . '.serialized.gz';
        Storage::disk('analyzed')->put($path, gzencode(serialize($result)));

        if($packageVersion->serialized_location != null){
            Storage::disk('analyzed')->delete($packageVersion->serialized_location);
        }
        $packageVersion->serialized_location = $path;
        $packageVersion->serialized_created_at = now();
        $packageVersion->save();

    }

}
