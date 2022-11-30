<?php

namespace App\Http\Controllers;

use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\Package;
use App\Repositories\PackageRepository;
use App\ViewModels\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Karriere\JsonDecoder\JsonDecoder;

class TinkerController extends Controller
{
    public function __construct(
        protected PackageRepository $packageRepository,
    ){

    }
    public function __invoke(Request $request)
    {

        $package = Package::with('versions')->where(['vendor' => 'apigen', 'package' => 'apigen'])->firstOrFail();

        //$package->versions->pluck('name')->dd();
        $version = $package->versions->where('name', 'v7.0.0-alpha.3')->firstOrFail();

        $this->packageRepository->set($version);
        $this->packageRepository->cache();

        //$index = $this->retrieveIndexAction->execute($version);

        dd($index);

    }
}
