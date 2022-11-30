<?php

namespace App\Http\Controllers;

use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\Package;
use App\Models\PackageVersion;
use App\Repositories\PackageRepository;
use App\ViewModels\Navigation;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class DocController extends Controller
{
    public function __construct(
        protected PackageRepository $packageRepository,
    )
    {
    }

    public function namespace(
        string $vendor,
        string $package,
        string $version,
        ?string $namespace = null,
    ){
        $package = Package::with('versions')
            ->where('vendor', $vendor)
            ->where('package', $package)
            ->firstOrFail();
        /** @var PackageVersion $version */
        $version = $package->versions->where('name', $version)->firstOrFail();

        if(!$version->alreadyAnalyzed()){
            return abort(404);
        }

        $this->packageRepository->set($version);
        $nav = $this->packageRepository->getNavigation();

        $namespace ??= $nav->rootNamespace->name;

        $namespace = explode(".", $namespace);
        $nav->activate($namespace);
        return view('docs.index', [
            'index' => $nav,
        ]);

    }

    public function class(
        string $vendor,
        string $package,
        string $version,
        string $namespace,
        string $class,
    ){
        $package = Package::with('versions')
            ->where('vendor', $vendor)
            ->where('package', $package)
            ->firstOrFail();
        /** @var PackageVersion $version */
        $version = $package->versions->where('name', $version)->firstOrFail();

        if(!$version->alreadyAnalyzed()){
            return abort(404);
        }

        $this->packageRepository->set($version);
        $nav = $this->packageRepository->getNavigation();
        $namespaces = explode(".", $namespace);
        $nav->activate($namespaces, new Navigation\ClassLikeInfo($class));

        $classInfo = $this->packageRepository->getClass($namespace, $class);
        dd($classInfo);
        return view('docs.class', [
            'index' => $nav,
        ]);

    }
}
