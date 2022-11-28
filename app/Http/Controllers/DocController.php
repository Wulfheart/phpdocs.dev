<?php

namespace App\Http\Controllers;

use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\Package;
use App\Models\PackageVersion;
use App\ViewModels\Navigation;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class DocController extends Controller
{
    public function __construct(
        protected RetrieveIndexAction $retrieveIndexAction,
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

        $index = $this->retrieveIndexAction->execute($version);


        $nav = Navigation::fromIndex($index);

        $namespace ??= $nav->rootNamespace->name;

        $namespace = explode(".", $namespace);
        $nav->activate($namespace);
        return view('docs.index', [
            'index' => $nav,
        ]);

    }
}
