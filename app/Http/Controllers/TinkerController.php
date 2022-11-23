<?php

namespace App\Http\Controllers;

use ApiGen\Renderer\Latte\LatteExtension;
use ApiGen\Renderer\Latte\LatteFunctions;
use App\Actions\ApiGen\RetrieveIndexAction;
use App\Models\Package;
use Illuminate\Http\Request;
use Latte\Engine;
use Latte\Loaders\FileLoader;

class TinkerController extends Controller
{
    public function __construct(
        protected RetrieveIndexAction $retrieveIndexAction,
    ){

    }
    public function __invoke(Request $request)
    {
        $engine = new Engine();
        $engine->setLoader(new FileLoader(resource_path('latte')));


        //$engine->addExtension()

        $package = Package::with('versions')->where(['vendor' => 'wulfheart', 'package' => 'pretty_routes'])->firstOrFail();

        $version = $package->versions->first();

        $index = $this->retrieveIndexAction->execute($version);
        //dd($index->namespace);

        return view('docs.index', [
            'index' => $index,
        ]);

    }
}
