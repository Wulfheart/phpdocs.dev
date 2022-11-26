<?php

namespace App\Actions\Packagist;

use App\Models\Package;
use App\Models\PackageVersion;
use Composer\MetadataMinifier\MetadataMinifier;
use Illuminate\Support\Facades\Http;
use Spatie\QueueableAction\QueueableAction;

class InitializePackageFromPackagistAction
{
    /**
     * @throws \Exception
     */
    public function execute(string $vendor, string $package): void
    {
        $response = Http::get(config('packagist.base_url') . "/p2/{$vendor}/{$package}.json");
        if($response->serverError()){
            throw new \Exception("Package {$vendor}/{$package} fetching error");
        }
        $minifiedData = $response->json()['packages']["$vendor/$package"];
        $expanded = MetadataMinifier::expand($minifiedData);
        if(count($expanded) === 0){
            logger()->warning("Package {$vendor}/{$package} has no versions");
            return;
        }

        $package = Package::create([
            'vendor' => $vendor,
            'package' => $package,
            'repository_url' => $expanded[0]['source']['url'],
            'license' => $expanded[0]['license'][0] ?? null,
        ]);

        $versionCount = count($expanded);
        for ($i = 0; $i < $versionCount; $i++) {
            $version = $expanded[($versionCount -1) - $i];
            PackageVersion::create([
                'dist_type' => $version['dist']['type'],
                'dist_url' => $version['dist']['url'],
                'name' => $version['version'],
                'package_id' => $package->id,
                'published_at' => $version['time'],
            ]);
        }



    }

}
