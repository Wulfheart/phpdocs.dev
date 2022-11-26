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
        $response = Http::get(config('packagist.base_url') . "/packages/{$vendor}/{$package}.json");
        if($response->serverError()){
            throw new \Exception("Package {$vendor}/{$package} fetching error");
        }
        $data = $response->json()['package'];

        $package = Package::create([
            'vendor' => $vendor,
            'package' => $package,
            'repository_url' => $data['repository'],
            'license' => null,
            'description' => $data['description'],
            'stats_dependents' => $data['dependents'],
            'stats_favers' => $data['favers'],
            'stats_suggesters' => $data['suggesters'],
            'stats_github_stars' => $data['github_stars'],
            'stats_github_forks' => $data['github_forks'],
            'stats_github_watchers' => $data['github_watchers'],
            'stats_github_open_issues' => $data['github_open_issues'],
        ]);
        foreach ($data['versions'] as $version) {
            PackageVersion::create([
                'dist_type' => $version['dist']['type'],
                'dist_url' => $version['dist']['url'],
                'name' => $version['version'],
                'package_id' => $package->id,
                'published_at' => $version['time'],
                'name_normalized' => $version['version_normalized'],
            ]);
        }
    }

}
